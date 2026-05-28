<?php

session_start();

include("Config/db.php");

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>

Statistics

</title>

<link rel="stylesheet" href="dashboard.css">

</head>

<body>

<div class="page-container">

<h1>

Statistics

</h1>

<div class="cards">

<?php

$stats = [

"Posts" => "
SELECT *
FROM posts
WHERE user_id='$user_id'
",

"Views" => "
SELECT SUM(views)
AS total
FROM posts
WHERE user_id='$user_id'
",

"Likes" => "
SELECT SUM(likes_count)
AS total
FROM posts
WHERE user_id='$user_id'
"

];

foreach($stats as $title => $query){

$result = mysqli_query($conn,$query);

$data = mysqli_fetch_assoc($result);

$value =
$data['total']
?? mysqli_num_rows($result);

?>

<div class="card">

<h2>

<?php echo $value; ?>

</h2>

<p>

<?php echo $title; ?>

</p>

</div>

<?php } ?>

</div>

</div>

</body>
</html>