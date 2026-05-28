
<?php

session_start();

include("../Config/db.php");

/* LOGIN CHECK */

if(!isset($_SESSION['user_id'])){

    exit();

}

if(!isset($_POST['post_id'])){

    exit();

}

$user_id = $_SESSION['user_id'];

$post_id = $_POST['post_id'];

/* CHECK ALREADY LIKED */

$check_query = "
SELECT *
FROM likes
WHERE user_id='$user_id'
AND post_id='$post_id'
";

$check_result =
mysqli_query(
$conn,
$check_query
);

/* INSERT LIKE */

if(mysqli_num_rows($check_result) == 0){

    $insert = "
    INSERT INTO likes(
    user_id,
    post_id
    )
    VALUES(
    '$user_id',
    '$post_id'
    )
    ";

    mysqli_query(
        $conn,
        $insert
    );

}

/* TOTAL COUNT */

$count_query = "
SELECT COUNT(*) as total
FROM likes
WHERE post_id='$post_id'
";

$count_result =
mysqli_query(
$conn,
$count_query
);

$count_row =
mysqli_fetch_assoc(
$count_result
);

/* RETURN COUNT */

echo $count_row['total'];

?>
