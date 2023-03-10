<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Code\Test\Unit\Validator;

use Magento\SomeModule\Model\NamedArguments\NamedParametersTest;
use Magento\SomeModule\Model\NamedArguments\MixedParametersTest;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Code\Validator\ConstructorIntegrity;
use Magento\Framework\Exception\ValidatorException;

require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Three/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Two/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/One/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Four/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Five/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Six/Test.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/NamedArguments/NamedParametersTest.php';
require_once __DIR__ . '/../_files/app/code/Magento/SomeModule/Model/NamedArguments/MixedParametersTest.php';
require_once __DIR__ . '/_files/ClassesForConstructorIntegrity.php';
class ConstructorIntegrityTest extends TestCase
{
    /**
     * @var ConstructorIntegrity
     */
    protected $_model;

    protected function setUp(): void
    {
        $this->_model = new ConstructorIntegrity();
    }

    public function testValidateIfParentClassExist()
    {
        $this->assertTrue($this->_model->validate(\Magento\SomeModule\Model\One\Test::class));
    }

    public function testValidateIfClassHasParentConstructCall()
    {
        $this->assertTrue($this->_model->validate(\Magento\SomeModule\Model\Two\Test::class));
    }

    public function testValidateIfClassHasParentConstructCallWithNamedArguments()
    {
        $this->assertTrue($this->_model->validate(NamedParametersTest::class));
    }

    public function testValidateIfClassHasParentConstructCallWithMixedArguments()
    {
        $this->assertTrue($this->_model->validate(MixedParametersTest::class));
    }

    public function testValidateIfClassHasArgumentsQtyEqualToParentClass()
    {
        $this->assertTrue($this->_model->validate(\Magento\SomeModule\Model\Three\Test::class));
    }

    public function testValidateIfClassHasExtraArgumentInTheParentConstructor()
    {
        $this->_model->validate(\Magento\SomeModule\Model\Four\Test::class);
    }

    public function testValidateIfClassHasMissingRequiredArguments()
    {
        $fileName = realpath(__DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Five/Test.php');
        $fileName = str_replace('\\', '/', $fileName);
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(
            'Missed required argument factory in parent::__construct call. File: ' . $fileName
        );
        $this->_model->validate(\Magento\SomeModule\Model\Five\Test::class);
    }

    public function testValidateIfClassHasIncompatibleArguments()
    {
        $fileName = realpath(__DIR__ . '/../_files/app/code/Magento/SomeModule/Model/Six/Test.php');
        $fileName = str_replace('\\', '/', $fileName);
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(
            'Incompatible argument type: Required type: \Magento\SomeModule\Model\Proxy. ' .
            'Actual type: \Magento\SomeModule\Model\ElementFactory; File: ' .
            PHP_EOL .
            $fileName
        );
        $this->_model->validate(\Magento\SomeModule\Model\Six\Test::class);
    }

    public function testValidateWrongOrderForParentArguments()
    {
        $fileName = realpath(__DIR__) . '/_files/ClassesForConstructorIntegrity.php';
        $fileName = str_replace('\\', '/', $fileName);
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(
            'Incompatible argument type: Required type: \Context. ' .
            'Actual type: \ClassA; File: ' .
            PHP_EOL .
            $fileName
        );
        $this->_model->validate('ClassArgumentWrongOrderForParentArguments');
    }

    public function testValidateWrongOptionalParamsType()
    {
        $fileName = realpath(__DIR__) . '/_files/ClassesForConstructorIntegrity.php';
        $fileName = str_replace('\\', '/', $fileName);
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(
            'Incompatible argument type: Required type: array. ' . 'Actual type: \ClassB; File: ' . PHP_EOL . $fileName
        );
        $this->_model->validate('ClassArgumentWithWrongParentArgumentsType');
    }
}
