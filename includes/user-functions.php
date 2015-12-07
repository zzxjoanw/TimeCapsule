<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/7/2015
 * Time: 2:16 PM
 */

class user
{
    private $firstname = "";
    private $lastname = "";
    private $country = "";

    function  __constructor($firstname,$lastname,$country)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->country = $country;
    }

    /*
     * moved to db-functions.php
    public function login($connection, $username, $password, $role)
    {
        $table = $role . "Table";
        $sql = "SELECT * FROM " . $table . "WHERE username = ? AND password = ?;";
        $preparedStatement = $connection->prepare($sql);
        $preparedStatement->bind_param("ss", $username, $password);
        $preparedStatement->execute();
        $preparedStatement->bind_result($fn,$ln,$country);

        while ($preparedStatement->fetch())
        {
            if($preparedStatement->num_rows == 1)
            {
                session_start();
                $this->setFirstname($fn);
                $this->setLastname($ln);
                $this->setCountry($country);
            }
        }
    }
    */

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }
}