
<?php

session_start();

include("../Config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: /rwc/Login.php");

    exit();

}

if(!isset($_GET['id'])){

    header("Location: /rwc/dashboard.php");

    exit();

}

$post_id = $_GET['id'];

$user_id = $_SESSION['user_id'];

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

if(mysqli_num_rows($result) == 0){

    header("Location: /rwc/dashboard.php");

    exit();

}

$post = mysqli_fetch_assoc($result);

/* UPDATE */

if(isset($_POST['update_post'])){

    $title =
    mysqli_real_escape_string(
        $conn,
        $_POST['title']
    );

    $category =
    mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );

    $tags =
    mysqli_real_escape_string(
        $conn,
        $_POST['tags']
    );

    $content =
    mysqli_real_escape_string(
        $conn,
        $_POST['content']
    );

    $image_name = $post['image'];

    if(!empty($_FILES['image']['name'])){

        if(
        !empty($post['image'])
        &&
        file_exists(
        "../uploads/" . $post['image']
        )
        ){

            unlink(
                "../uploads/" .
                $post['image']
            );

        }

        $image_name =
        time() .
        "_" .
        $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/" . $image_name
        );

    }

    $video_name = $post['video'];

    if(!empty($_FILES['video']['name'])){

        if(
        !empty($post['video'])
        &&
        file_exists(
        "../uploads/" . $post['video']
        )
        ){

            unlink(
                "../uploads/" .
                $post['video']
            );

        }

        $video_name =
        time() .
        "_" .
        $_FILES['video']['name'];

        move_uploaded_file(
            $_FILES['video']['tmp_name'],
            "../uploads/" . $video_name
        );

    }

    $update = "
    UPDATE posts
    SET
    title='$title',
    category='$category',
    tags='$tags',
    content='$content',
    image='$image_name',
    video='$video_name'
    WHERE id='$post_id'
    ";

    mysqli_query(
        $conn,
        $update
    );

    header(
    "Location: single-post.php?id=$post_id"
    );

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Edit Blog</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

<style>

*{

    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:sans-serif;
}

body{

    background:
    #020617;

    color:white;

    min-height:100vh;
}

/* CONTAINER */

.edit-wrapper{

    width:100%;
    max-width:950px;

    margin:120px auto;

    padding:50px;

    border-radius:35px;

    background:
    rgba(255,255,255,.04);

    border:
    1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(20px);

    box-shadow:
    0 0 40px rgba(0,0,0,.35);
}

/* TITLE */

.edit-wrapper h1{

    font-size:55px;

    margin-bottom:35px;
}

/* FORM */

.edit-form{

    display:flex;

    flex-direction:column;

    gap:25px;
}

/* ROW */

.form-row{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:20px;
}

/* GROUP */

.form-group{

    display:flex;

    flex-direction:column;

    gap:12px;
}

/* LABEL */

.form-group label{

    color:#cbd5e1;

    font-size:15px;

    font-weight:600;
}

/* INPUT */

.form-group input,

.form-group textarea{

    width:100%;

    padding:18px 22px;

    border-radius:18px;

    border:
    1px solid rgba(255,255,255,.08);

    background:
    rgba(255,255,255,.05);

    color:white;

    font-size:16px;

    outline:none;
}

/* TEXTAREA */

.form-group textarea{

    min-height:250px;

    resize:none;
}

/* FILE */

.form-group input[type="file"]{

    padding:16px;
}

/* BUTTON */

.update-btn{

    height:60px;

    border:none;

    border-radius:18px;

    background:
    linear-gradient(
    45deg,
    #00e5ff,
    #d400ff
    );

    color:white;

    font-size:18px;

    font-weight:700;

    cursor:pointer;

    transition:.4s;
}

.update-btn:hover{

    transform:translateY(-3px);
}

/* RESPONSIVE */

@media(max-width:768px){

    .edit-wrapper{

        padding:25px;
    }

    .form-row{

        grid-template-columns:1fr;
    }

    .edit-wrapper h1{

        font-size:38px;
    }

}

</style>

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="edit-wrapper">

<h1>

Edit Blog

</h1>

<form
method="POST"
enctype="multipart/form-data"
class="edit-form"
>

<div class="form-group">

<label>

Post Title

</label>

<input
type="text"
name="title"
value="<?php echo $post['title']; ?>"
required
>

</div>

<div class="form-row">

<div class="form-group">

<label>

Category

</label>

<input
type="text"
name="category"
value="<?php echo $post['category']; ?>"
required
>

</div>

<div class="form-group">

<label>

Tags

</label>

<input
type="text"
name="tags"
value="<?php echo $post['tags']; ?>"
required
>

</div>

</div>

<div class="form-group">

<label>

Blog Content

</label>

<textarea
name="content"
required
><?php echo $post['content']; ?></textarea>

</div>

<div class="form-group">

<label>

Change Cover Image

</label>

<input
type="file"
name="image"
>

</div>

<div class="form-group">

<label>

Change Video / Audio

</label>

<input
type="file"
name="video"
>

</div>

<button
type="submit"
name="update_post"
class="update-btn"
>

Update Blog

</button>

</form>

</div>

</body>

</html>
