<?php
 
session_start();
 
if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
     
    session_destroy();
    header("Location: index.php"); //Redirect the user
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
 
function loginForm(){
    echo

    '
    <br><br><br>
    <img class="container-fluid" src="14.png"></img>
    <div class="container-sm">
    <div class="row g-3">
    <form action="index.php" method="post">
    <div class="col">
    <div class="input-group flex-nowrap">
  <span class="input-group-text" id="addon-wrapping">@</span>
  <input type="text" name="name" class="form-control" placeholder="Enter your Name here" aria-label="Username" aria-describedby="addon-wrapping">
</div>
</div>
<div>
      <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
  </div>';
}
 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">


    <title>Inter-Dimensional-Chat Application</title>
    <meta name="description" content="Gang-Chat Application" />
    <link rel="stylesheet" href="style.css" />
</head>

<body class="bg">
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
    ?>
    <div id="wrapper">
        <div id="menu">
            <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
            <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
        </div>

        <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
        </div>

        <form name="message" action="">
            <input name="usermsg" type="text" id="usermsg" />
            <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
        </form>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // jQuery Document
        $(document).ready(function () {
            $("#submitmsg").click(function () {
                var clientmsg = $("#usermsg").val();
                $.post("post.php", {
                    text: clientmsg
                });
                $("#usermsg").val("");
                return false;
            });

            function loadLog() {
                var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request

                $.ajax({
                    url: "log.html",
                    cache: false,
                    success: function (html) {
                        $("#chatbox").html(html); //Insert chat log into the #chatbox div

                        //Auto-scroll           
                        var newscrollHeight = $("#chatbox")[0].scrollHeight -
                            20; //Scroll height after the request
                        if (newscrollHeight > oldscrollHeight) {
                            $("#chatbox").animate({
                                scrollTop: newscrollHeight
                            }, 'normal'); //Autoscroll to bottom of div
                        }
                    }
                });
            }

            setInterval(loadLog, 2500);

            $("#exit").click(function () {
                var exit = confirm("Are you sure you want to end the session?");
                if (exit == true) {
                    window.location = "index.php?logout=true";
                }
            });
        });
    </script>
</body>

</html>
<?php
}
?>