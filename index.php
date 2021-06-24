<?php
session_start();

require_once("pdo.php");

if (isset($_POST["votes"])) {
    //only upvote if logged in
    if (isset($_SESSION["account"])) {

        $user=$_SESSION["account"];
        $sno = $_POST["sno"];
        $postid=$sno;
        $sql = "Select votes from blogs.blogs where sno= :sno";
        $stmt = $pdo->prepare($sql);

        $stmt->execute(
            array(
                ':sno' => $sno
            )
        );
        $sqlnew="Insert into blogs.likes ( `user`, `postid`) VALUES (:user, :postid);";
        $stmtnew = $pdo->prepare($sqlnew);

      
     
        $votes = 0;
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row != false) {
            $votes = $row["votes"];


            $votes++;

            $sql = "Update blogs.blogs SET votes= :votes where sno = :sno";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(
                    ':votes' => $votes,
                    ':sno' => $sno
                )
            );
            $stmtnew->execute(
                array(
                    ':user' => $user,
                    ':postid' => $postid
                )
            );

            //new fetch code- the response is only number of votes
            echo $votes;
            exit();
        }
    }
}

if (isset($_POST["unvotes"])) {
    //only upvote if logged in
    if (isset($_SESSION["account"])) {

        $user=$_SESSION["account"];
        $sno = $_POST["sno"];
        $postid=$sno;
        $sql = "Select votes from blogs.blogs where sno= :sno";
        $stmt = $pdo->prepare($sql);

        $stmt->execute(
            array(
                ':sno' => $sno
            )
        );
        $sqlnew="Delete from blogs.likes where  `user`= :user AND `postid`= :postid;";
        $stmtnew = $pdo->prepare($sqlnew);

      
     
        $votes = 0;
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row != false) {
            $votes = $row["votes"];


            $votes--;

            $sql = "Update blogs.blogs SET votes= :votes where sno = :sno";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(
                    ':votes' => $votes,
                    ':sno' => $sno
                )
            );
            $stmtnew->execute(
                array(
                    ':user' => $user,
                    ':postid' => $postid
                )
            );

            //new fetch code- the response is only number of votes
            echo $votes;
            exit();
        }
    }
}
require "header.php";
// 
$sql = "Select * from blogs.blogs ORDER BY RAND() ";
$stmt = $pdo->query($sql);


echo '<div class="entry">';
echo '<h1>All blogs are displayed here</h1>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>



<div class="content">
    <div class="title">
        <h2>
            <?php echo $row["title"]; ?></h2>
    </div>
    <div class="name" style="color:red; font-size:large; text-align:right; text-transform:capitalize">
        <?php echo $row["user"]; ?></h2>
    </div>
    <?php echo $row["content"]; ?></h2>


</div>
<div class="upvote">
    <span id="likes_count<?php echo $row["sno"]; ?>"><?php echo $row["votes"];  ?> Votes</span>

    <?php if (!isset($_SESSION["account"])) { ?>
    <button type="button" class="button" name="votes" onclick="like(<?php echo $row['sno']; ?>)" id="votes"><img
            src="assets/like.jpg" /></button>
    <?php } else {
            $user = $_SESSION["account"];
            $postid=$row["sno"];
            $sql2="Select * from blogs.likes WHERE `user`= :user and `postid`= :postid";
            
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute(
                array(
                    ':user' => $user,
                    ':postid' => $postid
                )
            );
            //liked
            if($stmt2->fetch(PDO::FETCH_ASSOC))
            { ?>
    <button type="button" class="button" name="votes" onclick="unlike(<?php echo $row['sno']; ?>)"
        id="unlike<?php echo $row['sno']; ?>">
        <img src="assets/unlike.png" id="imgunlike<?php echo $row["sno"]; ?>" /></button>
    <button type="button" class="button hide" onclick="like(<?php echo $row['sno']; ?>)"
        id="like<?php echo $row['sno']; ?>"><img src="assets/like.jpg"
            id="imglike<?php echo $row["sno"]; ?>" /></button>
    <?php } 
    // not liked
    else {?>
    <button type="button" class="button hide" name="votes" onclick="unlike(<?php echo $row['sno']; ?>)"
        id="unlike<?php echo $row['sno']; ?>">
        <img src="assets/unlike.png" id="imgunlike<?php echo $row["sno"]; ?>" /></button>
    <button type="button" class="button" onclick="like(<?php echo $row['sno']; ?>)"
        id="like<?php echo $row['sno']; ?>"><img src="assets/like.jpg"
            id="imglike<?php echo $row["sno"]; ?>" /></button>

    <?php } } ?>
</div>







<?php
}
echo '</div>'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function like(sn) {

    var sno = sn;
    var votes = true;


    $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
            votes: votes,
            sno: sno
        },
        success: function(response) {
            if (isNaN(response)) {
                window.location.href = "login.php";
            } else {
                document.getElementById(`likes_count${sno}`).textContent = response + " Votes";
                document.getElementById(`like${sno}`).classList.add("hide");
                document.getElementById(`unlike${sno}`).classList.remove("hide");
                console.log(response);

            }
        }
    });
}

function unlike(sn) {

    var sno = sn;
    var unvotes = true;


    $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
            unvotes: unvotes,
            sno: sno
        },
        success: function(response) {
            if (isNaN(response)) {
                window.location.href = "login.php";
            } else {
                document.getElementById(`likes_count${sno}`).textContent = response + " Votes";
                document.getElementById(`unlike${sno}`).classList.add("hide");
                document.getElementById(`like${sno}`).classList.remove("hide");
                console.log(response);
            }
        }
    });
}
</script>
<?php
include "footer.php";
?>