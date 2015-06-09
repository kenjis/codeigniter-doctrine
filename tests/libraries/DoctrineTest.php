<?php

require APPPATH . '/libraries/Doctrine.php';

class DoctrineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->obj = new Doctrine();
    }

    public function test_convertDbConfig_sqlite_pdo_1()
    {
        $db['default'] = [
            'dsn'      => '',
            'hostname' => 'sqlite:' . APPPATH . 'ci_test.sqlite',
            'username' => '',
            'password' => '',
            'database' => '',
            'dbdriver' => 'pdo',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => TRUE,
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt'  => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        ];

        $actual = $this->obj->convertDbConfig($db['default']);
        $expected = [
            'driver'   => 'pdo_sqlite',
            'user'     => '',
            'password' => '',
            'path'     => 'vendor/codeigniter/framework/application/ci_test.sqlite',
        ];
        $this->assertEquals($expected, $actual);
    }

    public function test_convertDbConfig_sqlite_pdo_2()
    {
        $db['default'] = [
            'dsn' => 'sqlite:' . APPPATH . 'ci_test.sqlite',
            'hostname' => 'localhost',
            'username' => 'sqlite',
            'password' => 'sqlite',
            'database' => 'sqlite',
            'dbdriver' => 'pdo',
            'subdriver' => 'sqlite'
        ];

        $actual = $this->obj->convertDbConfig($db['default']);
        $expected = [
            'driver'   => 'pdo_sqlite',
            'user'     => 'sqlite',
            'password' => 'sqlite',
            'path'     => 'vendor/codeigniter/framework/application/ci_test.sqlite',
        ];
        $this->assertEquals($expected, $actual);
    }

    public function test_convertDbConfig_mysqli()
    {
        $db['default'] = [
            'dsn'      => '',
            'hostname' => 'localhost',
            'username' => 'username',
            'password' => 'password',
            'database' => 'codeigniter',
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => TRUE,
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        ];

        $actual = $this->obj->convertDbConfig($db['default']);
        $expected = [
            'driver'   => 'mysqli',
            'user'     => 'username',
            'password' => 'password',
            'host'     => 'localhost',
            'dbname'   => 'codeigniter',
            'charset'  => 'utf8'
        ];
        $this->assertEquals($expected, $actual);
    }

    public function test_convertDbConfig_pdo_mysql()
    {
        $db['default'] = [
            'dsn' => 'mysql:host=localhost;dbname=ci_test',
            'hostname' => 'localhost',
            'username' => 'travis',
            'password' => 'password',
            'database' => 'ci_test',
            'char_set' => 'utf8',
            'dbdriver' => 'pdo',
            'subdriver' => 'mysql'
        ];

        $actual = $this->obj->convertDbConfig($db['default']);
        $expected = [
            'driver'   => 'pdo_mysql',
            'user'     => 'travis',
            'password' => 'password',
            'host'     => 'localhost',
            'dbname'   => 'ci_test',
            'charset'  => 'utf8'
        ];
        $this->assertEquals($expected, $actual);
    }
}
