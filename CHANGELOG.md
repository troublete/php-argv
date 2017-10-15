# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2017-10-15
### Added

* `getCommand` as short handle for retrieving a command, if set, off clean arguments.

* benchmarking tests

### Changed

* `getFlags` to retrieve values following up on a flag as flag values

* `getFlags` returned anonymous class method `add` to consider a second argument which is treated as flag value,
defaults to `true`

* API docs

## [1.1.0] - 2017-10-02
### Added

* `__get` function to `getFlags` callback to eliminate required `isset` for flag check

* added missing PHP 7 typehinting

* fixed testing

## [1.0.0] - 2017-09-28
### Added

* `cleanArguments`, `reduceFlagName`, `isCommandCall`, `isFlag`, `isFlagAlias`, `getFlags`, `getValues` functions for basic handling of input arguments

* added testing for functions

 