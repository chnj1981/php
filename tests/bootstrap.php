<?php declare(strict_types=1);


// -----------------------------------------------------------------------------
// Option 1: Use Composer
// -----------------------------------------------------------------------------
//
// If you are using the Composer package manager, you can use its class loader
// to run your tests. Composer will automatically load the project files plus
// any dependencies, so you don't have to configure the class paths manually.
//
// Uncomment the following line to include Composer's autoloading scripts. The
// path is relative to the project's root directory.


//require('vendor/autoload.php');


// ------------------------------------------------------------------------------
// Option 2: Manual registration of dependencies
// ------------------------------------------------------------------------------
//
// Nirvarnia Autoloader is a simple PSR-4 compatible class loader. You can use
// it to autoload this package and its dependencies when running tests, if you
// are not using Composer's autoloader.
//
// Usage:
//
//     $autoloader = new \Nirvarnia\Autoloader($base_dir);
//     $autoload->register($prefix, $directory);
//     $autoload->register($prefix, $directory);
//     $autoload->register($prefix, $directory);
//
// $base_dir is an optional base directory for all autoloadable resources. If
// you do not provide a base directory, it will be set to PHP's include path.
//
// The register() method adds a new autoloading rule. $prefix is a namespace
// prefix and $directory is a directory path - relative to $base_dir -
// containing the classes and interfaces for the given namespace prefix.
//
// Optionally, a single namespace prefix may have classes littered about more
// than one base directory. If so, provide an array of directory paths as the
// second parameter.
//
//     $autoload->register($prefix, [$directory, $directory, $directory]);
//
// For testing, you will need to register an autoloading rule for the package
// itself. The package files are in the ./src/ directory. You may also need to
// register autoloading rules for each of the package's dependencies, which you
// should install in the ./vendor/ directory.
//
// Example:
//
//     $base_dir = dirname(dirname(__FILE__));
//     $autoloader = new \Nirvarnia\Autoloader($base_dir);
//
//     // Package:
//     $autoloader->register('Nirvarnia\\Helper', '/src');
//
//     // Dependencies:
//     $autoloader->register('Carbon', '/vendor/nesbot/carbon/src/Carbon');
//     $autoloader->register('Monolog', '/vendor/monolog/monolog/src/Monolog');
//     $autoloader->register('Symfony\\Component\\Yaml', '/vendor/symfony/yaml');
//
// The Nirvarnia Autoloader library automatically registers itself as an SPL
// autoloader - there is no need to call spl_autoload_register().


require('tests/Autoloader.php');

$autoloader = new \Nirvarnia\Autoloader(dirname(dirname(__FILE__)));
$autoloader->register('Nirvarnia\\ApiView', '/src');
