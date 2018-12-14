<?php

class UserInfo
{

    protected $_userId;
    protected $_PDO;

    public function __construct($user_id, PDO $PDO)
    {
        $this->_userId = $user_id;
        $this->_PDO = $PDO;
    }

    public function getEmail()
    {
        $stmt = $this->_PDO->prepare("SELECT email FROM rp_Users WHERE ID=$this->_userId LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetch();
        $email = $result["email"];
        return $email;
    }

    public function getUsername()
    {
        $stmt = $this->_PDO->prepare("SELECT username FROM rp_Users WHERE ID=$this->_userId LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetch();
        $username = $result["username"];
        return $username;
    }

    public function getUsernameById($id)
    {
        $stmt = $this->_PDO->prepare("SELECT username FROM rp_Users WHERE ID=$id LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetch();
        $username = $result["username"];
        return $username;
    }

    public function getIdByUsername($username)
    {
        $stmt = $this->_PDO->prepare("SELECT ID FROM rp_Users WHERE userName='$username' LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetch();
        $id = $result["ID"];
        return $id;
    }

    public function getAllInfo()
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM rp_Users WHERE ID=$this->_userId LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $userObject = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $userObject;
    }

    public function getByUsername($username)
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM rp_Users WHERE userName='$username' LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $userObject = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $userObject;
    }

    public function getByHoldingID($id)
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM rp_Holdings INNER JOIN rp_Users ON rp_Holdings.sitterID=rp_Users.ID INNER JOIN rp_Animals ON rp_Holdings.animalID=rp_Animals.ID WHERE rp_Holdings.holdingID='$id' LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $userObject = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $userObject;
    }

    public function getByHoldingID2($id)
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM rp_Holdings INNER JOIN rp_Animals ON rp_Holdings.animalID=rp_Animals.ID WHERE rp_Holdings.holdingID='$id' LIMIT 1");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $userObject = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $userObject;
    }

    public function getUserlist()
    {
        $stmt = $this->_PDO->prepare("SELECT userName, firstName, lastName, sexId FROM rp_Users");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getUserAnimals($user_id)
    {
        $stmt = $this->_PDO->prepare("SELECT pathToPic, petname, type FROM rp_Animals WHERE ownerID=$user_id");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    public function getUserRatings($user_id)
    {
        $stmt = $this->_PDO->prepare("SELECT rating, ratingDesc, ratingDate, rp_Users.firstName, rp_Users.lastName, rp_Users.pathToPic FROM rp_UserRatings INNER JOIN rp_Users ON rp_UserRatings.raterID = rp_Users.ID WHERE userID=$user_id");
        if (false === $stmt) {
            die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $stmt->execute();
        if (false === $stmt) {
            die('execute() failed: ' . htmlspecialchars($mysqli->error));
        }
        $result = $stmt->fetchAll();
        return $result;
    }

}
