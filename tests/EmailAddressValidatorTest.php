<?php

    // Include PHPUnit
    require_once('PHPUnit/Framework.php');

    // Include the email address validator class
    require_once('../EmailAddressValidator.php');
     
    class EmailAddressValidatorTest extends PHPUnit_Framework_TestCase {

        protected $objValidator = null;

        // List of valid addresses according to standards
        private $arrValidAddresses = array(
             // Common email address types
                 'test@example.com' // Standard
                ,'TEST@example.com' // Upper case local part
                ,'1234567890@example.com' // Numeric local part
                ,'test+test@example.com' // Tagged "test"
                ,'test-test@example.com' // Tagged (qmail format)
             // Different local parts
                ,'t*est@example.com' // Unusual characters
                ,'+1~1+@example.com'
                ,'{_dave_}@example.com'
                ,'"[[ dave ]]"@example.com' // Quoted local part
                ,'test.test@example.com' // Dot-separated local part
                ,'dave."dave"@example.com' // Obsolete label (combination)
                ,'"test@test"@example.com' // "@" symbol in quoted local part
             // Different domain parts
                ,'test@123.123.123.123' // IP address
                ,'test@[123.123.123.123]' // IP in square brackets
                ,'test@example.example.com' // Multiple domain labels
            );

        // List of invalid addresses according to standards
        private $arrInvalidAddresses = array(
             // Different local parts
                 'test.example.com' // No "@" symbol
                ,'test.@example.com' // Period is a seperator in local part
                ,'test@test@example.com' // Multiple "@" symbols.
                ,'test@@example.com' // Multiple adjacent "@" symbols.
                ,'test..test@example.com' // Local part sections cannot be blank
                ,'-- test --@example.com' // No spaces allowed in local part
                ,'[test]@example.com' // Square brackets only allowed within quotes
                ,'.test@example.com' // Local part cannot start with a period
                ,'12345678901234567890123456789012345678901234567890123456789012345@example.com' // Local part should not be over 64 chars (this is 65)
                ,'"test\test"@example.com' // Quotes cannot contain a backslash
                ,'"test"test"@example.com' // Quotes cannot contain more quotes
                ,'()[]\;:,<>@example.com' // Invalid characters in local part
             // Different domain parts
                ,'test@.' // Domain labels must be at least 1 character
                ,'test@example' // Domain must contain at least 2 lables
                ,'test@[123.123.123.123' // Unpartnered square bracket
                ,'test@123.123.123.123]'
            );

        public function setUp() {
            $this->objValidator = new EmailAddressValidator;
        }

        public function tearDown() {
            unset($this->objValidator);
        }

        // Test the valid addresses.
        public function testValidAddresses() {
            foreach ($this->arrValidAddresses as $strAddress) {
                $this->assertEquals(true, $this->objValidator->check_email_address($strAddress));
            }
        }

        // Test the invalid addresses.
        public function testInvalidAddresses() {
            foreach ($this->arrInvalidAddresses as $strAddress) {
                $this->assertEquals(false, $this->objValidator->check_email_address($strAddress));
            }
        }
    }

?>