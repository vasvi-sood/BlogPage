<?php
include "header.php";
require "pdo.php";
session_start();
$user="";
$title="";
$content="";
// $err="";
// $msg="";
if(isset($_POST["post"]))
{
    $user=$_SESSION["account"];
    $title=trim($_POST["title"]);
    $content=trim($_POST["content"]);
    if(empty($user)||empty($title)|| empty($content) )
    {
        $_SESSION["msg"]=isset($_SESSION["msg"])?"":false;
        $_SESSION["err"]="All fields are required";
   
    }
   else
   {
    $sql="INSERT INTO blogs.blogs ( `user`, `title`, `content`) VALUES ( :user, :title, :content)";
    $stm=$pdo->prepare($sql);
    $stm->execute(array(':user'=>$user, ':title'=>$title, 'content'=>$content
  
));
$_SESSION["err"]=isset($_SESSION["err"])?"":false;
$_SESSION["msg"]="Your blog is posted";
// $err="All fields are required";
// $msg="Your blog is posted";
header("Location:insert.php");
return;
   }
}

?>
<?php if( isset($_SESSION["account"])) {
        ?>
<div class="adddata" style=" position:absolute; 
     top:20%;
     left:20%;">
    <h1>Hello, <?= htmlentities($_SESSION["account"]);?></h1>
    <form action="insert.php" method="post" style="display:inline-grid">
        <p style="color:red;"><?= isset($_SESSION["err"])?$_SESSION["err"]:false;  ?></p>
        <p style="color:green;"><?= isset($_SESSION["msg"])?$_SESSION["msg"]:false; ?></p>

        <label>Title</label>
        <input type="text" name="title" id="" value="<?= htmlentities($title) ?>">
        <label>Content</label>
        <textarea name="content" cols="30" rows="10">
        <?= htmlentities($content) ?>
    </textarea>
        <input type="submit" name="post" value="post">
        <a href="diary.php">View Enteries</a>
    </form>

</div>
<?php
    } else {

        header("Location:login.php");
        exit();

 } ?>

<?php
include "footer.php";
?>