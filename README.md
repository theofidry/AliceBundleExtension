AliceBundleExtension
======================

A [Behat extension](http://behat.org) to load [HautelookAliceBundle](https://github.com/hautelook/AliceBundle) fixtures.

[![Package version](http://img.shields.io/packagist/vpre/theofidry/alice-bundle-extension.svg?style=flat-square)](https://packagist.org/packages/theofidry/alice-fixtures-extension)
[![Build Status](https://img.shields.io/travis/theofidry/AliceBundleExtension.svg?branch=master&style=flat-square)](https://travis-ci.org/theofidry/AliceBundleExtension?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/3a633c53-a83d-47d4-aeb5-d3675aa4853d.svg?style=flat-square)](https://insight.sensiolabs.com/projects/3a633c53-a83d-47d4-aeb5-d3675aa4853d)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/theofidry/AliceFixturesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/theofidry/AliceBundleExtension/?branch=master)


## Installation

You can use [Composer](https://getcomposer.org/) to install the bundle to your project:

```bash
composer require theofidry/alice-bundle-extension
```

Then, in your behat config file `behat.yml`, register the extension and declare the context:

```yaml
# behat.yml
default:
    suites:
        default:
            contexts:
                - Fidry\AliceBundleExtension\Context\Doctrine\AliceORMContext

                # or if you want to set the base path only for this context:
                - Fidry\AliceBundleExtension\Context\Doctrine\AliceORMContext:
                    basePath: %paths.base%/tests/Features/fixtures/ORM (default value)
    # ...
    extensions:
        Fidry\AliceBundleExtension\Extension:
            fixtures_base_path: ~ # default to %paths.base%/features/fixtures
```

You have three contexts available:

* `Fidry\AliceBundleExtension\Context\Doctrine\AliceODMContext`
* `Fidry\AliceBundleExtension\Context\Doctrine\AliceORMContext`
* `Fidry\AliceBundleExtension\Context\Doctrine\AlicePHPCRContext`

With the default fixtures basePath respectively at:

* `%paths.base%/tests/Features/fixtures/ODM`
* `%paths.base%/tests/Features/fixtures/ORM`
* `%paths.base%/tests/Features/fixtures/PHPCR`

## Basic usage

Assuming you have the same configuration as the [Installation section](#Installation), you can create the following
fixture file:

```yaml
# features/fixtures/ORM/dummy.yml

AppBundle\Entity\Dummy:
    dummy_{1..10}:
        name: <name()>
```

Then simply load your fixtures with the following step:

```gherkin
Given the fixtures file "dummy.yml" is loaded
Given the fixtures file "dummy.yml" is loaded with the persister "doctrine.orm.entity_manager"
```

## Steps

For each context, you have the following steps available:

```gherkin
@Given the database is empty
@Then I empty the database

@Given the fixtures "fixturesFile" are loaded
@Given the fixtures file "fixturesFile" is loaded
@Given the fixtures "fixturesFile" are loaded with the persister "persister_service_id"
@Given the fixtures file "fixturesFile" is loaded with the persister "persister_service_id"
```

Loading a file can be done in three ways:

| Type of path | fixtures file path | computed fixtures file path |
|--------------|--------------------|-----------------------------|
| Relative path | `"dummy.yml"` | `contextBasePath/dummy.yml`, ex: `%paths.base%/tests/Features/fixtures/ORM/dummy.yml` |
| @Bundle path | `"@AppBundle/DataFixtures/ORM/dummy.yml"` | `src/AppBundle/DataFixtures/ORM/dummy.yml` (example)
| Absolute path | `/dummy.yml` | unchanged |


## Credits

This library is developed by [Théo FIDRY](https://github.com/theofidry).


## License

[![license](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
