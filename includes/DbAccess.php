<?php
  class DbAccess {
    private $dbHost;
    private $username;
    private $password;
    private $port;
    private $dbName;
    private $conn;

    /**
     * Constructor for DbAccess
     * @param String $_dbHost   Database Host
     * @param String $_port     Port number
     * @param String $_username User's username
     * @param String $_password User's password
     * @param String $_dbName   Database Name
     */
    public function __construct($_dbHost, $_port, $_username, $_password, $_dbName) {
      $this->dbHost = $_dbHost;
      $this->port = $_port;
      $this->username = $_username;
      $this->password = $_password;
      $this->dbName = $_dbName;
    }
    
    /**
     * Connect to database
     * @return Object Connection object
     */
    public function connect() {
      if ($this->conn === null) {
        $options = array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false
        );
        $this->conn = new PDO("mysql:host=$this->dbHost:$this->port;dbname=$this->dbName", $this->username, $this->password, $options);
      }
      return $this->conn;
    }

    /**
     * Executes the query and returns the fetched rows
     * @param  Object $_query Prepared query to be executed
     * @param  String $params Parameters to be used in prepared query
     * @return Array          Array of rows fetched from database
     */
    public function query($_query, $params = null) {
      $db_connect = $this->connect();
      $_query->execute($params);
      return $_query->fetchAll();
    }
  }
?>
