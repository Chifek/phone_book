<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 22.07.20
 * Time: 17:03
 */

class Database
{
    private $dbconn = "pgsql:host=localhost port=5432 dbname=phonebook user=postgres password=postrges";
    private $user = "postgres";
    private $password = "postgres";
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO($this->dbconn, $this->user, $this->password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert($firstName, $lastName, $userId, $path, $phone)
    {
        $sql = "INSERT INTO usersbook (user_id, path, first_name, last_name, phone) 
                  VALUES (:user_id, :path, :first_name, :last_name, :phone)";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['user_id' => $userId, 'path' => $path, 'first_name' => $firstName,
            'last_name' => $lastName, 'phone' => $phone]);

        return true;
    }

    public function getAll($userId)
    {
        $sql = "SELECT * FROM usersbook
                  WHERE user_id = :user_id";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['user_id' => $userId]);
        $result = $statement->fetchAll();
        $data = array();
        foreach ($result as $row) {
            $data[] = $row;
        }

        return $data;
    }

    public function getBookFromId($id)
    {
        $sql = "SELECT * FROM usersbook
                  WHERE id = :id";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();

        return $result;
    }

    public function update($id, $firstName, $lastName, $path, $phone, $userId)
    {
        if ($path === null) {
            $sql = "UPDATE usersbook SET first_name = :first_name, last_name = :last_name,
                  user_id = :user_id, phone = :phone
                  WHERE id = :id AND user_id = :user_id";
            $statement = $this->conn->prepare($sql);
            $statement->execute(['first_name' => $firstName, 'last_name' => $lastName, 'phone' => $phone, 'id' => $id, 'user_id' => $userId]);
        } else {
            $sql = "UPDATE usersbook SET first_name = :first_name, last_name = :last_name,
                  user_id = :user_id, path = :path, phone = :phone
                  WHERE id = :id AND user_id = :user_id";
            $statement = $this->conn->prepare($sql);
            $statement->execute(['first_name' => $firstName, 'last_name' => $lastName, 'user_id' => $userId, 'path' => $path,
                'phone' => $phone, 'id' => $id]);
        }

        return true;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM usersbook WHERE id = :id";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['id' => $id]);

        return true;
    }

    public function checkUserById($login)
    {
        $sql = "SELECT * FROM users WHERE login = :login";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['login' => $login]);
        $result = $statement->fetch();

        return $result;
    }

    public function createNewUser($login, $password, $email)
    {
        $sql = "INSERT INTO users (email, password, login) 
                  VALUES (:email, :password, :login)";
        $statement = $this->conn->prepare($sql);
        $statement->execute(['email' => $email, 'password' => $password, 'login' => $login]);

        return true;
    }
}