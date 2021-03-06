# vvv-base Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## next_release - date

Added
Changed
Deprecated
Removed
Fixed
Security

## v0.3.1 - 2017-08-07

Added
* Support for [`.editorconfig`](http://editorconfig.org) (Props @salcode)
* More thorough unit testing
* Alternate versions of the `db_prefix` option: `prefix`, `dbprefix`.

Fixed
* Bug where `activate: false` still caused plugins to be activated ([#20](https://github.com/JPry/vvv-base/issues/20) - props @codepuncher)
* Documentation for `db_prefix` option did not match actual behavior. The behavior itself was fixed.

## v0.3.0 - 2017-05-26

Added
* `htdocs` option to allow for a Git repo of the entire `htdocs/` directory
* `themes` option to allow for automated installation of themes
* `wp` option to allow for skipping WordPress installation entirely
* Unit Tests
* [Contribution guidelines](https://github.com/JPry/vvv-base/blob/develop/.github/CONTRIBUTING.md)
* [Circle CI](https://circleci.com/gh/JPry/vvv-base) integration for testing

Changed
* `wp-content` option is deprecated in favor of `wp_content`
* Added dates to the changelog

## v0.2.1 - 2017-05-09

* Changelog fixes.

## v0.2.0 - 2017-05-09

* Create changelog file.
* Use separate repo for `DefaultsArray` class, via Composer.
* Fix a bug with the hosts logic (#1)

## v0.1.0 - 2017-05-08

* Initial working version
