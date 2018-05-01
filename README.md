# PHP FQCN Fixer

## Introduction

PHP FQCN Fixer is a simple standalone tool that ensures naming consistency between your php classes, interfaces
and traits and the file location and name.

It's especially usefull when performing heavy refactoring in your code base.

## Requirements

PHP needs to be a minimum version of PHP 7.0.0

## Installation

You can run these commands to easily access latest php-cs-fixer from anywhere on your system:

```
$ wget https://github.com/gquemener/PHP-FQCN-Fixer/releases/download/v0.0.1/php-fqcn-fixer.phar -O php-fqcn-fixer
```

## Usage

In your PHP project, your can run the following command to fix naming inconsistency of a file:

```
$ cat src/App/Model/Truck.php
<?php

namespace App;

class Motorbike
{
}
$ php-fqcn-fixer fix src/App/Model/Truck.php
Fixing naming inconsistencies into src/App/Model/Truck.php
  - src/App/Model/Truck.php fixed
$ cat src/App/Model/Truck.php
<?php

namespace App\Model;

class Truck
{
}
```

You may also run the executable against a directory to fix all the php files inside it:

```
$ php-fqcn-fixer fix src/App/Model
Fixing naming inconsistencies into src/App/Model
  - src/App/Model/Truck.php fixed
  - src/App/Model/Car.php fixed
  - src/App/Model/Vehicle.php fixed
```

Finally, a watch mode is also provided in order to run the command once and forget about naming consistency
```
$ php-fqcn-fixer fix -w src/App/Model
Fixing naming inconsistencies into src/App/Model
  - src/App/Model/Truck.php fixed
  - src/App/Model/Car.php fixed
  - src/App/Model/Vehicle.php fixed
Watching file modifications into src/App/Model...
```
