# CodeIgniter Doctrine

This package provides simple Doctrine integration for [CodeIgniter](https://github.com/bcit-ci/CodeIgniter) 3.x.

## Folder Structure

```
codeigniter/
└── application/
    └── libraries/
        └── codeigniter-doctrine/
            └── libraries/
                └── Doctrine.php
```

## Requirements

* PHP 5.4.0 or later
* Composer

## Installation

Install this project with Composer:

~~~
$ cd /path/to/codeigniter/
$ composer require kenjis/codeigniter-doctrine:1.0.x@dev
~~~

## Usage

Load Doctrine library:

~~~php
$this->load->library('Doctrine');
~~~

Use the entity manager:

~~~php
$em = $this->doctrine->em;
~~~

Use `doctrine` command:

~~~
$ cd /path/to/codeigniter/
$ vendor/bin/doctrine
~~~

### Reference

* http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/

## Related Projects for CodeIgniter 3.x

* [CodeIgniter Composer Installer](https://github.com/kenjis/codeigniter-composer-installer)
* [Cli for CodeIgniter 3.0](https://github.com/kenjis/codeigniter-cli)
* [ci-phpunit-test](https://github.com/kenjis/ci-phpunit-test)
* [CodeIgniter Simple and Secure Twig](https://github.com/kenjis/codeigniter-ss-twig)
* [CodeIgniter Deployer](https://github.com/kenjis/codeigniter-deployer)
