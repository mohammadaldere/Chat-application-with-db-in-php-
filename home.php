<?php
session_start();
if(isset($_SESSION['username'])){
    #database connection file
    include 'app/db.conn.php';

    include 'app/helpers/user.php';

    include 'app/helpers/conversations.php'; 

    include 'app/helpers/timeAgo.php'; 

    include 'app/helpers/last_chat.php'; 

    #Getting user data 

    $user = getUser($_SESSION['username'],$conn);

      #Getting user conversations

      $conversations = getConversations($user['user_id'],$conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App - Home</title>
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
    <div class="p-2 w-400
                rounded shadow">

            <div>
                <div class="d-flex
                            mb-3 p-3 bg-light
                            justify-content-between
                            align-items-center">
                    <div class="d-flex
                                align-items-center">
                        <img src="uploads/<?=$user['p_p']?>"
                             class="w-25 rounded-circle">
                        <h3 class="fs-xs m-2"><?=$user['username']?></h3>
                    </div>
                    <a href="logout.php"
                       class="btn btn-dark">Logout</a>
     
                </div> 

                <div class="input-group mb-3">
                     <input type="text"
                            placeholder="Search..."
                            id="searchText"
                            class="form-control">
                     <button class="btn btn-primary" 
                             id="searchBtn">
                        <i class="fa fa-search"></i>
                     </button>
                </div>
                <ul id = "chatList"
                    class="list-group mvh-50 overflow-auto">
                    <?php if(!empty($conversations)){?>
                       
                    <?php foreach($conversations as $conversation){?>

                  
                    <li class="list-group-item">
                        <a href="chat.php?user=<?=$conversation['username']?>"
                           class="d-flex
                                  justify-content-between
                                  align-items-center p-2">
                           <div class="d-flex
                                       align-items-center">
                                <img src="uploads/<?=$conversation['p_p']?>"
                                           class="w-10 rounded-circle">
                                <h3 class="fs-xs m-2">
                                    <?=$conversation['username']?><br> 
                                    <small>
                                        <?php
                                        echo lastChat($_SESSION['user_id'],
                                                      $conversation['user_id'],$conn)
                                        ?>
                                    </small>
                                </h3>
                            </div>
                            <?php if(last_seen($conversation['last_seen'])=="Active"){?>
                          <div title="online">
                            <div class="online">

                            </div>
                          </div>
                          <?php }?>
                        </a>
                    </li>
                <?php } ?>
               <?php }else{?>
                <div class="alert  alert-info
                            text-center">
                     
                     <i class="fa fa-comments d-block fs-big"></i>
                     No message yet , Start conversation
               </div>
               <?php }?>
                </ul>
            </div>

    </div>
     

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

 <script>
    $(document).ready(function(){
       
        //search 
        $("#searchText").on("input",function(){
              var searchText = $(this).val();
              if(searchText=="")return;
              $.post('app/ajax/search.php',
              {
                key:searchText
              },
              function(data,status){
                $("#chatList").html(data);
              });
        });
         //search by button
         $("#searchBtn").on("click",function(){
              var searchText = $("#searchText").val();
              if(searchText=="")return;
              $.post('app/ajax/search.php',
              {
                key:searchText
              },
              function(data,status){
                $("#chatList").html(data);
              });
        });
        //auto update last seen for logged in user
        let lastSeenUpdate = function(){
            $.get("app/ajax/update_last_seen.php" );
        }
          lastSeenUpdate();

          //auto update last seen every 10 seconds 
          setInterval(lastSeenUpdate,10000);
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