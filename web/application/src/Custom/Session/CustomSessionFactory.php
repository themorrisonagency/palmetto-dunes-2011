<?php

namespace Application\Src\Custom\Session;

use \Concrete\Core\Application\Application;
use \Concrete\Core\Http\Request;
use \Illuminate\Config\Repository;
use \Concrete\Core\Session\Storage\Handler\NativeFileSessionHandler;
use \Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use \Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

/**
 * Class SessionFactory
 * Base concrete5 session factory
 * @package Concrete\Core\Session
 */
 //we have to completely replace the sessionfactory class because the getsessionhandler function we want to override is private rather than protected.
class CustomSessionFactory implements \Concrete\Core\Session\SessionFactoryInterface 
{

    /** @var \Concrete\Core\Application\Application */
    private $app;

    /** @var \Concrete\Core\Http\Request */
    private $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Create a new symfony session object
     * This method MUST NOT start the session
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function createSession()
    {
        $config = $this->app['config'];
        $storage = $this->getSessionStorage($config);

        $session = new SymfonySession($storage);
        $session->setName($config->get('concrete.session.name'));

        /**
         * @todo Move this to somewhere else
         */
        $this->request->setSession($session);

        return $session;
    }

    /**
     * @param \Illuminate\Config\Repository $config
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    protected function getSessionStorage(Repository $config)
    {
        $app = $this->app;

        if ($app->isRunThroughCommandLineInterface()) {
            $storage = new MockArraySessionStorage();
        } else {
            $handler = $this->getSessionHandler($config);
            $storage = new NativeSessionStorage(array(), $handler);

            // Initialize the storage with some options
            $options = $config->get('concrete.session.cookie');
            if ($options['cookie_path'] === false) {
                $options['cookie_path'] = $app['app_relative_path'] . '/';
            }

            $lifetime = $config->get('concrete.session.max_lifetime');
            $options['gc_max_lifetime'] = $lifetime;
            $storage->setOptions($options);
        }

        return $storage;
    }
    
    /**
     * @param \Illuminate\Config\Repository $config
     * @return \SessionHandlerInterface
     */
    protected function getSessionHandler(Repository $config)
    {
    	switch($config->get('concrete.session.handler'))
    	{
        case 'database':
        	$db = $this->app['database']->connection();
            $handler = new PdoSessionHandler($db->getWrappedConnection(), array(
                'db_table' => 'Sessions',
                'db_id_col' => 'sessionID',
                'db_data_col' => 'sessionValue',
                'db_time_col' => 'sessionTime'
            ));
            break;
        case 'symphonysessionhandler':
        	//$savePath = $config->get('concrete.session.save_path') ?: null;
            $handler = new \Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler();
            break;
        case 'phpnative':
        	$handler=null;
        	break;
        default:
            $savePath = $config->get('concrete.session.save_path') ?: null;
            $handler = new \Concrete\Core\Session\Storage\Handler\NativeFileSessionHandler($savePath);
        }
        return $handler;
    }

}
