<?php 
    class Database {
        
		private $hostMysql = "203.210.84.185";
        private $database_nameMysql = "absen";
        private $usernameMysql = "root";
        private $passwordMysql = "";
		
		//koneksi server local
		private $host = "203.210.84.185";
        private $database_name = "hrms";
        private $username = "hrms";
        private $password = "hrm";

        //koneksi cloud
        private $hostcloud = "localhost";
        private $database_name_cloud = "maf36581_hcms_new";
        private $username_cloud = "maf36581_hcms_new1";
        private $password_cloud = "CgD41m83MO2S";

        /*private $hostcloud = "localhost";
        private $database_name_cloud = "maf36581_hcms_new";
        private $username_cloud = "maf36581_hcms_new1";
        private $password_cloud = "CgD41m83MO2S";*/

        public $conn;

        public function getConnectionPostgreSQL(){
            $this->conn = null;
            try{
                $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }

        public function getConnectionPostgreSQLCloud(){
            $this->conn = null;
            try{
                $this->conn = new PDO("pgsql:host=" . $this->hostcloud . ";dbname=" . $this->database_name_cloud, $this->username_cloud, $this->password_cloud);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
		
		public function getConnectionMySql(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->hostMysql . ";dbname=" . $this->database_nameMysql, $this->usernameMysql, $this->passwordMysql);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
		

		
    }  
?>