<?php
namespace Argv;

use function Argv\{cleanArguments, reduceFlagName, isCommandCall, isFlag, isFlagAlias, getFlags, getValues, getCommand};

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
		'dms',
		'-f',
		'cmmd'
	];

	protected $cleaned = [
		'cmd',
		'--flag',
		'dms',
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

	public function testGetFlagValues()
	{
		$flags = getFlags([
			'--flag',
			'flagValue',
			'--someOtherFlag'
		]);
		
		$this->assertEquals('flagValue', $flags->flag);
		$this->assertTrue($flags->someOtherFlag);
	}

	public function testGetFlags()
	{
		$flags = getFlags($this->cleaned, ['f' => 'flag']);
		$this->assertEquals('cmmd', $flags->flag);
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
			2 => 'dms',
			4 => 'cmmd'
		], $values->all());

		$this->assertCount(3, $values->all());
		$this->assertEquals('cmd', $values->first());
	}

	public function testCliArgumentCallsBenchmark()
	{
		$args = [
			'someCommand',
			'--flag',
			'someValue',
			'-f',
			'shortValue',
			'--new'
		];

		$isCommand = isCommandCall($args);
		$commandName = getCommand($args);
		$flags = getFlags($args);

		$this->assertTrue($isCommand);
		$this->assertTrue($flags->new);
		$this->assertFalse($flags->notSetFlag);
		$this->assertEquals('someCommand', $commandName);
		$this->assertEquals('someValue', $flags->flag);

		$flags = getFlags($args, ['f' => 'flag']);
		$this->assertEquals('shortValue', $flags->flag);

		$args = [
			'--flag',
			'someValue',
			'-f',
			'shortValue'
		];

		$isCommand = isCommandCall($args);
		$commandName = getCommand($args);
		$flags = getFlags($args);

		$this->assertFalse($isCommand);
		$this->assertEquals('someValue', $flags->flag);

		$flags = getFlags($args, ['f' => 'flag']);
		$this->assertEquals('shortValue', $flags->flag);
	}
}