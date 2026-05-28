<?php

session_start();

include("Config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: auth/Login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

$query = "
SELECT comments.*, posts.title
FROM comments
JOIN posts
ON comments.post_id = posts.id
WHERE posts.user_id='$user_id'
ORDER BY comments.id DESC
";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>

Comments

</title>

<link rel="stylesheet" href="dashboard.css">

</head>

<body>

<div class="page-container">

<h1>

Comments

</h1>

<div class="recent-posts">

<table>

<tr>

<th>User Comment</th>
<th>Post</th>
<th>Date</th>

</tr>

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php
echo $row['comment'];
?>

</td>

<td>

<?php
echo $row['title'];
?>

</td>

<td>

<?php
echo $row['created_at'];
?>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>