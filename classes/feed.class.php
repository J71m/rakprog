<?php

class Feed
{

    protected $_PDO;

    public function __construct(PDO $PDO)
    {
        $this->_PDO = $PDO;
    }

    public function GetImages()
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM pic_group WHERE deleted IS null AND visibility <> 3 ORDER BY created DESC LIMIT 1");
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

    public function getUserInfo($user_id)
    {
        $stmt = $this->_PDO->prepare("SELECT * FROM rp_Users WHERE ID=$user_id LIMIT 1");
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
}
