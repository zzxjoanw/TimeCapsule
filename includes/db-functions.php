<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

/**
 * @return mysqli
 */
function openDBConnection()
{
    //these are overwritten in auth-info.php
    $host = "";
    $username = "";
    $password = "";
    $database = "";

    include("auth-info.php");

    $connection = new mysqli($host,$username,$password,$database) or die($connection->error);

    return $connection;
}

/**
 * @param $connection
 */
function closeDBConnection($connection)
{
    $connection->close();
}

/**
 * @param $preparedStatement
 */
function closeQuery($preparedStatement)
{
    $preparedStatement->close();
}

/**
 * @param $connection
 * @param $email
 * @param $password
 * @return array|bool
 */
function doLogin($connection, $email, $password)
{
    $sql = "SELECT studentID, firstname,lastname,country, firstRunComplete, careerID, interestList
            FROM studentTable WHERE email = ? AND password = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->bind_param("ss",$email,$password) or die("error in doLogin()");
    $preparedStatement->execute();
    $preparedStatement->store_result();
    $preparedStatement->bind_result($studentID,$firstname,$lastname,$country,$firstRunComplete,$careerID, $interestList);

    $list = array();

    if($preparedStatement->num_rows() == 1)
    {
        while($preparedStatement->fetch())
        {
            $careerName = getCareerNameByID($connection,$careerID);

            array_push($list,$studentID);
            array_push($list,$firstname);
            array_push($list,$lastname);
            array_push($list,$country);
            array_push($list,$firstRunComplete);
            array_push($list,$careerName);
            array_push($list,$interestList);
        }

        $temp = getCareerInfo($connection,$studentID);
        $_SESSION['careerName'] = $temp[1];
        return $list;
    }
    return false;
}

/**
 *
 */
function logout()
{
    unset($_SESSION['firstname']);
    unset($_SESSION['lastname']);
    unset($_SESSION['country']);
    session_destroy();
}

/**
 * @param $connection
 * @param $firstname
 * @param $lastname
 * @param $email
 * @param $password
 */
function insertStudent($connection, $firstname, $lastname, $email, $password)
{
    $sql = "INSERT INTO studentTable(firstname,lastname,email,password,firstRunComplete) VALUES(?,?,?,?,0)";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$preparedStatement->error());
    $preparedStatement->bind_param("ssss",$firstname,$lastname,$email,$password) or die("error: ".$preparedStatement->error());
    try
    {
        $preparedStatement->execute() or die($connection->error) . "<a href='register.php'>Return</a> to the registration page.";
    }
    catch(Exception $exception)
    {
        echo "Error: ".$exception->getMessage();
    }
    echo "<section>";
    echo "You've successfully registered with TimeCapsule! <a href='main.php'>Login from this page</a> to start your journey!.";
    echo "</section>";
}

/**
 * @param $connection
 * @param $code
 * @return mixed
 */
function getPathNameByCode($connection, $code)
{
    //should only return one result per code. if more than one, adjust the db table
    $sql = "SELECT courseName FROM courseTable WHERE JACSCode = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("s",$code);
    $preparedStatement->execute();
    $preparedStatement->bind_result($value);

    while($preparedStatement->fetch())
    {
        $pathName = $value;
    }

    return $pathName;
}