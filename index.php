<?php
session_start();
if(!isset($_SESSION['username'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App - Login</title>
     <!-- CSS only -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" 
      crossorigin="anonymous">
      <link href="css/style.css" 
            rel="stylesheet">
     <link href="img/img.png"  rel="icon">
</head>
<body class="d-flex
             justify-content-center
             align-items-center
             vh-100">
    <div class="w-400 p-5 shadow rounded">

        <form method="post" action="app/http/auth.php">
            
            <div class="d-flex
                        justify-content-center
                        align-items-center
                        flex-column">
                <img src="img/image.png"
                     class="w-25">

                <h3 class="display-4 
                           fs-1 
                           text-center">
                           LOGIN
                </h3>
            </div>
            <?php if(isset($_GET['error'])){?>
            <div class="alert  alert-warning"
                 role="alert">
                 <?php echo htmlspecialchars($_GET['error']);?>
            </div>
            <?php } ?>
            <?php if(isset($_GET['success'])){?>
            <div class="alert  alert-success"
                 role="alert">
                 <?php echo htmlspecialchars($_GET['success']);?>
            </div>
            <?php } ?>

            <div class="mb-3">
                <label  class="form-label">
                        User Name</label>
                <input type="text"
                       class="form-control"
                       name="username">
            </div> 

            <div class="mb-3">
                <label  class="form-label">
                              Password</label>
                <input type="password"
                       class="form-control" 
                       name="password">
            </div>
                
            <button type="submit" 
                    class="btn btn-primary">
                    LOGIN</button>

            <a href="signup.php">Sign Up</a>
        </form>
    </div>

  <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" 
crossorigin="anonymous"></script>  
</body>
</html>
<?php
 }else{
    header("Location:home.php");
    exit;
 }
?>