<?php 


namespace Framework;
use PDO;

class Database{
    protected $conn;

    public function __construct(array $config) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database Connection Faild: {$e->getMessage()}", 1);
            
        }
    }
    
    /**
     * Executes a database query
     *
     * @param string $query query
     * 
     * @return PDOStatement
     * @throws PDOException
     */
    public function query(string $query, array $params = []) {
        try {
            $sth = $this->conn->prepare($query);

            // ‌Bind params to values
            foreach ($params as $param => $value) {
                $sth->bindValue(':'. $param, $value);
            };

            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query failed to execute: {$e->getMessage()}");
        }
    }
}
