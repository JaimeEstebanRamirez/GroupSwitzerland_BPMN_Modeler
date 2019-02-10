<?php
/*
 * DB Class
 * This class is used to connect to database
 * @author    CodexWorld.com
 * @url       http://www.codexworld.com
 * @license   http://www.codexworld.com/license
 */
class DB{
    private $dbHost     = "webappgroupswitzerland-mysqldbserver.mysql.database.azure.com";
    private $dbUsername = "mysqldbuser@webappgroupswitzerland-mysqldbserver";
    private $dbPassword = "@Italy2018";
    private $dbName     = "gsdb4";
    
    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
}