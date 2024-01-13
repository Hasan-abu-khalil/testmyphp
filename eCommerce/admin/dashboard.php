<?php

session_start();

if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';
    include 'init.php';


    
    ?>
    <div class="container home-stats text-center py-5">
        <h1>Dashboard</h1>
        <div class="row py-3">
            <div class="col-md-3">
                <div class="stat">Total Members
                    <span>20</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat">Pending Members
                    <span>25</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat">Total Item
                    <span>2000</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat">Total Commets
                    <span>500</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa-solid fa-users"></i> Latest Registered Users
                    </div>
                    <div class="panel-body">
                        test
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa-solid fa-tag"></i> Latest Items
                    </div>
                    <div class="panel-body">
                        test
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    include $tpl . 'footer.php';

} else {
    header("location: index.php");
    exit();
}

?>