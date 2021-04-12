<?php session_start()?>
<?php include "resources/includes/header.php"?>
<?php require "config.php"?>
<?php 
// start REQUEST_METHOD
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $adminUsername = $_POST['adminusername']; 
        $adminPassword = $_POST['adminpassword'];
        $hashedpass = sha1($adminPassword);

        $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=? AND groupid=1");
        $stmt->execute(array($adminUsername , $hashedpass));
        // fetchAll() -> fetch data from DB at array
        $row = $stmt->fetch();
        // rowCount() -> Boolen function to check if user is exist or not
        $count = $stmt->rowCount();
        $in_DB = 1 ;
        if($count == $in_DB){
            $_SESSION['USER_NAME'] = $adminUsername;
            $_SESSION['USER_ID'] = $row['user_id'];
            $_SESSION['FULL_NAME'] = $row['fullname'];
            $_SESSION['GROUP_ID'] = $row['groupid'];
            header("location:dashboard.php");
            exit();
        }else{
            echo "check username and password";
        }
    }
?>
    <div class="login">
        <div class="container">
            <h1 class="text-center">Admin Login</h1>
            <form method="POST" action="<?php $_SERVER['PHP_SELF']?>">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="TEXT" class="form-control" name="adminusername">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="adminpassword">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    
<?php include "resources/includes/footer.php"?>