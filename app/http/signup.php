<?php

# check if name , username , password submitted

if( isset($_POST['username'])&&
    isset($_POST['name'])&&
    isset($_POST['password'])){

    #database connect file
    include '../db.conn.php';
    #get data from post request and store them in var
    $name=$_POST['name'];
    $password=$_POST['password'];
    $username=$_POST['username']; 
    #making URL data format
    $data = 'name='.$name.'&username='.$username.'';

    #simple form validation
    if(empty($name)){
        #error message
        $em="Name is required";

        #redirect to 'signup.php' and passing error message and data
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    }else if(empty($username)){
            #error message
            $em="UserName is required";
    
            #redirect to 'signup.php' and passing error message and data
            header("Location: ../../signup.php?error=$em&$data");
            exit;

    } else if(empty($password)){
           #error message
           $em="password is required";

           #redirect to 'signup.php' and passing error message and data
           header("Location: ../../signup.php?error= $em & $data ");
           exit;

}else{
    //to know if username exists
    $sql="SELECT username
          From   users
          WHERE  username=?";
    $stmt = $conn->prepare($sql);
    $stmt -> execute([$username]);
    if($stmt->rowCount()>0){
        $em ="the username ($username) is taken";
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    }
    else{
        # Profile Picture Uploading
        if(isset($_FILES['pp'])){
            # get data and store them in var
            $img_name = $_FILES['pp']['name'];  
            $tmp_name = $_FILES['pp']['tmp_name']; 
            $error = $_FILES['pp']['error'];  
            
            #if there is not error occurred while uploading 
            if($error===0){
                #get image extension store it in var
                $img_ex = pathinfo($img_name,PATHINFO_EXTENSION);

                #convert the image extension into lower case 
                # and store it in var
                $img_ex_lc=strtolower($img_ex);

                #creating arrays that stores allowed
                #to upload image extension

                $allowed_exc=array("jpg","jpeg","png");

                #check if  the image extension is present in $allowed 
                #is present in $allowed_exs array
                if(in_array($img_ex_lc,$allowed_exc)){
                #renaming the image with users username 
                #like: username.$img_ex_lc
                $new_img_name=$username. '.' .$img_ex_lc;

                #creating upload path on root directory
                $img_upload_path = '../../uploads/'.$new_img_name;

                #move uploaded image to  ./upload folder
                move_uploaded_file($tmp_name,$img_upload_path);
                }else{
                    $em ="you cant upload files of this type";
                    header("Location: ../../signup.php?error=$em&$data");
                    exit;
                }

            }
            
          }  
          //password hashing
          $password = password_hash($password,PASSWORD_DEFAULT)   ;
          //if the user profile picture
          if(isset($new_img_name)){
          //inserting data in to database
           $sql="INSERT INTO users
                 (name,username,password,p_p)
                 VALUES(?,?,?,?)";
           $stmt=$conn->prepare($sql);
           $stmt->execute([$name,$username,$password,$new_img_name]);
          }else{
           //inserting data in to database
           $sql="INSERT INTO users
                 (name,username,password)
                 VALUES(?,?,?)";
           $stmt=$conn->prepare($sql);
           $stmt->execute([$name,$username,$password]);
          }
          //success message
          $sm="Account created successfully";
          //redirect  to 'index.php ' and passing success message
          header("Location: ../../index.php?success=$sm");
          exit;

         } 

        }
    }
  else{
          header("Location: ../../signup.php");
          exit;
   }