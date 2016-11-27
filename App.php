<?php
namespace Lobby\App;

use \Lobby\App\fr_logsys\Fr\LS;

class fr_logsys extends \Lobby\App {

  public $set = false;
  public $info, $dbinfo = array();
  public $table, $dbh = null;

  public function init($page){
    $this->dbinfo = $this->data->getArray("credentials");
    if($this->data->getValue("credentials") != null && $this->connect($this->dbinfo)){
      $this->set = true;
    }
  }

  public function load(){
    $dbinfo = $this->data->getArray("credentials");
    $this->table = $dbinfo['db_table'];
    require_once $this->dir . "/src/inc/logsys.config.php";
  }

  public function setInfo(){
    $this->load();

    $number_of_users = $this->dbh->query("SELECT COUNT(1) FROM `". $this->table ."`");
    $number_of_users = $number_of_users !== FALSE ? $number_of_users->fetchColumn() : 0;

    $number_of_tokens = $this->dbh->query("SELECT COUNT(1) FROM `resetTokens`");
    $number_of_tokens = $number_of_tokens !== FALSE ? $number_of_tokens->fetchColumn() : 0;

    $this->info = array(
      "users" => $number_of_users,
      "verify_tokens" => $number_of_tokens
    );
  }

  public function connect($credentials){
    $config = array_merge(array(
      "db_name" => "",
      "db_host" => "",
      "db_port" => "",
      "db_username" => "",
      "db_password" => "",
      "db_table" => ""
    ), $credentials);

    try{
      $this->dbh = new \PDO("mysql:dbname={$config['db_name']};host={$config['db_host']};port={$config['db_port']};charset=utf8;", $config['db_username'], $config['db_password']);
      /**
       * SQL Injection Vulnerable.
       */
      $table = htmlspecialchars($config['db_table']);

      $sql = $this->dbh->query("SELECT 1 FROM `". $table ."` LIMIT 1");
      if($sql !== false){
        return true;
      }else{
        return "no_table";
      }
    }catch(\PDOException $e){
      return false;
    }
  }

  public function registeredInAMonth(){
    $sql = $this->dbh->prepare("SELECT COUNT(1) FROM `{$this->table}` WHERE YEAR(`created`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(`created`) > MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
    $sql->execute();
    return $sql->fetchColumn();
  }
}
