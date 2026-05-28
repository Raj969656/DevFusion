<?php

include("Config/db.php");

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $sql = "INSERT INTO users(
                username,
                email,
                password
            )
            VALUES(
                '$username',
                '$email',
                '$hashed_password'
            )";

    $result = mysqli_query($conn, $sql);

    if($result){
        header("Location: Login.php");
    }else{
        echo "Registration Failed";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Register</title>

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

<h2>Create Account</h2>

<form method="POST">

<div class="input-box">

<i class="ri-user-line"></i>

<input
type="text"
name="username"
placeholder="Username"
required
>

</div>

<div class="input-box">

<i class="ri-mail-line"></i>

<input
type="email"
name="email"
placeholder="Email"
required
>

</div>

<div class="input-box">

<i class="ri-lock-password-line"></i>

<input
type="password"
name="password"
placeholder="Password"
required
>

</div>

<button
type="submit"
name="register"
class="login-btn"
>
    Register
</button>

<p class="register">

Already have account?

<a href="Login.php">
Login
</a>

</p>

</form>

</div>

</section>

</body>
</html>