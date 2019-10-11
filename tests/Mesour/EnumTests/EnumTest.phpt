<?php

declare(strict_types = 1);

/**
 * Test: Mesour\Enum\Enum
 *
 * @testCase Mesour\EnumTests\EnumTest
 */

namespace Mesour\EnumTests;

use Mesour\Enum\InvalidEnumValueException;
use Mesour\EnumTests\Enums\TestEnum;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

require_once __DIR__ . '/Enums/TestEnum.php';

/**
 * @author Matouš Němec <mesour.com>
 */
class EnumTest extends TestCase
{

	public function testAvailableValues(): void
	{
		Assert::same(['first', 'second', 'third'], TestEnum::getAvailableValues());
	}

	public function testNotExistedValue(): void
	{
		Assert::exception(function () {
			TestEnum::checkValue('not-existed');
		}, InvalidEnumValueException::class);
	}

	public function testIsValidValue(): void
	{
		Assert::true(TestEnum::isValidValue('first'));
	}

	public function testIsNotValidValue(): void
	{
		Assert::false(TestEnum::isValidValue('not-existed'));
	}

	public function testGetValues(): void
	{
		$enum = TestEnum::get(TestEnum::FIRST);
		Assert::same('first', $enum->getValue());
		Assert::true($enum->isValid());
	}

	public function testStringValue(): void
	{
		$enum = TestEnum::get(TestEnum::THIRD);
		Assert::same('third', $enum->getValue());
		Assert::false($enum->isValid());
	}

	public function testEqualsValue(): void
	{
		$enum = TestEnum::get(TestEnum::FIRST);
		Assert::true($enum->equalsValue(TestEnum::FIRST));
	}

	public function testEquals(): void
	{
		$enum = TestEnum::get(TestEnum::SECOND);
		$enum2 = TestEnum::get(TestEnum::SECOND);
		Assert::true($enum->equals($enum2));
	}

	public function testEqualsValueString(): void
	{
		$enum = TestEnum::get(TestEnum::THIRD);
		Assert::true($enum->equalsValue(TestEnum::THIRD));
	}

	public function testEqualsString(): void
	{
		$enum = TestEnum::get(TestEnum::THIRD);
		$enum2 = TestEnum::get(TestEnum::THIRD);
		Assert::true($enum->equals($enum2));
	}

	public function testMoreEquals(): void
	{
		$first = TestEnum::get(TestEnum::FIRST);
		$first2 = TestEnum::get(TestEnum::FIRST);
		$third = TestEnum::get(TestEnum::THIRD);

		// by instance
		Assert::true($first === $first);
		Assert::true($first === $first2);
		Assert::false($first === $third);

		// with method
		Assert::true($first->equals($first));
		Assert::true($first->equals($first2));
		Assert::false($first->equals($third));

		// by value
		Assert::true($first->equalsValue(TestEnum::FIRST));
		Assert::true($first->equalsValue('first'));
		Assert::false($first->equalsValue(TestEnum::THIRD));
	}

}

(new EnumTest())->run();
