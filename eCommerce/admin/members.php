<?php

session_start();

$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {

    // $pageTitle = 'Dashboard';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    //start manage page

    if ($do == 'Manage') {
        //Manage manage page 

        //select all users except admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 ");

        //execute the statement
        $stmt->execute();

        // assign to variable
        $rows = $stmt->fetchAll();


        ?>

        <h1 class="text-center my-5">Member Page</h1>
        <div class="container">
            <div class="table-responsive">
                <a href='members.php?do=Add' class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i> Add new member</a>
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Registerd Date</th>
                            <th scope="col">Control</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                            <a href='members.php?do=Edit&userid=" . $row['UserID'] . " '><i class='mx-2 fa-solid fa-pen-to-square' style='color:blue;'></i></a>
                            <a href='members.php?do=Delete&userid=" . $row['UserID'] . " '><i class='mx-2 fa-solid fa-trash confirm' style='color:red;'></i></a>
                            </td>";


                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>

        <?php

    } elseif ($do == "Add") {
        // add members page
        // echo "welcome to add members page";


        ?>


        <h1 class="text-center"> Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">


                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"> UserName</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" autocomplete="off" placeholder="User Name"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"> Password</label>
                    <div class="col-sm-10">

                        <input type="password" name="password" class="password form-control" autocomplete="new-password"
                            placeholder="password" required>
                        <i class="show-pass fa-solid fa-eye"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"> Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"> Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="full" class="form-control" placeholder="Full Name" required>
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">

                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>

        <?php
    } elseif ($do == "Insert") {

        //insert member page
        // echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full'];


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center"> Insert Member</h1>';
            echo '<div class="container">';


            // get variables from the form
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashPass = sha1($_POST['password']);


            $formError = array();
            if (strlen($user) < 4) {
                $formError[] = ' <div class="alert alert-danger ">username cant be less than <strong>4 characters</strong> </div>';

            }

            if (strlen($user) > 20) {
                $formError[] = ' <div class="alert alert-danger ">username cant be more than <strong>20 characters</strong> </div>';

            }
            if (empty($user)) {
                $formError[] = ' <div class="alert alert-danger ">username cant be <strong>Empty</strong> </div>';
            }

            if (empty($pass)) {
                $formError[] = ' <div class="alert alert-danger ">password cant be <strong>Empty</strong></div>';
            }

            if (empty($name)) {
                $formError[] = ' <div class="alert alert-danger ">full name cant be <strong>Empty</strong></div>';
            }

            if (empty($email)) {
                $formError[] = ' <div class="alert alert-danger ">Email cant be <strong>Empty</strong> </div>';
            }

            foreach ($formError as $error) {
                echo $error;
            }

            if (empty($formError)) {

                // check if user exist  in database
                $check = checkItem("Username", "users", $user);
                if ($check == 1) {
                    $theMsg = "<div class='alert alert-danger'> Sorry This User Is Exist</div>";
                    redirectHome('$theMsg', 'back');
                } else {

                    // insert user info in database

                    $stmt = $con->prepare("INSERT INTO
                    users (Username , Password , Email , FullName, Date)
                  VALUES(:user , :pass , :email, :name , now())");
                    $stmt->execute(
                        array(
                            'user' => $user,
                            'pass' => $hashPass,
                            'email' => $email,
                            'name' => $name,

                        )
                    );

                    // echo success message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record inserted </div>';
                    redirectHome("$theMsg", 'back');
                }

            }



        } else {
            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page  directly</div>';
            redirectHome($theMsg, 'back');
        }

        echo '</div>';
    } elseif ($do == 'Edit') {

        //edit page 

        // check if get request userid is numeric & get the integer value  of it 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // select all data depend  on this  id
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

        //execute query
        $stmt->execute(array($userid));

        //fetch the data
        $row = $stmt->fetch();

        // the tow count
        $count = $stmt->rowCount();

        //if there's such id show  the form
        if ($stmt->rowCount() > 0) {
            ?>


            <h1 class="text-center"> Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> UserName</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control" autocomplete="off"
                                value="<?php echo $row['Username'] ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> Password</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password"
                                placeholder="leave blank if you don't want change">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"> Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <?php

            // if there's no such ID  show error message
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"> There No Such ID </div>';
            redirectHome($theMsg, 'back');
            echo '</div>';
        }


    } elseif ($do == "Update") {
        //update page
        echo '<h1 class="text-center"> Update Member</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // get variables from the form
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            // password trick
            $pass = '';
            if (empty($_POST['newpassword'])) {
                $pass = $_POST['oldpassword'];
            } else {
                $pass = sha1($_POST['newpassword']);
            }

            $formError = array();
            if (strlen($user) < 4) {
                $formError[] = ' <div class="alert alert-danger ">username cant be less than <strong>4 characters</strong> </div>';

            }

            if (strlen($user) > 20) {
                $formError[] = ' <div class="alert alert-danger ">username cant be more than <strong>20 characters</strong> </div>';

            }
            if (empty($user)) {
                $formError[] = ' <div class="alert alert-danger ">username cant be <strong>Empty</strong> </div>';
            }

            if (empty($name)) {
                $formError[] = ' <div class="alert alert-danger ">full name cant be <strong>Empty</strong></div>';
            }

            if (empty($email)) {
                $formError[] = ' <div class="alert alert-danger ">Email cant be <strong>Empty</strong> </div>';
            }

            foreach ($formError as $error) {
                echo $error;
            }

            if (empty($formError)) {

                // update the database with this info
                $stmt = $con->prepare("UPDATE users SET Username=?, Email=?, FullName=?, password=? WHERE UserID=?");
                $stmt->execute(array($user, $email, $name, $pass, $id));


                // echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Update </div>';
                redirectHome($theMsg, 'back');
            }




        } else {

            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page  directly</div>';
            redirectHome($theMsg);
        }
        echo '</div>';
    } elseif ($do == "Delete") {
        // Delete member page
        echo '<h1 class="text-center"> Delete Member</h1>';
        echo '<div class="container">';
        // check if get request userid is numeric & get the integer value  of it 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // select all data depend  on this  id
        $check = checkItem('userid', 'users', $userid);

        // $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

        // //execute query
        // $stmt->execute(array($userid));

        // //fetch the data
        // $row = $stmt->fetch();

        // // the tow count
        // $count = $stmt->rowCount();

        // //if there's such id show  the form
        // if ($stmt->rowCount() > 0) {

        if ($check > 0) {


            $stmt = $con->prepare("DELETE FROM users WHERE UserId = ?");
            $stmt->execute(array($userid));

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Delete </div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger"> this id is not exist </div>';
            redirectHome($theMsg);

        }
        echo "</div>";

    }

    include $tpl . 'footer.php';

} else {
    header("location: index.php");
    exit();
}

?>