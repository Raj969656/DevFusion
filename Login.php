<?php

session_start();

include("Config/db.php");

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
            WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify(
            $password,
            $row['password']
        )){

            $_SESSION['user_id'] = $row['id'];

            $_SESSION['username'] =
            $row['username'];

          echo "

<script>

alert('Login Successful');

window.location.href='/rindex.php';

</script>

";
        }else{

            echo "Wrong Password";

        }

    }else{

        echo "User Not Found";

    }
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

<link rel="stylesheet" href="style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

</head>

<body>

<div class="bg-animation">
<span></span>
<span></span>
<span></span>
</div>

<?php include("includes/navbar.php"); ?>

<section class="container">

<div class="login-box">

<h2>Login</h2>

<form method="POST">

<div class="input-box">

<i class="ri-mail-line"></i>

<input
type="email"
name="email"
placeholder="Enter Email"
required
>

</div>

<div class="input-box">

<i class="ri-lock-password-line"></i>

<input
type="password"
name="password"
placeholder="Enter Password"
required
>

</div>

<button
type="submit"
name="login"
class="login-btn"
>
    Login Now
</button>

<p class="register">

Don't have account?

<a href="register.php">
Register
</a>

</p>

</form>

</div>

</section>

</body>
</html>