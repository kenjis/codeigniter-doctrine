<?php
/**
 * Part of CodeIgniter Doctrine
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-doctrine
 */

/*
 * This code is based on http://doctrine-orm.readthedocs.org/en/latest/cookbook/integrating-with-codeigniter.html
 */

use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Logging\EchoSQLLogger;

class Doctrine
{
    public $em = null;

    public function __construct()
    {
        // load database configuration from CodeIgniter
        if (! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
            && ! file_exists($file_path = APPPATH.'config/database.php')) {
            throw new Exception('The configuration file database.php does not exist.');
        }
        require_once $file_path;

        $entitiesClassLoader = new ClassLoader('models', rtrim(APPPATH, "/"));
        $entitiesClassLoader->register();
        $proxiesClassLoader = new ClassLoader('Proxies', APPPATH . 'models/Proxies');
        $proxiesClassLoader->register();

        // Set up caches
        $config = new Configuration;
        $cache = new ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(array(APPPATH . 'models/Entities'));
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);

        $config->setQueryCacheImpl($cache);

        // Proxy configuration
        $config->setProxyDir(APPPATH . '/models/Proxies');
        $config->setProxyNamespace('Proxies');

        // Set up logger
        $logger = new EchoSQLLogger;
        $config->setSQLLogger($logger);

        $config->setAutoGenerateProxyClasses(true);

        // Database connection information
        if ($db['default']['dbdriver'] === 'pdo') {
            if (substr($db['default']['hostname'], 0, 7) === 'sqlite:') {
                $connectionOptions = array(
                    'driver'   => 'pdo_sqlite',
                    'user'     => $db['default']['username'],
                    'password' => $db['default']['password'],
                    'path'     => preg_replace('/\Asqlite:/', '', $db['default']['hostname']),
                );
            } else {
                throw new Exception('Your Database Configuration is not confirmed by CodeIgniter Doctrine');
            }
        } elseif ($db['default']['dbdriver'] === 'mysqli') {
            $connectionOptions = array(
                'driver'   => $db['default']['dbdriver'],
                'user'     => $db['default']['username'],
                'password' => $db['default']['password'],
                'host'     => $db['default']['hostname'],
                'dbname'   => $db['default']['database'],
                'charset'  => $db['default']['char_set'],
            );
        } else {
            throw new Exception('Your Database Configuration is not confirmed by CodeIgniter Doctrine');
        }

        // Create EntityManager
        $this->em = EntityManager::create($connectionOptions, $config);
    }
}
