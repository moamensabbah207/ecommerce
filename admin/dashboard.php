<?php session_start()?>
<?php if(isset($_SESSION['USER_NAME'])):?>
<?php include "resources/includes/header.php"?>
<?php require "config.php"?>
<?php include "resources/includes/navbar.php"?>
<?php include "resources/function.php"?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="member">
                    <i class="fas fa-users"></i>
                    <?php echo countItem("user_id" , "users")?>
                </div>
            </div>
        </div>
    </div>
<?php include "resources/includes/footer.php"?>
<?php else:?>
<?php header("location:index.php")?>
<?php endif?>