<?php

declare(strict_types = 1);

namespace Mesour\EnumTests\Enums;

use Mesour\Enum\Enum;

/**
 * @author Matouš Němec <mesour.com>
 */
class TestEnum extends Enum
{

	const FIRST = ['first', true];
	const SECOND = ['second', false];
	const THIRD = 'third';

	const FOURTH_IGNORED = 'fourth-ignored';

	/** @var bool */
	private $valid;

	protected function __construct(string $value, bool $valid = false)
	{
		parent::__construct($value);
		$this->valid = $valid;
	}

	public function isValid(): bool
	{
		return $this->valid;
	}

	protected static function getIgnoredConstantNames(): array
	{
		return ['FOURTH_IGNORED'];
	}

}
