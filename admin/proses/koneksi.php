<?php
if (!class_exists('Database')) {
    class Database
    {
        private $host = "localhost";
        private $user = "root";
        private $password = "";
        private $dbName = "sistem_penjualan_nanas";
        public $conn;

        public function __construct()
        {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
            if ($this->conn->connect_error) {
                die("Koneksi Gagal: " . $this->conn->connect_error);
            }
        }

        public function getConnection()
        {
            return $this->conn;
        }
    }
}
