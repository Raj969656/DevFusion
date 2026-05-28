
<?php

session_start();

include("../Config/db.php");

/* LOGIN CHECK */

if(!isset($_SESSION['user_id'])){

    header("Location: /Login.php");

    exit();

}

/* ID CHECK */

if(!isset($_GET['id'])){

    header("Location: /dashboard.php");

    exit();

}

$post_id = $_GET['id'];

$user_id = $_SESSION['user_id'];

/* FETCH POST */

$query = "
SELECT *
FROM posts
WHERE id='$post_id'
AND user_id='$user_id'
";

$result = mysqli_query(
    $conn,
    $query
);

/* POST EXISTS ? */

if(mysqli_num_rows($result) == 0){

    header("Location: /dashboard.php");

    exit();

}

$post = mysqli_fetch_assoc($result);

/* DELETE IMAGE */

if(
!empty($post['image'])
&&
file_exists(
"../uploads/" . $post['image']
)
){

    unlink(
        "../uploads/" . $post['image']
    );

}

/* DELETE VIDEO */

if(
!empty($post['video'])
&&
file_exists(
"../uploads/" . $post['video']
)
){

    unlink(
        "../uploads/" . $post['video']
    );

}

/* DELETE COMMENTS */

mysqli_query(
    $conn,
    "DELETE FROM comments
    WHERE post_id='$post_id'"
);

/* DELETE LIKES */

mysqli_query(
    $conn,
    "DELETE FROM likes
    WHERE post_id='$post_id'"
);

/* DELETE POST */

mysqli_query(
    $conn,
    "DELETE FROM posts
    WHERE id='$post_id'
    AND user_id='$user_id'"
);

/* REDIRECT */

header("Location: /dashboard.php");

exit();

?>
