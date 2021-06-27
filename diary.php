<?php


if (isset($_POST["del"])) {
    require "pdo.php";
    $sno = $_POST["sno"];
    $sql = "delete from blogs.blogs where sno= $sno";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo $sno;
    exit();
}

if(isset($_POST["edit"]))
{
    require "pdo.php";
    $sno = $_POST["sno"];
    $title=$_POST["title"];
    $content=$_POST["content"];
    $sql = "Update blogs.blogs set title= :title, content =:content where sno= :sno";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ":title"=>$title,
    ":content"=>$content,
    ":sno"=>$sno
));
    // echo $sno;
    header("Location:diary.php");
    return;
}
include "header.php";
session_start();

?>

<link rel="stylesheet" href="style/diary.css">
</link>
<?php if (isset($_SESSION["account"])) {
    require "pdo.php";
    $user = $_SESSION["account"];
    $sql = "select * from blogs.blogs where user= :user ORDER BY SNO desc";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user" => $user));

    echo '<div class="main">
<div class=" sub">';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?><div class="card" id="card<?= $row['sno']; ?>">
    <div class="button">
        <?php
         $arr1 = array(
            "sno" => $row["sno"],
            "title" => ($row['title'])
         );
                $var =json_encode($arr1,JSON_HEX_APOS);
                ?>
        <input type="button" value="Delete" onclick='func(<?php echo $var; ?>)' />
        <?php
               
                $arr = array(
                    "sno" => $row["sno"],
                    "title" => ($row['title']),
                    "content" => $row['content']
                );
                        
                $var2 = json_encode($arr,JSON_HEX_APOS);
                ?>
        <input type="button" value="Edit" onclick=' edit(<?= $var2 ?> )' />
    </div>
    <H3><?= htmlentities($row["title"]);
        ?></H3>
    <div><?= htmlentities($row["content"]);
            ?></div>
</div>
<?php
    } ?>

</div>
</div>
<div class="overlay hidden" id="overlay">
    <div id="cancel"><button onclick=' canc()'><img src=" assets/cancel.jpg"></button>
    </div>

    <form action="diary.php" method="post" style="display:inline-grid;position:fixed; top:40%; left:30%">
        <label>Title</label>
        <input type="hidden" name="sno" id="sno" value="">
        <input type=" text" name="title" id="title" value="">
        <label>Content</label>
        <textarea name="content" cols="30" rows="10" id="content">
        </textarea>
        <input type="submit" name="edit" value="Edit">
        <button onclick=' canc()'>Cancel
    </form>
</div>
<a href=" insert.php" class="insert"><img src=" assets/plus.gif" /></a>


?>
<div class="profile">
    <h1 style="
    color: white;
">HELLO, <?=htmlentities($_SESSION['account']);?></h1>
    Gender
    <br>
    <input type="radio" name="gender" id="" value="Female" onclick="change('female','male','cute' )">Female
    <input type="radio" name="gender" id="" value="Male" onclick="change('male','female','cute' )">Male
    <input type="radio" name="gender" id="" value="notsay" onclick="change('cute','male','female' )" checked>Rather not
    say
    <div id="female" class="hidden"><img src="assets/female.png" />
    </div>
    <div id="male" class="hidden"><img src="assets/male.png" /> </div>
    <div id="cute" class=""><img src="assets/cute.png" width="80%" /> </div>
</div>
<?php
                                    

                                        ?><?php
    } else {

        header("Location:login.php");
        exit();
    }

        ?><?php include "footer.php";

    ?><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function change(a, b, c) {
    console.log(a, b, c);
    document.getElementById(a).classList.remove("hidden");
    document.getElementById(b).classList.add("hidden");
    document.getElementById(c).classList.add("hidden");

}


function func(arr) {

    let sno = JSON.parse(JSON.stringify(arr["sno"]));
    let title = JSON.parse(JSON.stringify(arr["title"]));
    console.log(sno);
    let ans = confirm("Are you sure you want to delete the blog: " + title);
    console.log(ans);
    let del = true;

    if (ans == true) {
        $.ajax({

            url: ' diary.php',
            type: 'post',
            data: {
                del: del,
                sno: sno
            },
            success: function(response) {
                {
                    document.getElementById(`card${sno}`).style.display = "none"; //
                    // console.log(document.getElementById(`card${sno}`).classList);
                    console.log(response);
                }
            }
        });
    }
}

function edit(arr) {

    let cont = document.getElementById("content");
    console.log(arr);
    document.getElementById("overlay")
        .classList.remove('hidden');
    var a = arr["title"];
    a = JSON.parse(JSON.stringify(a));
    var search = "\\";
    // a = a.replaceAll(search, "");
    document.getElementById("title").value = a;
    var b = arr["content"];
    b = JSON.parse(JSON.stringify(b));
    // b = b.replaceAll(search, "");
    cont.textContent = b;

    document.getElementById("sno").value = arr["sno"];





}

function canc() {
    document.getElementById("content").textContent = "";
    document.getElementById("overlay").classList.add("hidden");

}
</script><?php include "footer.php";
                        ?>