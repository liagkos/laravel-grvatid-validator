<?php
namespace Liagkos\Grvatval\Tests;

use Liagkos\Grvatval\Grvatval;
use Orchestra\Testbench\TestCase;

/**
 * Class GrvatvalTests
 * @package Liagkos\Grvatval\Tests
 */
class GrvatvalTests extends TestCase
{
    /**
     * @var \Validator Laravel validator instance
     */
    protected $validator;

    /**
     * @var string Form attribute
     */
    protected $attribute;

    /**
     * @var string Valid numeric VAT ID
     */
    protected $validVatId;

    /**
     * @var string Invalid numeric VAT ID with correct length
     */
    protected $invalidVatId;

    /**
     * @var string Invalid numeric VAT ID length
     */
    protected $invalidVatIdShortNumber;

    /**
     * @var string Invalid VAT ID combination with characters
     */
    protected $invalidVatIdString;

    /**
     * Prepare the test case and assign values
     */
    public function setUp()
    {
        parent::setUp();
        $this->attribute               = 'vatid';       // Form attribute, just for testing
        $this->validVatId              = '090000045';   // Public Energy Corporation
        $this->invalidVatId            = '090000044';   // Wrong VAT ID
        $this->invalidVatIdShortNumber = '09000004';    // Missing last number
        $this->invalidVatId            = '09000004A';   // Characters instead of numbers
    }

    /**
     * Test validate method of validator
     */
    public function testValidate()
    {
        $this->assertTrue($this->validate($this->validVatId)->passes());
        $this->assertTrue($this->validate($this->invalidVatId)->fails());
        $this->assertTrue($this->validate($this->invalidVatIdShortNumber)->fails());
        $this->assertTrue($this->validate($this->invalidVatIdString)->fails());
    }

    /**
     * Test message method of validator depending on value of VAT ID
     */
    public function testMessage()
    {
        $this->assertEquals(0, $this->validate($this->validVatId)->messages()->count());
        $this->assertEquals(1, $this->validate($this->invalidVatId)->messages()->count());
        $this->assertEquals(1, $this->validate($this->invalidVatIdShortNumber)->messages()->count());
        $this->assertEquals(1, $this->validate($this->invalidVatIdString)->messages()->count());
    }

    /**
     * Create and call the validator
     *
     * @param $vatId string VAT ID to check
     *
     * @return mixed
     */
    protected function validate($vatId)
    {
        return \Validator::make(
            [$this->attribute => $vatId],
            [$this->attribute => new Grvatval($this->attribute, $vatId)]
        );
    }
}