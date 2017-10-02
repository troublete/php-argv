<?php
namespace Argv;

use function Argv\{cleanArguments, reduceFlagName, isCommandCall, isFlag, isFlagAlias, getFlags, getValues};

class argumentsTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected $arguments = [
		'index.php',
		'cmd',
		'--flag',
		'-f',
		'cmmd'
	];

	protected $cleaned = [
		'cmd',
		'--flag',
		'-f',
		'cmmd'
	];

	public function testCleanArguments()
	{
		$cleanedArguments = cleanArguments($this->arguments);
		$this->assertEquals($this->cleaned, $cleanedArguments);
	}

	public function testReduceFlagName()
	{
		$realFlag = reduceFlagName('--flag');
		$this->assertEquals('flag', $realFlag);

		$value = reduceFlagName('flag');
		$this->assertEquals('flag', $value);
	}

	public function testIsCommandCall()
	{
		$isCommandCall = isCommandCall($this->cleaned);
		$this->assertEquals(true, $isCommandCall);

		$isNotCommandCall = isCommandCall(['--flag']);
		$this->assertEquals(false, $isNotCommandCall);

		$isNotCommandCall = isCommandCall(['-f']);
		$this->assertEquals(false, $isNotCommandCall);
	}

	public function testIsFlag()
	{
		$isFlag = isFlag('--flag');
		$this->assertEquals(true, $isFlag);

		$isNotFlag = isFlag('-f');
		$this->assertEquals(false, $isNotFlag);

		$isNotFlag = isFlag('flag');
		$this->assertEquals(false, $isNotFlag);
	}

	public function testIsFlagAlias()
	{
		$isFlagAlias = isFlagAlias('-f');
		$this->assertEquals(true, $isFlagAlias);

		$isNotFlagAlias = isFlagAlias('--f');
		$this->assertEquals(false, $isNotFlagAlias);

		$isNotFlagAlias = isFlagAlias('flag');
		$this->assertEquals(false, $isNotFlagAlias);
	}

	public function testGetFlags()
	{
		$flags = getFlags($this->cleaned, ['f' => 'flag']);
		$this->assertEquals(true, $flags->flag);
		$this->assertFalse($flags->f);

		$flags = getFlags(['-f'], ['f' => 'flag']);
		$this->assertEquals(true, $flags->flag);
		$this->assertFalse($flags->f);

		$flags = getFlags(['-f']);
		$this->assertFalse($flags->flag);
		$this->assertFalse($flags->f);		
	}

	public function testGetValues()
	{
		$values = getValues($this->cleaned);

		$this->assertEquals([
			0 => 'cmd',
			3 => 'cmmd'
		], $values->all());

		$this->assertCount(2, $values->all());
		$this->assertEquals('cmd', $values->first());
	}
}