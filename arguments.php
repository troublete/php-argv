<?php
namespace Argv;

/**
 * Function to clean up provided CLI arguments (cut out the script as argument)
 * @param  array  $arguments
 * @return array
 */
function cleanArguments(array $arguments): array {
	$relevantArguments = array_slice($arguments, 1);
	return $relevantArguments;
}

/**
 * Function to normalize a flag name
 * @param  string $argument
 * @return string
 */
function reduceFlagName(string $argument): string {
	return str_replace('-', '', $argument);
}

/**
 * Function to check if the script call references on a command
 * @param  array   $arguments
 * @return boolean
 */
function isCommandCall(array $arguments): bool {
	$argument = current($arguments);
	return !isFlag($argument) && !isFlagAlias($argument);
}

/**
 * Function to check if a provided argument is a flag
 * @param  string  $argument
 * @return boolean
 */
function isFlag(string $argument): bool {
	return substr($argument, 0, 2) === '--';
}

/**
 * Function to check if a provided argument is a flag alias
 * @param  string  $argument
 * @return boolean
 */
function isFlagAlias(string $argument): bool {
	return substr($argument, 0, 1) === '-' && !isFlag($argument);
}

/**
 * Function to retrieve the flags provided by the script call
 * @param  array  $arguments
 * @param  array  $aliases
 * @return class@anonymous
 */
function getFlags(array $arguments, array $aliases = []) {
	$flagValues = getValues($arguments);
	$flags = new class {
		public function add(string $flagName, $value = true) {
			$this->{$flagName} = $value;
			return $this;
		}

		public function __get(string $name): bool {
			return isset($this->{$name}) && $this->{$name};
		}
	};

	foreach ($arguments as $index => $argument) {
		$flagValue = $flagValues->get(++$index) ?? true;

		if (isFlag($argument)) {
			$flags->add(reduceFlagName($argument), $flagValue);
		} else if (
			isFlagAlias($argument)
			&& isset($aliases[reduceFlagName($argument)])
		) {
			$flags->add($aliases[reduceFlagName($argument)], $flagValue);
		}
	}

	return $flags;
}

/**
 * Function to retrieve the values provided by the script call
 * @param  array  $arguments
 * @return class@anonymous
 */
function getValues(array $arguments) {
	$values = new class {
		private $values = [];

		public function add(string $value, int $originalIndex) {
			$this->values[$originalIndex] = $value;
		}

		public function get(int $index) {
			return $this->values[$index] ?? null;
		}

		public function all(): array
		{
			return $this->values;
		}

		public function first(): string
		{
			return current($this->values);
		}
	};

	foreach ($arguments as $index => $argument) {
		if (!isFlag($argument) && !isFlagAlias($argument)) {
			$values->add($argument, $index);			
		}
	}
	
	return $values;	
}

/**
 * Alias function to retrieve the command name of cleaned arguments
 * @param  array  $arguments
 * @return string
 */
function getCommand(array $arguments): string {
	return getValues($arguments)->first();
}
