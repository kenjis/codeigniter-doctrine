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
    /**
     * @var EntityManager|null
     */
    public $em = null;

    /**
     * @var int debug level
     */
    public $debug = 0;

    public function __construct()
    {
        // load database configuration from CodeIgniter
        if (! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
            && ! file_exists($file_path = APPPATH.'config/database.php')) {
            throw new Exception('The configuration file database.php does not exist.');
        }
        require $file_path;

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
        if ($this->debug > 0) {
            $logger = new EchoSQLLogger;
            $config->setSQLLogger($logger);
        }

        $config->setAutoGenerateProxyClasses(true);

        // Database connection information
        $connectionOptions = $this->convertDbConfig($db['default']);

        // Create EntityManager
        $this->em = EntityManager::create($connectionOptions, $config);
    }

    /**
     * Convert CodeIgniter database config array to Doctrine's
     *
     * See http://www.codeigniter.com/user_guide/database/configuration.html
     * See http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
     *
     * @param array $db
     * @return array
     * @throws Exception
     */
    public function convertDbConfig($db)
    {
        $connectionOptions = [];

        if ($db['dbdriver'] === 'pdo') {
            return $this->convertDbConfigPdo($db);
        } elseif ($db['dbdriver'] === 'mysqli') {
            $connectionOptions = [
                'driver'   => $db['dbdriver'],
                'user'     => $db['username'],
                'password' => $db['password'],
                'host'     => $db['hostname'],
                'dbname'   => $db['database'],
                'charset'  => $db['char_set'],
            ];
        } else {
            throw new Exception('Your Database Configuration is not confirmed by CodeIgniter Doctrine');
        }

        return $connectionOptions;
    }

    protected function convertDbConfigPdo($db)
    {
        $connectionOptions = [];

        if (substr($db['hostname'], 0, 7) === 'sqlite:') {
            $connectionOptions = [
                'driver'   => 'pdo_sqlite',
                'user'     => $db['username'],
                'password' => $db['password'],
                'path'     => preg_replace('/\Asqlite:/', '', $db['hostname']),
            ];
        } elseif (substr($db['dsn'], 0, 7) === 'sqlite:') {
            $connectionOptions = [
                'driver'   => 'pdo_sqlite',
                'user'     => $db['username'],
                'password' => $db['password'],
                'path'     => preg_replace('/\Asqlite:/', '', $db['dsn']),
            ];
        } elseif (substr($db['dsn'], 0, 6) === 'mysql:') {
            $connectionOptions = [
                'driver'   => 'pdo_mysql',
                'user'     => $db['username'],
                'password' => $db['password'],
                'host'     => $db['hostname'],
                'dbname'   => $db['database'],
                'charset'  => $db['char_set'],
            ];
        } else {
            throw new Exception('Your Database Configuration is not confirmed by CodeIgniter Doctrine');
        }

        return $connectionOptions;
    }
}
