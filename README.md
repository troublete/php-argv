# Argv
> Small functional library to interpret CLI arguments ✨

[![Build Status](https://travis-ci.org/troublete/argv.svg?branch=master)](https://travis-ci.org/troublete/argv)

## Install

```bash
$ composer require troublete/argv
```

## Usage

```php
<?php
require_once 'path/to/autoload.php';

use function Argv\{cleanArguments, getFlags};

$cleanedArguments = cleanArguments($argv);
$flags = getFlags($cleanedArguments);

if ($flags->rainbow) {
	echo 'rainbow 🌈';
}
```

On CLI execution like `$ php file-with-script.php --rainbow` the CLI will output `rainbows 🌈`. (see `examples` for a working example)

## API

### `cleanArguments(array $argv): string`

Will remove the script from the input arguments.

### `reduceFlagName(string $flagName): string`

Will remove `-` and `--` from flags and flag aliases.

### `isCommandCall(array $arguments): bool`

Will determine by checking the first argument if a command is called or only a flag call is occuring.

### `isFlag(string $argument): bool`

Will determine if argument is a flag by checking if first two characters are `--`.

### `isFlagAlias(string $argument): bool`

Will determine if argument is a flag alias by checking if the first character is `-`.

### `getFlags(array $arguments, array $aliases = []): class@anonymous`

Will return an class with all flags (NOT aliases) as public properties, value set to true. If aliases are provided in the form like `['f' => 'flag']` the flag `flag` will be set on script call with only `-f`.

### `getValues(array $arguments): class@anonymous`

Will return all provided values encapsulated in a anonymous class (original index will be preserved). With calling `->all()` on the returned class an array with all values will be returned, where key is original index and value the value. With calling `->first()` the first value will be returned.

## License

GPL-2.0 Willi Eßer