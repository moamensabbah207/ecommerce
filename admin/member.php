<?php
session_start();
    // $do="";
    if(isset($_GET['do'])){
        $do = $_GET['do'] ;
    }else{
        // echo "sorry";
        $do = "manage" ;
    }
?>
<?php if(isset($_SESSION['USER_NAME'])):?>
<?php include "resources/includes/header.php"?>
<?php require "config.php"?>
<?php include "resources/includes/navbar.php"?>
<!-- start all member page -->
<?php if($do == "manage"):?>
<?php
// Select All From DB 
        $stmt = $con->prepare("SELECT * FROM users WHERE groupid = 0");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
<div class="container">
    <h1 class="text-center">All Members</h1>
    <!-- add member button -->
    <a class="btn btn-primary" href="?do=add"><i class="fas fa-user-plus"></i> Add Member</a>
    <!--/member button -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Photo</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Date</th>
                <th scope="col">Control</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row):?>
            <tr>
                <th scope="row">
                <img style="height: 50px; width: 50px;" src="public\img\uploaded\member\<?=$row["path"]?>" alt="<?=$row["path"]?>">
                </th>
                <th scope="row"><?= $row["username"]?></th>
                <td><?= $row["email"]?></td>
                <td><?= $row["created_at"]?></td>
                <td>
                    <a class="btn btn-secondary" href="?do=show&userid=<?= $row["user_id"]?>" title="Show"><i
                            class="fas fa-eye"></i></a>
                    <a class="btn btn-warning" href="?do=edit&userid=<?= $row["user_id"]?>" title="Edit"><i
                            class="fas fa-edit"></i></a>
                    <a class="btn btn-danger" href="" title="Delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php elseif($do == "add"):?>
<div class="container">
    <h1 class="text-center">Add Member</h1>
    <form method="POST" action="?do=insert" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="mb-3">
            <label class="form-label">Fullname</label>
            <input type="text" class="form-control" name="fullname">
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Upload photo</label>
            <input class="form-control" type="file" id="formFile" name="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php elseif($do == "insert"):?>
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $avatarName = $_FILES['avatar']['name'];
        $avatarType = $_FILES['avatar']['type'];
        $avatarTmpName = $_FILES['avatar']['tmp_name'];
        $avatarError = $_FILES['avatar']['error'];
        $avatarSize = $_FILES['avatar']['size'];

        $avatarAllExtension = array("image/jpeg","image/png","image/jpg");
        if(in_array($avatarType , $avatarAllExtension)){
            $avatar = rand(0 , 1000)."_".$avatarName;
            $destination = "public\img\uploaded\member\\".$avatar;
            move_uploaded_file($avatarTmpName , $destination);
        }else{
            echo "sorry your extension is " . $avatarType;
        }
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $password = sha1($_POST['password']);
        $fullname = $_POST['fullname'];

        $formErrors = array();
        if(empty($username)){
            $formErrors[] = "username mustnot be empty";
        }
        if(strlen($username) < 4){
            $formErrors[] = "username mustnot be less than 4 character";
        }
        foreach($formErrors as $error){
            echo $error . "<br>";
        }

        $stmt = $con->prepare("INSERT INTO users(username,password,email,fullname,groupid,created_at,path) VALUES(?,?,?,?,0,now(),?)");
        $stmt->execute(array($username , $password , $email , $fullname , $avatar));
        header("location:member.php");
    }
?>
<?php elseif($do == "edit"):?>
<?php
        /*if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
            $userid =intval($_GET['userid']);
        }else{
            echo "0";
        }*/

        $userid =isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0;
        $stmt = $con-> prepare('SELECT * FROM users WHERE user_id = ?');
        $stmt->execute(array($userid));
        $row = $stmt-> fetch();
        $count = $stmt->rowCount();
        
    ?>
<?php if($count == 1):?>
<div class="container">
    <h1 class="text-center">Edit Member</h1>
    <form method="POST" action="?do=update">
        <div class="mb-3">
            <input type="hidden" class="form-control" value="<?= $row['user_id']?>" name="userid">
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?= $row['username']?>" name="username">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="newpassword">
            <input type="hidden" class="form-control" value="<?= $row['password']?>" name="oldpassword">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" value="<?= $row['email']?>" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Fullname</label>
            <input type="text" class="form-control" value="<?= $row['fullname']?>" name="fullname">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<?php endif?>
<?php elseif($do == "update"):?>
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $userid   = $_POST['userid'];
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $fullname = $_POST['fullname'];
        $password = empty($_POST['newpassword'])?$_POST['oldpassword']:$_POST['newpassword'];
        $hashedpass = sha1($password);
        $stmt =$con->prepare("UPDATE users SET username=? , password=? , email=? , fullname=? WHERE user_id=?");
        $stmt->execute(array($username , $hashedpass , $email , $fullname , $userid));
        header("location:member.php");
    }
?>
<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>
<?php
            $userid = $_GET['userid'];
            $stmt = $con-> prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt->execute(array($userid));
            $row = $stmt-> fetch();
            
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        ?>
<?php endif?>

<?php include "resources/includes/footer.php"?>
<!-- end member CRUD page -->
<?php else:?>
<?php header("location:index.php");?>
<?php endif?>