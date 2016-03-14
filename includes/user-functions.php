<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/7/2015
 * Time: 2:16 PM
 */

/*
 * @param object $connection
 * @param string $email
 */
function getUserInfo($connection,$email)
{
    $firstname = "";
    $lastname = "";
    $country = "";

    $sql = "SELECT firstname,lastname,country FROM studentTable WHERE email = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$email);
    $preparedStatement->execute();
    $preparedStatement->bind_result($firstname,$lastname,$country);

    return $preparedStatement->fetch_array(MYSQLI_NUM);
}

function setUserInfo($connection,$list)
{
    //$list is a key-value array containing the columns to be updated for each user

    $sql = "";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    //can I have multiple bind_param statements?
    return $preparedStatement->execute();
}

/*
 * @param object $connection a mysqli connection object
 * @param int $id a student id
 * @return true or false depending on whether the user has done the first run procedure
 */
function isFirstRunComplete($connection, $id)
{
    $firstRunComplete = NULL;
    $sql = "SELECT firstRunCompelete FROM studentTable WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($firstRunComplete);

    while($preparedStatement->fetch())
    {
        if($firstRunComplete == 1)
        {
            return true;
        }

        return false;
    }
}

/*
 * @param object $connection a mysqli connection object
 * @param int $value the new value for firstRunComplete. Must be 0 or 1.
 * @return false if an invalid value is passed in, else true
 */
function setFirstRunValue($connection,$value,$id)
{
    if(($value!=1) && ($value!=0))
    {
        return false;
    }

    $sql = "UPDATE studentTable SET firstRunComplete = ? WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("ii",$value,$id);
    $preparedStatement->execute();

    return true;
}

function getGCSEs($connection,$country="",$id=0)
{
    if($country != "")
    {
        $sql = "SELECT g.id, g.name FROM studentGCSETable AS s, gcseTable AS g
                WHERE gcseCountryList LIKE '%" . substr($country,0,1) . "%'";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT g.id, g.name FROM studentGCSETable s, gcseTable g WHERE s.studentID = ? AND s.gcseID = g.gcseID";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$id);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID, $gcseName);

    $list = array();
    while($preparedStatement->fetch())
    {
        $row = $gcseID . "|" . $gcseName;
        array_push($list,$row);
    }

    return $list;
}

function getAllGCSEs($connection, $country)
{
    $gcseID = NULL;
    $gcseName = NULL;
    $initial = substr($country,0,1);
    $sql = "SELECT gcseID, gcseName FROM gcseTable WHERE gcseCountryList LIKE '%" . $initial . "%'";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID, $gcseName);

    $list = array();

    while($preparedStatement->fetch())
    {
        $row = $gcseID . "|" . $gcseName;
        array_push($list,$row);
    }

    return $list;
}

function getMyGCSEs($connection, $id)
{
    $sql = "SELECT DISTINCT g.gcseID, g.gcseName FROM studentGCSETable AS s, gcseTable AS g
            WHERE s.studentID = ? AND s.gcseID = g.gcseID";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id,$name);

    $list = array();

    while($preparedStatement->fetch())
    {
        $subArray = array();
        array_push($subArray,$id);
        array_push($subArray,$name);
        array_push($list,$subArray);
    }

    return $list;
}

function getOtherGCSEs($connection, $id)
{
    $gcseID = null;
    $sql = "SELECT gcseID FROM gcseTable WHERE gcseID NOT IN (SELECT gcseID FROM studentGCSETable WHERE studentID = ?)";

    $preparedStatement = $connection->prepare($sql) or die("error2: ".$connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$gcseID);
    }
    return $list;
}

function addGCSEs($connection, $list)
{
    $sql = "INSERT INTO studentGCSETable (studentID,gcseID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = $list[$i];
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$listVal) or die("error1");
        $preparedStatement->execute() or die($connection->error);
    }
}

function removeGCSEs($connection, $list)
{
    $sql = "DELETE FROM studentGCSETable WHERE studentID = ? AND gcseID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}

function getAllALevels($connection,$country)
{
    $aLevelID = NULL;
    $aLevelName = NULL;
    $initial = substr($country,0,1);
    $sql = "SELECT alevelID, alevelName FROM aLevelTable WHERE alevelCountryList LIKE '%" . $initial . "%'";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($aLevelID, $aLevelName);

    $list = array();

    while($preparedStatement->fetch())
    {
        $row = $aLevelID . "|" . $aLevelName;
        array_push($list,$row);
    }

    return $list;
}

function getMyALevels($connection, $id)
{
    $sql = "SELECT DISTINCT a.aLevelID, a.aLevelName FROM studentALevelTable AS s, aLevelTable AS a
            WHERE s.studentID = ? AND s.aLevelID = a.aLevelID";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id, $name);

    $list = array();

    while($preparedStatement->fetch())
    {
        $subArray = array();
        array_push($subArray,$id);
        array_push($subArray,$name);
        array_push($list,$subArray);
    }

    return $list;
}

function getOtherALevels($connection, $id)
{
    $aLevelID = null;
    $sql = "SELECT aLevelID FROM aLevelTable WHERE aLevelID NOT IN (SELECT aLevelID FROM studentALevelTable WHERE studentID = ?)";

    $preparedStatement = $connection->prepare($sql) or die("error2: ".$connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($aLevelID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$aLevelID);
    }
    return $list;
}

function addALevels($connection,$list)
{
    $sql = "INSERT INTO studentALevelTable (studentID,aLevelID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = $list[$i];
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$listVal) or die("error1");
        $preparedStatement->execute() or die($connection->error);
    }
}

function removeALevels($connection,$list)
{
    echo "userfunctions:179 removeALevels()";
    $sql = "DELETE FROM studentALevelTable WHERE studentID = ? AND aLevelID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}

//do all interests appear in myInterests or OtherInterests?
function getOtherInterests($connection, $id)
//gets all interests not associated with the current student
{
    $interestID = NULL;
    $sql = "SELECT interestID FROM interestTable WHERE interestID NOT IN " .
           "(SELECT interestID FROM studentInterestsTable WHERE studentID = " . $id . ")";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$interestID);
    }
    return $list;
}

function getMyInterests($connection, $id)
//gets all the current student's interests
{
    $interestList = NULL;
    $sql = "SELECT interestList FROM studentTable WHERE studentID = $id";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestList);
    $studentInterestList = array();
    while ($preparedStatement->fetch())
    {
        $row = $interestList;
        $studentArray = explode(",",$row);
        array_push($studentInterestList,$studentArray);
    }
    return $studentInterestList;
}

class career
{
    private $id;
    private $name;
    private $interestList;
    private $description;

    function getID() { return $this->id; }
    function getName() { return $this->name; }
    function getInterestList() { return $this->interestList; }
    function getDescription() { return $this->description; }

    function setID($id) { $this->id = $id; }
    function setName($name) { $this->name = $name; }
    function setInterestList($interestList) { $this->interestList; }
    function setDescription($description) { $this->description; }
}

function getMyCareerInfo($connection,$studentID)
{
    $sql = "SELECT careerName FROM careersTable WHERE id = (SELECT careerID FROM studentTable WHERE id = ?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$studentID);
    $preparedStatement->execute();
    $preparedStatement->bind_result($careerName);
    while($preparedStatement->fetch())
    {
        $_SESSION['careerName'] = $careerName;
    }
}

function getCareerNameByID($connection,$id)
{
    $sql = "SELECT careerName FROM careersTable WHERE id = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($careerName);
    while($preparedStatement->fetch())
    {
        return $careerName;
    }
}

function getCareerInterests($connection)
//returns a 2d array of interests for each career
{
    $interests = NULL;
    $sql = "SELECT interestList FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interests);
    $careerNameList = array();
    $careerInterestList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerInterestList,explode(",",$interests));
    }

    return $careerInterestList;
}

function getCareerInfo($connection)
{
    $sql = "SELECT * FROM careersTable";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id, $careerName, $interestList, $careerDescription);

    $careerArray = Array();
    while($preparedStatement->fetch())
    {
        $career = new career();
        $career->setID($id);
        $career->setName($careerName);
        $career->setInterestList($interestList);
        $career->setDescription($careerDescription);
        array_push($careerArray,$career);
    }

    $_SESSION['careerArray'] = $careerArray;
}

function getCareerDescriptions($connection)
{
    $sql = "SELECT careerDescription FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($description);
    $careerDescriptionList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerDescriptionList,$description);
    }
    return $careerDescriptionList;
}

function getCareerIDs($connection)
{
    $sql = "SELECT id FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id);
    $careerIDList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerIDList,$id);
    }

    return $careerIDList;
}

function getCareerNames($connection)
{
    $sql = "SELECT careerName FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($name);
    $careerNameList = array();
    $careerInterestList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerNameList,$name);
    }

    return $careerNameList;
}

function setCareer($connection, $careerID, $studentID)
{
    $sql = "UPDATE studentTable SET careerID = ? WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("ii",$careerID,$studentID);
    $preparedStatement->execute();
}

function intCheck($array1,$array2)
{
    $arrayIntersection = array_intersect($array1,$array2);
    return (count($arrayIntersection)/count($array1))*100;
}

function getAllInterests($connection)
{
    $interestID = $interestName = NULL;
    //gets IDs and names from the INterestTable
    $sql = "SELECT interestID, interestName FROM interestTable";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID, $interestName) or die("result error");

    $list = array();

    while ($preparedStatement->fetch())
    {
        $row = $interestID . "|" . $interestName;
        array_push($list,$row);
    }

    return $list;
}

function addInterests($connection, $list, $id)
{
    $id = $_SESSION['studentID'];

    $listString = implode(",",$list);

    $sql = "UPDATE studentTable SET interestList = ? WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("si",$listString,$id);
    $preparedStatement->execute() or die($connection->error);
}

function removeInterests($connection,$list)
{
    $sql = "DELETE FROM studentInterestsTable WHERE studentID = ? AND interestID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}