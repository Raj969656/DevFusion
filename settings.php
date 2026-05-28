<?php

session_start();

include("Config/db.php");

$user_id = $_SESSION['user_id'];

if(isset($_POST['update'])){

    $username = $_POST['username'];

    $bio = $_POST['bio'];

    mysqli_query(
    $conn,
    "
    UPDATE users
    SET
    username='$username',
    bio='$bio'
    WHERE id='$user_id'
    "
    );

    $_SESSION['username'] =
    $username;

}

$user_query = "
SELECT *
FROM users
WHERE id='$user_id'
";

$user_result = mysqli_query(
$conn,
$user_query
);

$user =
mysqli_fetch_assoc($user_result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>

Settings

</title>

<link rel="stylesheet" href="dashboard.css">

</head>

<body>

<div class="page-container">

<h1>

Settings

</h1>

<form method="POST" class="settings-form">

<input
type="text"
name="username"
value="<?php echo $user['username']; ?>"
placeholder="Username"
>

<textarea
name="bio"
placeholder="Bio"
>

<?php echo $user['bio']; ?>

</textarea>

<button
type="submit"
name="update"
>

Update Profile

</button>

</form>

</div>

</body>
</html>