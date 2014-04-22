<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

use Phalcon\Mvc\View\Engine\Volt;

try {

    /**
     * Read the configuration
     */
    $config = include(__DIR__ . "/../app/config/config.php");

    $loader = new \Phalcon\Loader();

    /**
     * We're a registering a set of directories taken from the configuration file
     */
    $loader->registerDirs(
            array(
                $config->application->controllersDir,
                $config->application->modelsDir
            )
    )->register();

    /**
     * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
     */
    $di = new \Phalcon\DI\FactoryDefault();

    /**
     * The URL component is used to generate all kind of urls in the application
     */
    $di->set('url', function() use ($config) {
                $url = new \Phalcon\Mvc\Url();
                $url->setBaseUri($config->application->baseUri);
                return $url;
            });

    /**
     * Setup the translator
     */
    $di->set('translate',function() use($di) {
      $dispatcher = $di->get('dispatcher');
      $language = $dispatcher->getParam("lang");

      $langDir = __DIR__.'/../app/lang/';
      if(file_exists("{$langDir}{$language}.php")) {
        require_once "{$langDir}{$language}.php";
      } else {
        require_once "{$langDir}en.php";
      }

      return new \Phalcon\translate\Adapter\NativeArray(array(
        "content" => $messages
      ));


    });

    /**
     * Setting up the view component
     */
    $di->set('view', function() use ($config) {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir($config->application->viewsDir);
                $view->registerEngines(array(
                    '.volt' => function($view, $di) use ($config) {
                        $volt = new Volt($view, $di);
                        $volt->setOptions(
                                array(
                                    'compiledPath' => __DIR__ . '/../cache/volt/',
                                    'compiledExtension' => '.php',
                                    'compiledSeparator' => '_',
                                    'compileAlways' => true
                                )
                        );
                        $volt->getCompiler()->addFunction('tr', function ($resolvedArgs, $exprArgs) use ($di) {
                          return sprintf('$this->translate->query(\'%s\')', $exprArgs[0]['expr']['value']);
                        });
                        return $volt;
                    }
                ));

                return $view;
            }, true);

    //Set up the flash service
    $di->set('flash', function() {
                $flash = new \Phalcon\Flash\Session(array(
                            'error' => 'alert-danger',
                            'success' => 'alert alert-success',
                            'notice' => 'alert alert-info',
                        ));
                return $flash;
            });

     /**
      * Setting router
      */
    $di->set('router', function() {
                require __DIR__ . '/../app/config/routes.php';
                return $router;
            });

    /**
     * Database connection is created based in the parameters defined in the configuration file
     */
    $di->set('db', function() use ($config) {
                return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                            "host" => $config->database->host,
                            "username" => $config->database->username,
                            "password" => $config->database->password,
                            "dbname" => $config->database->name
                        ));
            });

    /**
     * If the configuration specify the use of metadata adapter use it or use memory otherwise
     */
    $di->set('modelsMetadata', function() use ($config) {
                if (isset($config->models->metadata)) {
                    $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\' . $config->models->metadata->adapter;
                    return new $metadataAdapter();
                } else {
                    return new \Phalcon\Mvc\Model\Metadata\Memory();
                }
            });

    /**
     * Start the session the first time some component request the session service
     */
    $di->set('session', function() {
                $session = new \Phalcon\Session\Adapter\Files();
                $session->start();
                return $session;
            });

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();
} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}