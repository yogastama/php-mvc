<?php

class BaseModel
{
    private $host = 'localhost';
    private $databaseName = 'inventaris_barang';
    private $username = 'root';
    private $password = '';
    private $databaseHandler;
    private $statement;

    protected $table;
    protected $primaryKey;

    public function __construct()
    {
        $dataSourceName = 'mysql:host=' . $this->host . ';dbname=' . $this->databaseName;
        $option = [
            // menjaga agar databasenya tetap terjaga
            PDO::ATTR_PERSISTENT => true,
            // menampilkan error database
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->databaseHandler = new PDO($dataSourceName, $this->username, $this->password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function query($query)
    {
        $this->statement = $this->databaseHandler->prepare($query);
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
        $this->statement->bindValue($param, $value, $type);
    }
    public function execute()
    {
        $this->statement->execute();
    }
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll()
    {
        $this->query('SELECT * FROM ' . $this->table);
        return $this->resultSet();
    }
    public function findById($id)
    {
        $this->query('SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . '=:' . $this->primaryKey);
        $this->bind($this->primaryKey, $id);
        return $this->single();
    }
    public function add($data)
    {
        $query = "INSERT INTO " . $this->table . " VALUES ('', ";
        $i = 1;
        $totalData = count($data);
        foreach ($data as $key => $value) {
            if ($i != $totalData) {
                $query .= ':' . $key . ', ';
            } else {
                $query .= ':' . $key . ')';
            }
            $i++;
        }
        $this->query($query);
        foreach ($data as $key => $value) {
            $this->bind($key, $value);
        }
        $this->execute();
        return $this->rowCount();
    }
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    public function deleteById($id)
    {
        $query = "DELETE FROM " . $this->table . ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;
        $this->query($query);
        $this->bind($this->primaryKey, $id);
        $this->execute();

        return $this->rowCount();
    }
    public function updateById($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET ";
        $i = 1;
        $totalData = count($data);
        foreach ($data as $key => $value) {
            if ($i == $totalData) {
                $query .= $key . ' = :' . $key . ' ';
            } else {
                $query .= $key . ' = :' . $key . ', ';
            }
            $i++;
        }
        $query .= ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;
        $this->query($query);
        foreach ($data as $key => $value) {
            $this->bind($key, $value);
        }
        $this->bind($this->primaryKey, $id);
        $this->execute();
        return $this->rowCount();
    }
}
