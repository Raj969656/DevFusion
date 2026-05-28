<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../Config/db.php");

/* CHECK POST ID */

if(!isset($_GET['id'])){

    die("Post ID Missing");

}

$post_id = $_GET['id'];

/* LIKE SYSTEM */

if(isset($_POST['like_post'])){

    if(!isset($_SESSION['user_id'])){

        header("Location: /Login.php");

        exit();

    }

    $user_id = $_SESSION['user_id'];

    $check_like = "
    SELECT *
    FROM likes
    WHERE post_id='$post_id'
    AND user_id='$user_id'
    ";

    $check_result = mysqli_query(
        $conn,
        $check_like
    );

    if(mysqli_num_rows($check_result) == 0){

        mysqli_query(
            $conn,
            "INSERT INTO likes(
                post_id,
                user_id
            )
            VALUES(
                '$post_id',
                '$user_id'
            )"
        );

        mysqli_query(
            $conn,
            "UPDATE posts
            SET likes_count =
            likes_count + 1
            WHERE id='$post_id'"
        );

    }

    header(
        "Location: single-post.php?id=$post_id"
    );

    exit();

}


/* COMMENT SYSTEM */

if(isset($_POST['post_comment'])){

    if(!isset($_SESSION['user_id'])){

        header("Location: /Login.php");

        exit();

    }

    $comment = trim($_POST['comment']);

    $user_id = $_SESSION['user_id'];

    if(!empty($comment)){

        mysqli_query(
            $conn,
            "INSERT INTO comments(
                post_id,
                user_id,
                comment
            )
            VALUES(
                '$post_id',
                '$user_id',
                '$comment'
            )"
        );

    }

    header(
        "Location: single-post.php?id=$post_id"
    );

    exit();

}



/* UPDATE VIEWS */

mysqli_query(
    $conn,
    "UPDATE posts
    SET views = views + 1
    WHERE id='$post_id'"
);

/* FETCH POST */

$sql = "

SELECT posts.*,

users.username,

(
SELECT COUNT(*)
FROM likes
WHERE likes.post_id = posts.id
) AS likes_count

FROM posts

JOIN users
ON posts.user_id = users.id

WHERE posts.id='$post_id'";


$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){

    die("Post Not Found");

}

$post = mysqli_fetch_assoc($result);

/* READING TIME */

$word_count =
str_word_count(strip_tags($post['content']));

$reading_time =
ceil($word_count / 200);

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

<?php echo $post['title']; ?>

</title>

<link rel="stylesheet" href="../style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

<style>

/* GLOBAL */
/* HERO */

.hero-media{

    position:relative;

    width:100%;

    height:650px;

    border-radius:40px;

    overflow:hidden;

    margin-bottom:40px;

    background:black;
}

/* IMAGE */


/* VIDEO */

.hero-video{

    width:100%;

    height:100%;

    object-fit:cover;

    background:black;
}

/* OVERLAY */

.hero-overlay{

    position:absolute;

    inset:0;

    background:
    linear-gradient(
    to top,
    rgba(0,0,0,.85),
    rgba(0,0,0,.2),
    transparent
    );
}

/* PLAY BUTTON */

.play-overlay{

    position:absolute;

    top:50%;

    left:50%;

    transform:translate(-50%,-50%);

    width:110px;

    height:110px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
    rgba(255,255,255,.15);

    backdrop-filter:blur(12px);

    cursor:pointer;

    z-index:20;

    transition:.4s;

    border:
    1px solid rgba(255,255,255,.2);
}

/* ICON */

.play-overlay i{

    font-size:55px;

    color:white;

    margin-left:8px;
}

.play-overlay:hover{

    transform:
    translate(-50%,-50%)
    scale(1.1);

    background:
    rgba(255,255,255,.22);
}
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    scroll-behavior:smooth;
}

body{

    background:#020617;

    color:white;

    overflow-x:hidden;

    font-family:sans-serif;

    position:relative;
}

/* SCROLLBAR */

::-webkit-scrollbar{
    width:8px;
}

::-webkit-scrollbar-thumb{
    background:linear-gradient(
    #00f0ff,
    #ff00f7
    );

    border-radius:20px;
}

/* BACKGROUND BLOBS */

body::before{

    content:'';

    position:fixed;

    width:500px;

    height:500px;

    background:#00f0ff22;

    filter:blur(140px);

    top:-150px;

    left:-100px;

    z-index:-2;
}

body::after{

    content:'';

    position:fixed;

    width:500px;

    height:500px;

    background:#ff00f722;

    filter:blur(140px);

    bottom:-150px;

    right:-100px;

    z-index:-2;
}

/* CURSOR GLOW */

.cursor-glow{

    position:fixed;

    width:300px;

    height:300px;

    border-radius:50%;

    background:rgba(0,240,255,.08);

    filter:blur(120px);

    pointer-events:none;

    transform:translate(-50%,-50%);

    z-index:-1;
}

/* PROGRESS BAR */

#progressBar{

    position:fixed;

    top:0;

    left:0;

    width:0%;

    height:4px;

    z-index:9999;

    background:linear-gradient(
    90deg,
    #00f0ff,
    #ff00f7
    );
}

/* MAIN */

.article-container{

    width:100%;

    max-width:1600px;

    margin:auto;

    padding:50px 5%;

    position:relative;

    z-index:5;
}

/* HERO */

.article-hero{

    width:100%;

    height:700px;

    border-radius:35px;

    overflow:hidden;

    position:relative;

    margin-bottom:50px;

    border:1px solid rgba(255,255,255,.08);

    box-shadow:
    0 20px 80px rgba(0,0,0,.6);
}



@keyframes heroZoom{

    from{
        transform:scale(1.05);
    }

    to{
        transform:scale(1.12);
    }
}

/* OVERLAY */

.hero-overlay{

    position:absolute;

    inset:0;

    padding:70px;

    display:flex;

    flex-direction:column;

    justify-content:end;

    background:linear-gradient(
    to top,
    rgba(0,0,0,.95),
    rgba(0,0,0,.25)
    );
}

.hero-overlay h1{

    font-size:75px;

    line-height:95px;

    margin-bottom:30px;

    max-width:1000px;
}

.hero-media{

    position:relative;

    width:100%;

    height:650px;

    border-radius:40px;

    overflow:hidden;

    background:black;
}

.hero-image{

    width:100%;

    height:100%;

    background-size:cover;

    background-position:center;

    background-repeat:no-repeat;

    transform:scale(1.03);

    transition:.6s ease;
}

.hero-media:hover .hero-image{

    transform:scale(1.08);
}

.hero-video{

    width:100%;

    height:100%;

    object-fit:cover;

    background:black;
}

.hero-overlay{

    position:absolute;

    inset:0;

    padding:70px;

    display:flex;

    flex-direction:column;

    justify-content:end;

    background:linear-gradient(
    to top,
    rgba(0,0,0,.92),
    rgba(0,0,0,.25),
    transparent
    );
}

.play-overlay{

    position:absolute;

    top:50%;

    left:50%;

    transform:translate(-50%,-50%);

    width:110px;

    height:110px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
    rgba(255,255,255,.15);

    backdrop-filter:blur(12px);

    border:
    1px solid rgba(255,255,255,.2);

    cursor:pointer;

    z-index:20;
}

.play-overlay i{

    font-size:55px;

    color:white;

    margin-left:8px;
}


/* META */

.article-meta{

    display:flex;

    gap:30px;

    flex-wrap:wrap;

    color:#ddd;

    font-size:18px;
}

.article-meta span{

    display:flex;

    align-items:center;

    gap:8px;

    background:rgba(255,255,255,.08);

    padding:12px 20px;

    border-radius:15px;

    backdrop-filter:blur(12px);

    border:1px solid rgba(255,255,255,.08);
}

/* GRID */

.article-layout{

    display:grid;

    grid-template-columns:2fr 1fr;

    gap:35px;
}

/* CARDS */

.article-content,
.author-box,
.sidebar-card,
.comment-box{

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,.08),
    rgba(255,255,255,.03)
    );

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(18px);

    box-shadow:
    0 10px 40px rgba(0,0,0,.45),
    inset 0 1px 1px rgba(255,255,255,.05);

    transition:.4s ease;
}

.article-content:hover,
.author-box:hover,
.sidebar-card:hover,
.comment-box:hover{

    transform:translateY(-8px);
}

/* ARTICLE */

.article-content{

    padding:60px;

    border-radius:35px;
}

.article-content p{

    font-size:20px;

    line-height:2;

    color:#ddd;

    letter-spacing:.4px;
}

/* ACTION BAR */

.action-bar{

    margin-top:50px;

    display:flex;

    gap:20px;

    flex-wrap:wrap;
}

.action-btn{

    padding:16px 28px;

    border:none;

    border-radius:18px;

    background:rgba(255,255,255,.08);

    border:1px solid rgba(255,255,255,.08);

    color:white;

    cursor:pointer;

    font-size:16px;

    position:relative;

    overflow:hidden;

    transition:.4s;
}

.action-btn:hover{

    transform:translateY(-5px);

    box-shadow:0 0 30px rgba(0,240,255,.25);
}

.action-btn::before{

    content:'';

    position:absolute;

    width:120%;

    height:120%;

    background:linear-gradient(
    45deg,
    transparent,
    rgba(255,255,255,.2),
    transparent
    );

    transform:rotate(25deg);

    left:-150%;

    transition:1s;
}

.action-btn:hover::before{

    left:120%;
}

/* AUTHOR */

.author-box{

    margin-top:50px;

    padding:35px;

    border-radius:30px;

    display:flex;

    gap:25px;

    align-items:center;
}

.author-avatar{

    width:100px;

    height:100px;

    border-radius:50%;

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    box-shadow:0 0 40px rgba(0,240,255,.4);
}

/* COMMENTS */

.comments-section{

    margin-top:60px;
}

.comments-section h2{

    margin-bottom:25px;

    font-size:35px;
}

.comment-form textarea{

    width:100%;

    height:180px;

    padding:25px;

    border:none;

    outline:none;

    resize:none;

    border-radius:25px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.08);

    color:white;

    font-size:16px;

    margin-bottom:20px;
}

.publish-btn{

    padding:15px 35px;

    border:none;

    border-radius:16px;

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    cursor:pointer;

    font-size:16px;

    transition:.4s;
}

.publish-btn:hover{

    transform:translateY(-5px);

    box-shadow:0 0 30px rgba(255,0,247,.3);
}

/* COMMENT */

.comment-box{

    margin-top:25px;

    padding:25px;

    border-radius:25px;
}

.comment-top{

    display:flex;

    align-items:center;

    gap:15px;

    margin-bottom:15px;
}

.comment-avatar{

    width:50px;

    height:50px;

    border-radius:50%;

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );
}

.comment-box h4{

    color:#00f0ff;

    margin-bottom:5px;
}

.comment-box span{

    color:#999;

    font-size:13px;
}

.comment-box p{

    color:#ddd;

    line-height:32px;
}

/* SIDEBAR */

.sidebar{

    display:flex;

    flex-direction:column;

    gap:30px;

    position:sticky;

    top:120px;

    height:fit-content;
}

.sidebar-card{

    padding:30px;

    border-radius:30px;
}

.sidebar-card h3{

    margin-bottom:25px;
}

/* RELATED */

.related-post{

    display:flex;

    gap:15px;

    margin-bottom:25px;

    transition:.4s;
}

.related-post:hover{

    transform:translateX(10px);
}

.related-post img{

    width:110px;

    height:90px;

    object-fit:cover;

    border-radius:18px;
}

.related-post h4{

    line-height:28px;
}

/* TAGS */

.topic{

    display:inline-block;

    padding:12px 18px;

    border-radius:14px;

    margin:8px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.08);

    transition:.4s;
}

.topic:hover{

    transform:translateY(-5px);

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );
}

/* REVEAL */

.reveal{

    opacity:0;

    transform:translateY(40px);

    transition:1s;
}

.reveal.active{

    opacity:1;

    transform:translateY(0);
}

/* TOAST */

.toast{

    position:fixed;

    bottom:30px;

    right:30px;

    background:rgba(255,255,255,.08);

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(20px);

    padding:18px 25px;

    border-radius:18px;

    color:white;

    z-index:9999;

    transform:translateY(100px);

    opacity:0;

    transition:.5s;
}

.toast.show{

    transform:translateY(0);

    opacity:1;
}

/* RESPONSIVE */

/* MEDIA */

.post-media{

    margin-top:35px;

    border-radius:30px;

    overflow:hidden;

    border:
    1px solid rgba(255,255,255,.08);

    background:
    rgba(255,255,255,.04);

    backdrop-filter:blur(18px);
}

/* VIDEO */

.post-media video{

    width:100%;

    max-height:700px;

    background:black;

    object-fit:cover;
}

/* AUDIO */

.audio-player{

    padding:35px;
}

.audio-top{

    display:flex;

    align-items:center;

    gap:18px;

    margin-bottom:25px;
}

.audio-top i{

    width:70px;

    height:70px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
    linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    font-size:30px;

    box-shadow:
    0 0 30px rgba(0,240,255,.35);
}

.audio-top h3{

    font-size:24px;

    margin-bottom:5px;
}

.audio-top p{

    color:#94a3b8;
}

/* AUDIO CONTROL */

.audio-player audio{

    width:100%;
}
@media(max-width:1100px){

    .article-layout{

        grid-template-columns:1fr;
    }

    .sidebar{

        position:relative;

        top:0;
    }

    .hero-overlay h1{

        font-size:45px;

        line-height:60px;
    }

    .article-content{

        padding:35px;
    }

    .article-content p{

        font-size:17px;

        line-height:35px;
    }

    .article-hero{

        height:500px;
    }
    /* POST MEDIA */

.post-media{

    margin-top:35px;

    border-radius:25px;

    overflow:hidden;

    background:
    rgba(255,255,255,.05);

    border:
    1px solid rgba(255,255,255,.08);
}

/* VIDEO */

.post-media video{

    width:100%;

    max-height:700px;

    background:black;
}

/* AUDIO */

.audio-player{

    padding:30px;
}

.audio-player h3{

    margin-bottom:20px;

    color:white;
}

.audio-player audio{

    width:100%;
}
    
}

</style>

</head>

<body>

<!-- CURSOR GLOW -->

<div class="cursor-glow"></div>

<!-- PROGRESS BAR -->

<div id="progressBar"></div>

<!-- TOAST -->

<div class="toast" id="toast">

Link Copied Successfully

</div>

<?php include("../includes/navbar.php"); ?>

<div class="article-container">


<!-- HERO -->

<div class="article-hero reveal">

<?php

$file =
strtolower(
pathinfo(
$post['video'],
PATHINFO_EXTENSION
));

$is_audio =
(
$file == "mp3"
||
$file == "wav"
||
$file == "ogg"
);

$is_video =
(
$file == "mp4"
||
$file == "webm"
||
$file == "mov"
);

?>

<div class="hero-media">

<?php

if(
!empty($post['video'])
&&
$is_video
){

?>

<video
class="hero-video"
id="heroVideo"
poster="../uploads/<?php
echo $post['image'];
?>"
>

<source
src="../uploads/<?php
echo $post['video'];
?>"
>

</video>

<div
class="play-overlay"
id="playBtn"
>

<i class="ri-play-fill"></i>

</div>

<?php } else { ?>

<div
class="hero-image"
style="
background-image:
url('../uploads/<?php
echo $post['image'];
?>');
"
></div>

<?php } ?>

<div class="hero-overlay">

<h1>

<?php echo $post['title']; ?>

</h1>

<div class="article-meta">

<span>

<i class="ri-user-line"></i>

<?php echo $post['username']; ?>

</span>

<span>

<i class="ri-eye-line"></i>

<?php echo $post['views']; ?>

Views

</span>

<span>

<i class="ri-heart-3-fill"></i>

<?php echo $post['likes_count']; ?>

Likes

</span>

<span>

<i class="ri-time-line"></i>

<?php echo $reading_time; ?>

min read

</span>

</div>

</div>

</div>

</div>

<!-- GRID -->

<div class="article-layout">

<!-- LEFT -->

<div>

<!-- ARTICLE -->

<div class="article-content reveal">

<p>

<?php echo nl2br($post['content']); ?>

</p>

<?php

if(
!empty($post['video'])
){

?>

<div class="post-media">

<?php

if($is_audio){

?>

<div class="audio-player">

<div class="audio-top">

<i class="ri-music-2-fill"></i>

<div>

<h3>

Audio Experience

</h3>

<p>

Listen while reading

</p>

</div>

</div>

<audio controls>

<source
src="../uploads/<?php
echo $post['video'];
?>"
>

</audio>

</div>

<?php } else if($is_video){ ?>

<video controls>

<source
src="../uploads/<?php
echo $post['video'];
?>"
>

</video>

<?php } ?>

</div>

<?php } ?>

<!-- ACTIONS -->

<div class="action-bar">

<?php if(isset($_SESSION['user_id'])){ ?>

<form id="likeForm">

<input
type="hidden"
id="postId"
value="<?php echo $post['id']; ?>"
>

<button
type="submit"
class="action-btn"
id="likeBtn"
>

❤️

<span id="likeCount">

<?php echo $post['likes_count']; ?>

</span>

Likes

</button>

</form>



<?php } else { ?>

<a
href="/Login.php"
class="action-btn"
style="text-decoration:none;"
>

🔒 Login to Like

</a>

<?php } ?>

<button
class="action-btn"
onclick="
document.querySelector(
'.comment-form textarea'
)?.focus();
"
>

💬 Comment

</button>

<button
class="action-btn"
onclick="copyLink()"
>

🔗 Share

</button>

</div>

</div>

<!-- AUTHOR -->

<div class="author-box reveal">

<div class="author-avatar"></div>

<div>

<h2>

<?php echo $post['username']; ?>

</h2>

<br>

<p>

Passionate developer and technical writer creating modern web experiences.

</p>

</div>

</div>

<!-- COMMENTS -->

<div class="comments-section reveal">

<h2>

Comments

</h2>

<?php if(isset($_SESSION['user_id'])){ ?>

<form
method="POST"
class="comment-form"
>

<textarea
name="comment"
placeholder="Write your comment..."
required
></textarea>

<button
type="submit"
class="publish-btn"
name="post_comment"
>

Post Comment

</button>

</form>

<?php } else { ?>

<div class="login-required-box">

<h3>

Login Required

</h3>

<p>

Please login or register
to comment on this post.

</p>

<div class="auth-buttons">

<a href="/Login.php">

Login

</a>

<a href="/register.php">

Register

</a>

</div>

</div>

<?php } ?>

<?php

$comments_query = "
SELECT comments.*, users.username
FROM comments
JOIN users
ON comments.user_id = users.id
WHERE comments.post_id='$post_id'
ORDER BY comments.id DESC
";

$comments_result = mysqli_query(
$conn,
$comments_query
);

if(mysqli_num_rows($comments_result) > 0){

while($comment =
mysqli_fetch_assoc($comments_result)){

?>

<div class="comment-box reveal">

<div class="comment-top">

<div class="comment-avatar"></div>

<div>

<h4>

<?php
echo $comment['username'];
?>

</h4>

<span>

Just Now

</span>

</div>

</div>

<p>

<?php
echo $comment['comment'];
?>

</p>

</div>

<?php } } else { ?>

<div class="comment-box">

<p>

No comments yet.

</p>

</div>

<?php } ?>

</div>

</div>

<!-- SIDEBAR -->

<div class="sidebar">

<!-- RELATED -->

<div class="sidebar-card reveal">

<h3>

🔥 Related Posts

</h3>

<?php

$related_query = "
SELECT *
FROM posts
WHERE id != '$post_id'
ORDER BY RAND()
LIMIT 3
";

$related_result = mysqli_query(
$conn,
$related_query
);

while($related =
mysqli_fetch_assoc($related_result)){

?>

<a
href="single-post.php?id=<?php
echo $related['id'];
?>"
style="
text-decoration:none;
color:white;
"
>

<div class="related-post">

<img
src="../uploads/<?php
echo $related['image'];
?>"
>

<div>

<h4>

<?php
echo $related['title'];
?>

</h4>

</div>

</div>

</a>

<?php } ?>

</div>

<!-- TRENDING -->

<div class="sidebar-card reveal">

<h3>

📈 Trending Topics

</h3>

<div class="topic"># AI</div>

<div class="topic"># Web Development</div>

<div class="topic"># React</div>

<div class="topic"># PHP</div>

<div class="topic"># JavaScript</div>

<div class="topic"># UI/UX</div>

<div class="topic"># NodeJS</div>

<div class="topic"># MongoDB</div>

</div>

</div>

</div>

</div>

<!-- JAVASCRIPT -->

<script>

/* PROGRESS BAR */

window.addEventListener("scroll",()=>{

let scrollTop =
document.documentElement.scrollTop;

let height =
document.documentElement.scrollHeight -
document.documentElement.clientHeight;

let progress =
(scrollTop / height) * 100;

document.getElementById(
"progressBar"
).style.width = progress + "%";

});

/* REVEAL */

const reveals =
document.querySelectorAll(".reveal");

window.addEventListener("scroll",revealSections);

function revealSections(){

reveals.forEach(reveal=>{

const top =
reveal.getBoundingClientRect().top;

if(top < window.innerHeight - 100){

reveal.classList.add("active");

}

});

}

revealSections();

/* COPY LINK */

function copyLink(){

navigator.clipboard.writeText(
window.location.href
);

const toast =
document.getElementById("toast");

toast.classList.add("show");

setTimeout(()=>{

toast.classList.remove("show");

},2500);

}

/* CURSOR GLOW */

const glow =
document.querySelector(".cursor-glow");

document.addEventListener(
"mousemove",
(e)=>{

glow.style.left =
e.clientX + "px";

glow.style.top =
e.clientY + "px";

});

</script>
<script>

const heroVideo =
document.getElementById(
"heroVideo"
);

const playBtn =
document.getElementById(
"playBtn"
);

if(heroVideo){

playBtn.addEventListener(
"click",
()=>{

heroVideo.play();

heroVideo.controls = true;

playBtn.style.display =
"none";

});

heroVideo.addEventListener(
"pause",
()=>{

playBtn.style.display =
"flex";

});

}

</script>

<script>

const heroVideo =
document.getElementById(
"heroVideo"
);

const playBtn =
document.getElementById(
"playBtn"
);

if(heroVideo){

playBtn.addEventListener(
"click",
()=>{

heroVideo.play();

heroVideo.controls = true;

playBtn.style.display =
"none";

});

heroVideo.addEventListener(
"pause",
()=>{

playBtn.style.display =
"flex";

});

}

</script>

<script>

const likeForm =
document.getElementById(
"likeForm"
);

if(likeForm){

likeForm.addEventListener(
"submit",
async function(e){

e.preventDefault();

/* POST ID */

const postId =
document.getElementById(
"postId"
).value;

/* FORM DATA */

const formData =
new FormData();

formData.append(
"post_id",
postId
);

/* FETCH */

const response =
await fetch(
"/posts/like-post.php",
{
method:"POST",
body:formData
}
);

/* GET UPDATED COUNT */

const data =
await response.text();

/* UPDATE UI */

document.getElementById(
"likeCount"
).innerText = data;

});
}

</script>



</body>

</html>