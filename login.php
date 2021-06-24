<?php 
session_start();
require "header.php";
if(isset($_POST["submit"]))
{
$name=trim($_POST["name"]);
    $_SESSION["name"]=$name;
if(empty($name) ||empty($_POST["pass"]))
{
    $_SESSION["err"]="All fields are required";
    header("Location:login.php");
        exit();  
}
else
{
  
        if($_POST["pass"]==1234)
    {
        $_SESSION["account"]=$name;
        header("Location:index.php");
        exit();
    }
    else
    {
        $_SESSION["err"]="Wrong password";
        header("Location:login.php");
        exit();
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/index.css">
    <style>
    .login form input {

        padding: 2%;
        margin-left: 10%;
        margin-top: 5%;
    }

    .login form label {
        margin-left: 10%;
        padding: 2%;
    }
    </style>
</head>

<body>
    <div style="position:absolute;
    bottom:40%;
    z-index:200;
    ">
        <?php if( !isset($_SESSION["account"])) {
        ?><div class="login">
            <h1>Login.....</h1>
            <p style="color:red;">
                <?php  if(isset($_SESSION["err"]))
            {
            echo $_SESSION["err"];
            unset($_SESSION["err"]);
            }
              ?>
            </p>
            <form action="login.php" method="post" style="
display: inline-grid;
        ">
                <label>Username</label><input type="text" name="name"
                    value='<?= isset($_SESSION["name"])?htmlentities($_SESSION["name"]):false;?>' \>
                <label>Password</label><input type="password" name="pass" \> <input type="submit" value="Submit"
                    name="submit">
            </form>
        </div><?php
    } else {



    ?>
        <h1>Already loged in as <?= $_SESSION["account"];?>. Click here to <a href="logout.php"> Logout</a>
        </h1>

        <?php } ?>

    </div>
</body>

</html>