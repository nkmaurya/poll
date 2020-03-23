<?php

/**
 * AutoLoader
 */
class AutoLoader
{    
    /**
     * appRootDir
     *
     * @var mixed
     */
    private $appRootDir;
    
    /**
     * map
     *
     * @var mixed
     */
    private $map;

    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->appRootDir = dirname(__FILE__);

        $this->map =  array(
            'App\\Controller\\' => $this->appRootDir . '/src/Controller/',
            'App\\Model\\' => $this->appRootDir . '/src/Model/',
            'App\\Helper\\' => $this->appRootDir . '/src/Helpers/',
            'App\\Database\\' => $this->appRootDir . '/database/'
            );

        spl_autoload_register(array($this, 'loader'));
    }

    /**
     * loader
     *
     * @param  mixed $className
     * @return void
     */
    private function loader($className)
    {
        $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
        foreach ($this->map as $namespace => $path) {
            $path = $path . basename($className);
            if (file_exists($path . '.php')) {
                include_once $path . '.php';
            }
        }
    }
}

$autoloader = new AutoLoader();
