<?php
require_once __DIR__ . '/../vendor/autoload.php';

use function Argv\{cleanArguments, getFlags};

$cleanedArguments = cleanArguments($argv);
$flags = getFlags($cleanedArguments);

if ($flags->rainbow) {
	echo 'rainbow 🌈';
}