<?php 
/**
 * Database class file
 */
class Database
{
  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $db_name = "pdo-blog";

  private $dbh;
  private $error;
  private $stmt;

  public function __construct()
  {
    // Set DSN
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
    
    // Set options
    $options = [
      PDO::ATTR_PERSISTENT  => true,
      PDO::ATTR_ERRMODE     => PDO::ERRMODE_EXCEPTION,
    ];

    // Create new PDO instance
    try {
      $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
    }
  }

  public function query($query)
  {
    $this->stmt = $this->dbh->prepare($query);
  }

  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
          break;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }

  public function execute()
  {
    return $this->stmt->execute();
  }

  public function lastInsertId()
  {
    $this->dbh->lastInsertId();
  }

  public function resultset()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
