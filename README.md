KnpLabs - PHPSPEC WellDone Extension
================

[![Build Status](https://travis-ci.org/KnpLabs/PhpSpecWellDoneExtension.png)](https://travis-ci.org/KnpLabs/PhpSpecWellDoneExtension)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/KnpLabs/PhpSpecWellDoneExtension/badges/quality-score.png?s=89495d6fc7e03f09e220741146cb0b3efd05fec6)](https://scrutinizer-ci.com/g/KnpLabs/PhpSpecWellDoneExtension/)

**Installation**

```bash
php composer.phar require knplabs/phpspec-welldone-extension dev-master
```

**Configuration**

```yml
#phpspec.yml
extensions:
    - Knp\PhpSpec\WellDone\Extension
```

**Usage**

```bash
./bin/phpspec status
```

**Add exclusion (via phpspec.yml)**

```yml
#phpspec.yml
knp.welldone.exclusion:
    - "*Controller"
    - "App\Entity\*"

extensions:
    - Knp\PhpSpec\WellDone\Extension
```

**Add exclusion (via command)**

```bash
./bin/phpspec status -e "*Controller, App\Entity\*"
```

With command, yml parameter will be overwrite.
