<?php
session_start();
if(isset($_SESSION['username'])){
    #database connection file
    include 'app/db.conn.php';

    include 'app/helpers/user.php';

    include 'app/helpers/chat.php';
    include 'app/helpers/opened.php';

    include 'app/helpers/timeAgo.php'; 

    
  

    if(!isset($_GET['user'])){
        header("Location:home.php");
        exit;
    }

     #Getting user data 

     $chatWith = getUser($_GET['user'],$conn);

     if(empty($chatWith)){
        header("Location:home.php");
        exit;
     }

     $chats = getChats($_SESSION['user_id'],$chatWith['user_id'],$conn);

     opened($chatWith['user_id'],$conn,$chats)
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App </title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" 
          crossorigin="anonymous">
    <link href="css/style.css"  rel="stylesheet">
    <link href="img/img.png"  rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet">
</head>
<body class="d-flex
             justify-content-center
             align-items-center
             vh-100">
    <div class="w-400 shadow  p-4 rounded">
      <a href="home.php"
         class="fs-4 link-dark">&#8592;</a>

      <div class="d-flex align-items-center">
            <img src="uploads/<?=$chatWith['p_p']?>"
                class="w-15 rounded-circle">  
            <h3 class="display-4 fs-sm m-2">
                <?=$chatWith['username']?> <br>
                <div class="d-flex
                            align-items-center"
                      title="online">

                      <?php
                       if(last_seen($chatWith['last_seen'])=="Active"){

                      
                      ?>
                    <div class="online"></div>
                    <small class="d-block p-1">Online </small>
                    <?php }else{
                    ?>

                        <small class="d-block p-1">
                            Last seen:
                          <?=last_seen($chatWith['last_seen'])?> 
                        </small>
                 
                    <?php
                    } ?>
                </div>
            </h3>
        </div>
        <div class="shadow p-4 rounded 
                    d-flex flex-column 
                    mt-2  chat-box"
              id="chatBox">

              <?php
                 if(!empty($chats)){ 
                    foreach($chats as $chat){
                        if($chat['from_id']==$_SESSION['user_id']){?>
                        <p class="rtext align-self-end
                                  border rounded p-2 mb-1">              
                         <?=$chat['message']?>
                         <small class="d-block">
                         <?=$chat['created_at']?>
                         </small>
                        </p>

                       <?php }else{?>
                        <p class="ltext  border 
                            rounded p-2 mb-1">            
                            <?=$chat['message']?>
                            <small class="d-block"> <?=$chat['created_at']?></small>
                        </p>   

                      <?php }  
                    }  
                 }else{?>
                <div class="alert  alert-info
                            text-center">
                     
                     <i class="fa fa-comments d-block fs-big"></i>
                     No message yet , Start conversation
               </div>

            <?php }?>
        </div>

        <div class="input-group mb-3">
            <Textarea cols="3"
                      class="form-control"  
                      id="message">

            </Textarea>
            <button class="btn btn-primary"
                     id="sendBtn">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
    
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

    var scrollDown = function(){
        let chatBox = document.getElementById('chatBox');
        chatBox.scrollTop=chatBox.scrollHeight; 
    }
    
    scrollDown();
   $(document).ready(function(){
      
    $("#sendBtn").on('click',function(){
        message = $("#message").val();
        if(message==""){
           return;
        }
        $.post("app/ajax/insert.php",
        {
           message:message,
           to_id:<?=$chatWith['user_id']?>,
        },
        function(data , status){
           
        $("#message").val("");
        $("#chatBox").append(data);
        scrollDown();

        });
    }); 
    
            //auto update last seen for logged in user
            let lastSeenUpdate = function(){
              $.get("app/ajax/update_last_seen.php" );
             }
            lastSeenUpdate();

            //auto update last seen every 10 seconds 
            setInterval(lastSeenUpdate,10000);
           
            //auto refresh / reload 
            let fetchData = function(){
                $.post("app/ajax/getMessage.php",
                {  
                    id_2:<?=$chatWith['user_id']?>
                },
                function(data,status){
                    $("#chatBox").append(data);
                    if(data != " " ) scrollDown();
                    
                } );
            }

            fetchData();
             //auto update chats every 0.5 seconds 
             setInterval(fetchData,500);
 });
</script>
</body>
</html>
<?php
 }else{
    header("Location:index.php");
    exit; 
 } 
?>