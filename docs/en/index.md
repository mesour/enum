Enums with properties
=====================

Documentation

Basic usage
-----------

You can found documentation for basic usage here: [consistence/consistence docs](https://github.com/consistence/consistence/blob/master/docs/Enum/enums.md).

Implementation
--------------

You can easily define properties for enum values.

```php
<?php

class CardColor extends \Mesour\Enum\Enum
{

	public const BLACK = ['black', true];
	public const RED = ['red', false];

	/** @var bool */
	private $dashed;

	public function __construct(string $value, bool $dashed)
	{
		parent::__construct($value);
		$this->dashed = $dashed;
	}

	public function isDashed(): bool
	{
		return $this->dashed;
	}

}

$red = CardColor::get(CardColor::RED);
$red->getValue(); // 'red'
$red->isDashed(); // false

$black = CardColor::get(CardColor::BLACK);
$red->isDashed(); // true

$availableValues = CardColor::getAvailableValues(); // ['black', 'red']
```

Comparing
---------

Once you have an enum instance, you can compare them in multiple ways:

```php
<?php

$red = CardColor::get(CardColor::RED);
$red2 = CardColor::get(CardColor::RED);
$black = CardColor::get(CardColor::BLACK);

// by instance
$red === $red; // true
$red === $red2; // true
$red === $black; // false

// with method
$red->equals($red); // true
$red->equals($red2); // true
$red->equals($black); // false

// by value
$red->equalsValue(CardColor::RED); // true
$red->equalsValue('red'); // true
$red->equalsValue(CardColor::BLACK); // false
```

Default values
--------------

You can use default values in constructor and simplify code.

```php
<?php

class CardColor extends \Mesour\Enum\Enum
{

	public const BLACK = ['black', true];
	public const RED = ['red'];
	public const BLUE = 'blue'; // same as ['blue']

	/** @var bool */
	private $dashed;

	public function __construct(string $value, bool $dashed = false)
	{
		parent::__construct($value);
		$this->dashed = $dashed;
	}

	// ...
}

$blue = CardColor::get(CardColor::BLUE);
$blue->isDashed(); // false
```
