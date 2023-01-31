<?php
session_start();
# check if  username & password submitted

if( isset($_POST['username'])&&
    isset($_POST['password'])){

    #database connect file
    include '../db.conn.php';
    #get data from post request and store them in var
    $username=$_POST['username'];
    $password=$_POST['password'];
    #simple form validation 
    if(empty($username)){
        #error message
        $em="UserName is required";

        #redirect to 'index.php' and passing error message and data
        header("Location: ../../index.php?error=$em");
        exit;
  } else if(empty($password)){
     #error message
     $em="password is required";

     #redirect to 'index.php' and passing error message and data
     header("Location: ../../index.php?error=$em");
     exit;
  }else{
    $sql="SELECT * FROM 
          users WHERE username=?";
    $stmt =$conn->prepare($sql);
    $stmt->execute([$username]);
    #if the username is exist
    if($stmt->rowCount() ===1){
        #fetching user data
        $user = $stmt->fetch();

        #if both username are strictly equal
        if($user['username']===$username){

            #verifying the encrypted password 
            if(password_verify($password , $user['password'])){
                #successfully logged in
                #creating the session 
                $_SESSION['username']=$user['username'];
                $_SESSION['name']=$user['name'];
                $_SESSION['user_id']=$user['user_id'];

                #redirect to 'home.php'
                header("Location: ../../home.php");

            }else{
                 #error message
                 $em="Incorrect username or password ";

                 #redirect to 'index.php' and passing error message and data
                 header("Location: ../../index.php?error=$em");
                 exit;
            }

        }else{
            #error message
            $em="Incorrect username or password ";

            #redirect to 'index.php' and passing error message and data
            header("Location: ../../index.php?error=$em");
            exit;
        } 
    }
    else{
        #error message
        $em="username is not exist ";

        #redirect to 'index.php' and passing error message and data
        header("Location: ../../index.php?error=$em");
        exit;
    } 

  }
}else{
    header("Location: ../../index.php");
    exit;
}
