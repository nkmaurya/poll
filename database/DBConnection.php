<?php
namespace App\Database;

/**
 * DBConnection
 */
class DBConnection
{
    /**
     * Database client instance.
     */
    private $connection;

    /**
    * Single instance
    */
    private static $instance;

    /**
     * Database host.
     */
    private $host = DATABASE['host'];

    /**
     * Database port.
     */
    private $port = DATABASE['port'];

    /**
     * Database username.
     */
    private $username = DATABASE['username'];

    /**
     * Database password
     */
    private $password = DATABASE['password'];
    
    /**
     * Database name.
     */
    private $dbname = DATABASE['dbname'];
        
    /**
     * __construct
     *
     * @return void
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:dbname=$this->dbname;host=$this->host;port=$this->port";
            $this->connection = new \PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Failed to connect to DB: ". $e->getMessage());
        }
    }

    
    /**
     * getInstance
     *
     * @return void
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new DBConnection;
        }

        return static::$instance;
    }
    
    /**
     * getConnection
     *
     * @return void
     */
    public function getConnection()
    {
        return $this->connection;
    }

    
    /**
     * __clone
     *
     * @return void
     */
    final public function __clone()
    {
        throw new Exception('Cloning not allowed');
    }
}
