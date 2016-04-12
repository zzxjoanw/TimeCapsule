<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/7/2015
 * Time: 2:16 PM
 */

/**
 * @param $connection object the mysql connection object from db-functions.php
 * @param $email
 * @return mixed
 */
function getUserInfo($connection, $studentID)
{
    $firstname = "";
    $lastname = "";
    $country = "";

    $sql = "SELECT firstname,lastname,country,currentYear FROM studentTable WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$studentID);
    $preparedStatement->execute();
    $preparedStatement->bind_result($firstname,$lastname,$country,$currentYear);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$firstname,$lastname,$country,$currentYear);
    }

    return $list;
}

/**
 * @param $connection object the mysql connection object from db-functions.php
 * @param $list
 * @return mixed
 */
function setUserInfo($connection, $list)
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
        if ($firstRunComplete == 1)
        {
            return true;
        }
    }

    return false;
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

function getPathInfo($connection)
{
    $sql = "SELECT pathName,currentLevel,maxLevel,progress FROM pathTable WHERE studentID = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("i", $_SESSION['studentID']);
    $preparedStatement->execute();
    $preparedStatement->bind_result($pathName, $currentLevel, $maxLevel,$progress);

    $pathOutput = array();
    while ($preparedStatement->fetch())
    {
        $row = array();
        array_push($row, $pathName);
        array_push($row, $currentLevel);
        array_push($row, $maxLevel);
        $progress = str_split($progress);
        array_push($row, $progress);
        array_push($pathOutput, $row);
    }

    return $pathOutput;
}

/*
function getGCSEs($connection,$country="",$id=0)
{
    $gcseID = NULL;
    $gcseName = NULL;
    $gcseCode = NULL;
    
    if($country != "")
    {
        $sql = "SELECT g.gcseID, g.gcseName,g.gcseCode FROM studentGCSETable AS s, gcseTable AS g
                WHERE gcseCountryList LIKE '%" . substr($country,0,1) . "%'";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT g.gcseID, g.gcseName, g.gcseCode FROM studentGCSETable s, gcseTable g WHERE s.studentID = ? AND s.gcseID = g.gcseID";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$id);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID, $gcseName, $gcseCode);

    $list = array();
    while($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$gcseID);
        array_push($row,$gcseName);
        array_push($row,$gcseCode);
//        $row = $gcseID . "|" . $gcseName . "|" . $gcseCode;
        array_push($list,$row);
    }

    return $list;
}*/

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

/**
 * @param $connection
 * @param $id
 * @return array
 */
function getMyGCSEs($connection, $id)
{
    $name = NULL;
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

/*
function addGCSEs($connection, $list)
{
    $sql = "INSERT INTO pathTable (JACSCode,pathName,gcseProgress)
            VALUES (?,?,0)
            ON DUPLICATE KEY UPDATE gcseProgress=0";
    
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = explode("|",$list[$i]);
        $preparedStatement->bind_param("ss",$listVal[0],$listVal[1]);
        $preparedStatement->execute() or die($connection->error);
    }
}

function updateGCSE($connection,$code,$newValue)
{
    $sql = "UPDATE pathTable SET gcseProgress=? WHERE JACSCode=?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("is",$newValue,$code);
    $preparedStatement->execute();
}*/

/*
 * part of updateGCSE() now
function removeGCSEs($connection, $list)
{
    $sql = "DELETE FROM studentGCSETable WHERE studentID = ? AND gcseID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}*/

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
    $name = NULL;
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

/*
 * gets all information about all careers or about all careers associated with a student id
 *
 * @param object $connection a mysql connection object
 * @param int $studentID optional. the student's id
 * @return array a 2d array of strings
 */
function getCareerInfo($connection,$studentID=-1)
{
    $id = NULL;
    $careerName = NULL;
    $interestList = NULL;
    $careerDescription = NULL;

    if($studentID==-1)
    {
        $sql = "SELECT * FROM careersTable";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT * FROM careersTable WHERE id = (SELECT careerID FROM studentTable WHERE studentID = ?)";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$studentID);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($id,$careerName,$interestList, $careerDescription);

    $careerArray = Array();
    while($preparedStatement->fetch())
    {
        $row = Array();
        array_push($row,$id);
        array_push($row,$careerName);
        array_push($row,$careerDescription);
        array_push($row,$interestList);
        array_push($careerArray,$row);
    }

    return $careerArray;
}

function getMyCareerInfo($connection,$studentID)
{
    $careerName = NULL;
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
    $careerName = NULL;

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

/**
 * @param $connection
 * @param int $studentID
 * @return array
 */
function getCareerInterests($connection, $studentID = -1)
//returns a 2d array of interests for each career
{
    if($studentID == -1)
    {
        //split this into two sections so the proper info will be passed to $careerInterestList on firsturn.php:33
        $sql = "SELECT careerName, interestList, careerDescription FROM careersTable";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->execute();
        $preparedStatement->bind_result($careerName,$interestList, $careerDescription);
        $careerInterestList = array();
        while ($preparedStatement->fetch()) {
            $row = array();
            array_push($row,$careerName);
            array_push($row,$interestList);
            array_push($row,$careerDescription);
            array_push($careerInterestList,$row);
        }

        return $careerInterestList;
    }
    else
    {
        //split this into two sections so the proper info will be passed to $careerInterestList on firsturn.php:33
        $sql = "SELECT interestList FROM careersTable WHERE studentID = ?";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$studentID);
        $preparedStatement->execute();
        $preparedStatement->bind_result($interests);
        while ($preparedStatement->fetch()) {
            array_push($careerInterestList, explode(",", $interests));
        }

        return $careerInterestList;
    }
}

/*
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
}*/

function getCareerDescriptions($connection)
{
    $description = NULL;
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

/**
 * @param $connection
 * @return array
 */
function getCareerIDs($connection)
{
    $id = NULL;
    
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
    $name = NULL;
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

//interests
function getInterests($connection,$id=-1)
{
    $interestID = NULL;
    $interestName = NULL;
    $interestCategory = NULL;
    
    if($id==-1)
    {
        $sql = "SELECT interestID, interestName, interestCategory FROM interestTable ORDER BY interestCategory";
        $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);

        $preparedStatement->execute() or die("execution error");
        $preparedStatement->bind_result($interestID, $interestName, $interestCategory) or die($connection->error);

        $list = array();
        while ($preparedStatement->fetch())
        {
            $row = array();
            array_push($row,$interestID);
            array_push($row,$interestName);
            array_push($row,$interestCategory);
            array_push($list,$row);
        }
    }
    else
    {
        $sql = "SELECT interestID, interestName, interestCategory FROM interestTable WHERE interestID IN
          (SELECT interestID FROM studentInterestsTable WHERE studentID = ?)";
        $preparedStatement = $connection->prepare($sql) or die("getInterests() default error ".$connection->error);
        $preparedStatement->bind_param("i",$_SESSION['studentID']);

        $preparedStatement->execute() or die("execution error");
        $preparedStatement->bind_result($interestID,$interestName,$interestCategory) or die("e".$connection->error);

        $list = array();
        while ($preparedStatement->fetch())
        {
            $row = array();
            array_push($row,$interestID);
            array_push($row,$interestName);
            array_push($row,$interestCategory);
            array_push($list,$row);
        }
    }

    return $list;
}
/*
function getAllInterests($connection)
{
    //gets IDs and names from the INterestTable
    $sql = "SELECT interestID, interestName, interestCategory FROM interestTable ORDER BY interestCategory";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID, $interestName, $interestCategory) or die("result error");

    $list = array();

    while ($preparedStatement->fetch())
    {
        $row = $interestID . "|" . $interestName . "|" . $interestCategory;
        array_push($list,$row);
    }

    return $list;
}

function getMyInterests($connection, $id)
{
    //$sql = "SELECT interestList FROM studentTable WHERE studentID = $id";
    $sql = "SELECT interestID, interestName FROM interestTable WHERE interestID IN
      (SELECT * FROM studentInterestTable WHERE studentID = ?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$_SESSION['studentID']);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestID, $interestName);
    $studentInterestList = array();
    while ($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$interestID);
        array_push($row,$interestName);
        array_push($studentInterestList,$row);
    }
    return $studentInterestList;
}
*/

/**
 * @param $connection
 * @param $list
 * @param $id
 */
function addInterests($connection, $list, $id)
{
    $sql = "INSERT INTO studentInterestsTable (studentID,interestID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    
    for($i=0;$i<count($list);$i++) {
        $preparedStatement->bind_param("ii", $id, $list[$i]);
        $preparedStatement->execute(); //will silently fail if studentID/interestID combo already in db
    }
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