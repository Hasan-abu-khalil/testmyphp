<?php

// title pages
function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}


//redirect
function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if ($url == null) {
        $url = 'index.php';
        $link = 'Home Page';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';

        } else {
            $url = 'index.php';
            $link = 'Home Page';

        }
    }
    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}


// function to check item in database

function checkItem($select, $from, $value)
{

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}



//count number of item function
//function to count number of item row
function countItem()
{
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT(UserID) FROM user");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

?>