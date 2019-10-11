<?php

declare(strict_types = 1);

namespace Mesour\Enum;

use Consistence\Reflection\ClassReflection;
use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

/**
 * @author github.com/consistence/consistence
 * @author Matouš Němec <mesour.com>
 */
abstract class Enum
{

	/** @var mixed */
	private $value;

	/** @var Enum[] indexed by enum and value */
	private static $instances = [];

	/** @var mixed[] format: enum name (string) => cached values (const name (string) => value (mixed)) */
	private static $availableValues;

	protected function __construct($value)
	{
		self::checkValue($value);
		$this->value = $value;
	}

	/**
	 * @param mixed $arguments
	 * @return static
	 */
	public static function get($arguments): self
	{
		$index = sprintf('%s::%s', get_called_class(), self::getValueIndex($arguments));
		if (!isset(self::$instances[$index])) {
			if (is_array($arguments)) {
				self::$instances[$index] = new static(...$arguments);
			} else {
				self::$instances[$index] = new static($arguments);
			}
		}
		return self::$instances[$index];
	}

	/**
	 * @param mixed $value
	 */
	public static function checkValue($value)
	{
		if (!static::isValidValue($value)) {
			throw new InvalidEnumValueException($value, static::class);
		}
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public static function isValidValue($value): bool
	{
		return ArrayType::containsValue(static::getAvailableValues(), $value);
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	public static function getAvailableValues()
	{
		$index = get_called_class();
		if (!isset(self::$availableValues[$index])) {
			$availableValues = self::getEnumConstants();
			static::checkAvailableValues($availableValues);
			self::$availableValues[$index] = static::getValuesFromArrays($availableValues);
		}

		return self::$availableValues[$index];
	}

	/**
	 * @param mixed[] $availableValues
	 * @return array
	 */
	private static function getValuesFromArrays(array $availableValues): array
	{
		$out = [];
		foreach ($availableValues as $value) {
			$out[] = self::normalizeValue($value);
		}
		return $out;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function equals(self $that): bool
	{
		$this->checkSameEnum($that);

		return $this === $that;
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public function equalsValue($value): bool
	{
		return $this->getValue() === self::normalizeValue($value);
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private static function getValueIndex($value): string
	{
		$value = self::normalizeValue($value);
		$type = Type::getType($value);
		return $value . sprintf('[%s]', $type);
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	private static function normalizeValue($value)
	{
		if (is_array($value)) {
			if (!isset($value[0])) {
				throw new InvalidStateException('First element of array value must be set.');
			}
			return $value[0];
		}
		return $value;
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	private static function getEnumConstants()
	{
		$classReflection = new \ReflectionClass(get_called_class());
		$constants = ClassReflection::getDeclaredConstants($classReflection);

		$declaredConstants = [];
		foreach ($constants as $constant) {
			$declaredConstants[$constant->getName()] = $constant->getValue();
		}
		ArrayType::removeKeys($declaredConstants, static::getIgnoredConstantNames());

		return $declaredConstants;
	}

	/**
	 * @param mixed[] $availableValues
	 */
	protected static function checkAvailableValues(array $availableValues)
	{
		$index = [];
		foreach ($availableValues as $value) {
			Type::checkType($value, 'int|string|float|bool|null|array');
			$key = self::getValueIndex($value);
			if (isset($index[$key])) {
				throw new \Consistence\Enum\DuplicateValueSpecifiedException($value, static::class);
			}
			$index[$key] = true;
		}
	}

	/**
	 * @return string[] names of constants which should not be used as valid values of this enum
	 */
	protected static function getIgnoredConstantNames()
	{
		return [];
	}

	protected function checkSameEnum(self $that)
	{
		if (get_class($this) !== get_class($that)) {
			throw new InvalidStateException(sprintf(
				'Operation supported only for enum of same class: %s given, %s expected',
				get_class($that),
				get_class($this)
			));
		}
	}

}
