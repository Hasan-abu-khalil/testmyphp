<?php

session_start();
$noNavbar = '';
$pageTitle = 'Login';


if (isset($_SESSION['Username'])) {

    header('location: dashboard.php'); // redirect to dashboard page
}
// print_r($_SESSION);

include 'init.php';


?>

<?php

// check if user  coming form http post request 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // echo $username . ' ' . $password;

    // check if the user exist in database
    $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1  LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();


    // if count > 0 this mean  the database contain record about this Username
    if ($count > 0) {

        $_SESSION["Username"] = $username; //register session name
        $_SESSION["ID"] = $row['UserID']; //register session id
        header('location: dashboard.php'); // redirect to dashboard page
        exit();
    }
}

?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="UserName" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
    <button class="btn btn-primary btn-block" type="submit">Login</button>
</form>

<?php
include $tpl . 'footer.php';
?>