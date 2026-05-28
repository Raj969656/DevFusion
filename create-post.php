<?php

session_start();

include("Config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: Login.php");

    exit();

}

/* CREATE POST */

if(isset($_POST['create_post'])){
    /* VALIDATION */

$errors = [];

/* TITLE */

if(
empty(
trim($_POST['title'])
)
){

$errors[] =
"Post title is required";

}

/* CATEGORY */

if(
empty(
trim($_POST['category'])
)
){

$errors[] =
"Category is required";

}

/* TAGS */

if(
empty(
trim($_POST['tags'])
)
){

$errors[] =
"Tags are required";

}

/* CONTENT */

if(
empty(
trim($_POST['content'])
)
){

$errors[] =
"Blog content is required";

}

/* TITLE LENGTH */

if(
strlen(
trim($_POST['title'])
)
< 5
){

$errors[] =
"Title must be at least 5 characters";

}

/* CONTENT LENGTH */

if(
strlen(
trim($_POST['content'])
)
< 20
){

$errors[] =
"Content too short";

}

/* IMAGE OR VIDEO */

if(

empty($_FILES['image']['name'])
&&
empty($_FILES['video']['name'])

){

$errors[] =
"Upload image or video";

}

/* IMAGE VALIDATION */

if(
!empty($_FILES['image']['name'])
){

$image_ext =
strtolower(
pathinfo(
$_FILES['image']['name'],
PATHINFO_EXTENSION
));

$allowed_images = [

'jpg',
'jpeg',
'png',
'webp'

];

if(
!in_array(
$image_ext,
$allowed_images
)
){

$errors[] =
"Only JPG PNG WEBP images allowed";

}

if(
$_FILES['image']['size']
>
5 * 1024 * 1024
){

$errors[] =
"Image size must be under 5MB";

}

}

/* VIDEO VALIDATION */

if(
!empty($_FILES['video']['name'])
){

$video_ext =
strtolower(
pathinfo(
$_FILES['video']['name'],
PATHINFO_EXTENSION
));

$allowed_media = [

'mp4',
'webm',
'mov',
'mp3',
'wav',
'ogg'

];

if(
!in_array(
$video_ext,
$allowed_media
)
){

$errors[] =
"Invalid video/audio format";

}

if(
$_FILES['video']['size']
>
50 * 1024 * 1024
){

$errors[] =
"Media size must be under 50MB";

}

}

/* SHOW ERRORS */

if(
count($errors) > 0
){

$error_message =
implode(
"\\n",
$errors
);

echo "

<script>

alert('$error_message');

window.history.back();

</script>

";

exit();

}
/* VALIDATION */

$errors = [];

/* TITLE */

if(
empty(
trim($_POST['title'])
)
){

$errors[] =
"Post title is required";

}

/* CONTENT */

if(
empty(
trim($_POST['content'])
)
){

$errors[] =
"Post content is required";

}

/* CATEGORY */

if(
empty(
trim($_POST['category'])
)
){

$errors[] =
"Please select category";

}

/* IMAGE OR VIDEO REQUIRED */

if(

empty($_FILES['image']['name'])
&&
empty($_FILES['video']['name'])

){

$errors[] =
"Upload at least image or video";

}

/* TITLE LENGTH */

if(
strlen($_POST['title']) < 5
){

$errors[] =
"Title too short";

}

/* CONTENT LENGTH */

if(
strlen($_POST['content']) < 20
){

$errors[] =
"Content too short";

}

/* IMAGE VALIDATION */

if(
!empty($_FILES['image']['name'])
){

$image_ext =
strtolower(
pathinfo(
$_FILES['image']['name'],
PATHINFO_EXTENSION
));

$allowed_images = [
'jpg',
'jpeg',
'png',
'webp'
];

if(
!in_array(
$image_ext,
$allowed_images
)
){

$errors[] =
"Only JPG PNG WEBP allowed";

}

/* IMAGE SIZE */

if(
$_FILES['image']['size']
>
5 * 1024 * 1024
){

$errors[] =
"Image size must be under 5MB";

}

}

/* VIDEO VALIDATION */

if(
!empty($_FILES['video']['name'])
){

$video_ext =
strtolower(
pathinfo(
$_FILES['video']['name'],
PATHINFO_EXTENSION
));

$allowed_videos = [
'mp4',
'webm',
'mov',
'mp3',
'wav',
'ogg'
];

if(
!in_array(
$video_ext,
$allowed_videos
)
){

$errors[] =
"Invalid media format";

}

/* VIDEO SIZE */

if(
$_FILES['video']['size']
>
50 * 1024 * 1024
){

$errors[] =
"Media size must be under 50MB";

}

}

/* STOP IF ERROR */

if(
count($errors) > 0
){

$error_message =
implode(
"\\n",
$errors
);

echo "

<script>

alert('$error_message');

window.history.back();

</script>

";

exit();

}
    $title =
    mysqli_real_escape_string(
    $conn,
    $_POST['title']
    );

    $content =
    mysqli_real_escape_string(
    $conn,
    $_POST['content']
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

    $user_id =
    $_SESSION['user_id'];

    /* IMAGE */

    $image_name = "";

    if(
    isset($_FILES['image'])
    &&
    $_FILES['image']['name']
    != ""
    ){

        $image_name =
        time() .
        "_" .
        basename(
        $_FILES['image']['name']
        );

        $temp_name =
        $_FILES['image']['tmp_name'];

        move_uploaded_file(
        $temp_name,
        "uploads/" . $image_name
        );

    }

    /* VIDEO */

    $video_name = "";

    if(
    isset($_FILES['video'])
    &&
    $_FILES['video']['name']
    != ""
    ){

        $video_name =
        time() .
        "_" .
        basename(
        $_FILES['video']['name']
        );

        $video_temp =
        $_FILES['video']['tmp_name'];

        move_uploaded_file(
        $video_temp,
        "uploads/" . $video_name
        );

    }

    /* INSERT */

    $sql = "
    INSERT INTO posts(
        user_id,
        title,
        content,
        image,
        video,
        category,
        tags
    )
    VALUES(
        '$user_id',
        '$title',
        '$content',
        '$image_name',
        '$video_name',
        '$category',
        '$tags'
    )
    ";

    $result =
    mysqli_query(
    $conn,
    $sql
    );

    if($result){

        echo "

        <script>

        alert('Post Created Successfully');

        window.location.href='dashboard.php';

        </script>

        ";

    }else{

        die(
        mysqli_error($conn)
        );

    }

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

<title>

Create Post

</title>

<link
rel="stylesheet"
href="style.css"
>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

<style>

/* BODY */

body{

    background:#020617;

    overflow-x:hidden;
}

/* BACKGROUND */

.bg-animation{

    position:fixed;

    inset:0;

    overflow:hidden;

    z-index:-1;
}

.bg-animation span{

    position:absolute;

    width:400px;

    height:400px;

    border-radius:50%;

    filter:blur(120px);

    opacity:.25;
}

.bg-animation span:nth-child(1){

    background:#00f0ff;

    top:-100px;

    left:-100px;
}

.bg-animation span:nth-child(2){

    background:#ff00f7;

    bottom:-100px;

    right:-100px;
}

.bg-animation span:nth-child(3){

    background:#7c3aed;

    top:50%;

    left:50%;

    transform:translate(-50%,-50%);
}

/* CONTAINER */

.create-container{

    width:100%;

    max-width:1400px;

    margin:auto;

    padding:50px 5%;
}

/* CREATE BOX */

.create-box{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,.08),
    rgba(255,255,255,.03)
    );

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(18px);

    border-radius:40px;

    padding:50px;

    box-shadow:
    0 20px 80px rgba(0,0,0,.45);
}

/* HEADER */

.create-header{

    margin-bottom:40px;
}

.create-header h1{

    font-size:55px;

    color:white;

    margin-bottom:15px;
}

.create-header p{

    color:#94a3b8;

    font-size:18px;
}

/* FORM */

.create-form{

    display:flex;

    flex-direction:column;

    gap:28px;
}

/* INPUT GROUP */

.input-group{

    display:flex;

    flex-direction:column;

    gap:12px;
}

.input-group label{

    color:white;

    font-size:15px;

    font-weight:600;
}

/* INPUTS */

.input-group input,
.input-group textarea,
.input-group select{

    width:100%;

    padding:18px 22px;

    border:none;

    outline:none;

    border-radius:20px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.08);

    color:white;

    font-size:16px;
}

.input-group textarea{

    min-height:300px;

    resize:none;

    line-height:32px;
}

/* FILE UPLOAD */

.upload-box{

    width:100%;

    padding:40px;

    border-radius:25px;

    border:2px dashed rgba(255,255,255,.15);

    background:rgba(255,255,255,.04);

    text-align:center;

    transition:.4s;
}

.upload-box:hover{

    border-color:#00f0ff;

    background:rgba(255,255,255,.06);
}

.upload-box i{

    font-size:60px;

    color:#00f0ff;

    margin-bottom:20px;
}

.upload-box p{

    color:#cbd5e1;

    margin-bottom:20px;
}

.upload-box input{

    margin-top:15px;
}

/* GRID */

.grid-2{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:25px;
}

/* TAGS */

.tags-preview{

    display:flex;

    flex-wrap:wrap;

    gap:12px;

    margin-top:15px;
}

.tag{

    padding:10px 16px;

    border-radius:14px;

    background:
    linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    font-size:14px;
}

/* PREVIEW */

.preview-box{

    margin-top:20px;

    border-radius:25px;

    overflow:hidden;

    background:rgba(255,255,255,.05);

    border:1px solid rgba(255,255,255,.08);
}

.preview-box img,
.preview-box video{

    width:100%;

    max-height:500px;

    object-fit:cover;
}

/* BUTTONS */

.action-buttons{

    display:flex;

    gap:20px;

    flex-wrap:wrap;

    margin-top:15px;
}

.publish-btn,
.draft-btn{

    padding:18px 35px;

    border:none;

    border-radius:18px;

    cursor:pointer;

    font-size:16px;

    transition:.4s;
}

.publish-btn{

    background:
    linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    box-shadow:
    0 0 35px rgba(0,240,255,.25);
}

.publish-btn:hover{

    transform:translateY(-5px);
}

.draft-btn{

    background:rgba(255,255,255,.08);

    border:1px solid rgba(255,255,255,.08);

    color:white;
}

/* COUNTER */

.char-counter{

    color:#94a3b8;

    text-align:right;

    font-size:14px;
}

/* RESPONSIVE */

@media(max-width:900px){

    .grid-2{

        grid-template-columns:1fr;
    }

    .create-header h1{

        font-size:38px;
    }

    .create-box{

        padding:30px;
    }

}

</style>

</head>

<body>

<!-- BACKGROUND -->

<div class="bg-animation">

<span></span>
<span></span>
<span></span>

</div>

<!-- NAVBAR -->

<?php include("includes/navbar.php"); ?>

<!-- MAIN -->

<div class="create-container">

<div class="create-box">

<!-- HEADER -->

<div class="create-header">

<h1>

Create New Blog

</h1>

<p>

Write immersive stories,
share ideas and publish
premium content.

</p>

</div>

<!-- FORM -->

<form
method="POST"
enctype="multipart/form-data"
class="create-form"
>

<!-- TITLE -->

<div class="input-group">

<label>

Post Title

</label>

<input
type="text"
name="title"
id="title"
placeholder="Enter a powerful blog title..."
required
>

</div>

<!-- CATEGORY + TAGS -->

<div class="grid-2">

<div class="input-group">

<label>

Category

</label>

<select
name="category"
required
>

<option value="">

Select Category

</option>

<option>

Technology

</option>

<option>

AI

</option>

<option>

Web Development

</option>

<option>

Finance

</option>

<option>

Gaming

</option>

<option>

Lifestyle

</option>

</select>

</div>

<div class="input-group">

<label>

Tags

</label>

<input
type="text"
name="tags"
id="tags"
placeholder="#react #php #ai"
>

<div
class="tags-preview"
id="tagsPreview"
></div>

</div>

</div>

<!-- CONTENT -->

<div class="input-group">

<label>

Blog Content

</label>

<textarea
name="content"
id="content"
placeholder="
Write your story...
"
required
></textarea>

<div
class="char-counter"
id="charCounter"
>

0 Characters

</div>

</div>

<!-- IMAGE -->

<div class="input-group">

<label>

Cover Image

</label>

<div class="upload-box">

<i class="ri-image-2-line"></i>

<p>

Upload high quality cover image

</p>

<input
type="file"
name="image"
accept="image/*"
id="imageInput"
>

</div>

<div
class="preview-box"
id="imagePreview"
style="display:none;"
></div>

</div>

<!-- VIDEO -->

<div class="input-group">

<label>

Video Upload

</label>

<div class="upload-box">

<i class="ri-video-upload-line"></i>

<p>

Upload cinematic reel or vlog

</p>

<input
type="file"
name="video"
accept="video/*"
id="videoInput"
>

</div>

<div
class="preview-box"
id="videoPreview"
style="display:none;"
></div>

</div>

<!-- ACTIONS -->

<div class="action-buttons">

<button
type="submit"
name="create_post"
class="publish-btn"
>

Publish Post

</button>

<button
type="button"
class="draft-btn"
>

Save Draft

</button>

</div>

</form>

</div>

</div>

<script>

/* CHARACTER COUNTER */

const content =
document.getElementById(
"content"
);

const counter =
document.getElementById(
"charCounter"
);

content.addEventListener(
"input",
()=>{

counter.innerText =
content.value.length +
" Characters";

});

/* TAGS */

const tagsInput =
document.getElementById(
"tags"
);

const tagsPreview =
document.getElementById(
"tagsPreview"
);

tagsInput.addEventListener(
"keyup",
()=>{

let tags =
tagsInput.value.split(" ");

tagsPreview.innerHTML = "";

tags.forEach(tag=>{

if(tag.trim() != ""){

let span =
document.createElement(
"span"
);

span.classList.add(
"tag"
);

span.innerText = tag;

tagsPreview.appendChild(
span
);

}

});

});

/* IMAGE PREVIEW */

const imageInput =
document.getElementById(
"imageInput"
);

const imagePreview =
document.getElementById(
"imagePreview"
);

imageInput.addEventListener(
"change",
function(){

const file =
this.files[0];

if(file){

const reader =
new FileReader();

reader.onload =
function(e){

imagePreview.style.display =
"block";

imagePreview.innerHTML =

`<img src="${e.target.result}">`;

}

reader.readAsDataURL(
file
);

}

});

/* VIDEO PREVIEW */

const videoInput =
document.getElementById(
"videoInput"
);

const videoPreview =
document.getElementById(
"videoPreview"
);

videoInput.addEventListener(
"change",
function(){

const file =
this.files[0];

if(file){

const url =
URL.createObjectURL(file);

videoPreview.style.display =
"block";

videoPreview.innerHTML =

`
<video
src="${url}"
controls
autoplay
muted
></video>
`;

}

});

</script>

</body>

</html>