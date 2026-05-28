<?php

session_start();

include("../Config/db.php");


$sql = "

SELECT posts.*,

users.username,

(
SELECT COUNT(*)
FROM likes
WHERE likes.post_id = posts.id
) AS likes_count,

(
SELECT COUNT(*)
FROM comments
WHERE comments.post_id = posts.id
) AS comments_count

FROM posts

JOIN users
ON posts.user_id = users.id

ORDER BY posts.id DESC

";


$result = mysqli_query(
$conn,
$sql
);

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

All Blogs

</title>

<link
rel="stylesheet"
href="../style.css"
>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

<style>

/* PAGE */

.blog-page{

    width:100%;

    max-width:1500px;

    margin:auto;

    padding:140px 40px 80px;

    min-height:100vh;
}

/* HEADER */

.blog-header{

    text-align:center;

    margin-bottom:70px;
}

.blog-header h1{

    font-size:90px;

    line-height:1.1;

    margin-bottom:25px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;
}

.blog-header p{

    max-width:850px;

    margin:auto;

    color:#aaa;

    font-size:24px;

    line-height:1.8;
}

/* GRID */

.blog-grid{

    display:grid;

    grid-template-columns:
    repeat(2,1fr);

    gap:40px;
}

/* CARD */

.blog-card{

    background:
    rgba(255,255,255,.05);

    border:
    1px solid rgba(255,255,255,.08);

    border-radius:28px;

    overflow:hidden;

    backdrop-filter:blur(20px);

    transition:.4s;

    display:flex;

    flex-direction:column;

    min-height:650px;
}

.blog-card:hover{

    transform:
    translateY(-10px);

    box-shadow:
    0 20px 50px rgba(0,0,0,.5);
}

/* IMAGE */

.blog-image{

    position:relative;

    width:100%;

    height:320px;

    overflow:hidden;
}

.blog-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;
}

.blog-card:hover img{

    transform:scale(1.06);
}

/* CATEGORY */

.category-badge{

    position:absolute;

    top:20px;

    left:20px;

    padding:12px 20px;

    border-radius:50px;

    font-size:14px;

    font-weight:700;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    color:white;
}

/* CONTENT */

.blog-content{

    padding:30px;

    flex:1;

    display:flex;

    flex-direction:column;
}

/* META */

.blog-meta{

    display:flex;

    align-items:center;

    justify-content:space-between;

    margin-bottom:20px;

    color:#aaa;

    font-size:15px;
}

.author{

    display:flex;

    align-items:center;

    gap:10px;
}

.author i{

    color:#00f0ff;
}

/* TITLE */

.blog-content h2{

    font-size:36px;

    line-height:1.4;

    margin-bottom:20px;

    color:white;
}

/* TEXT */

.blog-content p{

    color:#aaa;

    line-height:2;

    font-size:16px;

    margin-bottom:30px;
}

/* STATS */

.blog-stats{

    display:flex;

    gap:25px;

    margin-top:auto;

    margin-bottom:25px;

    color:#ccc;
}

.blog-stats span{

    display:flex;

    align-items:center;

    gap:8px;
}

/* BUTTON */

.read-btn{

    width:fit-content;

    display:inline-flex;

    align-items:center;

    gap:10px;

    padding:16px 28px;

    border-radius:16px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    text-decoration:none;

    color:white;

    font-weight:700;

    transition:.4s;
}

.read-btn:hover{

    transform:translateY(-4px);

    box-shadow:
    0 10px 30px rgba(0,240,255,.4);
}

/* TABLET */

@media(max-width:1100px){

    .blog-grid{

        grid-template-columns:1fr;
    }

    .blog-header h1{

        font-size:70px;
    }

}

/* MOBILE */

@media(max-width:768px){

    .blog-page{

        padding:120px 20px 60px;
    }

    .blog-header{

        margin-bottom:50px;
    }

    .blog-header h1{

        font-size:46px;
    }

    .blog-header p{

        font-size:17px;

        line-height:1.7;
    }

    .blog-grid{

        grid-template-columns:1fr;

        gap:30px;
    }

    .blog-card{

        min-height:auto;
    }

    .blog-image{

        height:230px;
    }

    .blog-content{

        padding:22px;
    }

    .blog-content h2{

        font-size:28px;
    }

    .blog-content p{

        font-size:15px;

        line-height:1.8;
    }

    .blog-meta{

        flex-direction:column;

        align-items:flex-start;

        gap:10px;
    }

    .blog-stats{

        flex-wrap:wrap;

        gap:15px;
    }

    .read-btn{

        width:100%;

        justify-content:center;
    }

}

/* SMALL MOBILE */

@media(max-width:480px){

    .blog-header h1{

        font-size:38px;
    }

    .blog-image{

        height:200px;
    }

    .blog-content h2{

        font-size:24px;
    }

}
/* PAGE */

.blog-page{

    padding:140px 7% 80px;

    min-height:100vh;
}

/* HEADER */

.blog-header{

    text-align:center;

    margin-bottom:70px;
}

.blog-header h1{

    font-size:70px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;

    margin-bottom:20px;
}

.blog-header p{

    color:#aaa;

    font-size:20px;

    max-width:700px;

    margin:auto;

    line-height:1.8;
}

/* GRID */

.blog-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(380px,1fr));

    gap:40px;
}

/* CARD */

.blog-card{

    background:
    rgba(255,255,255,.05);

    border:
    1px solid rgba(255,255,255,.08);

    border-radius:30px;

    overflow:hidden;

    transition:.4s;

    backdrop-filter:blur(20px);

    position:relative;
}

.blog-card:hover{

    transform:
    translateY(-12px);

    box-shadow:
    0 20px 40px rgba(0,0,0,.4);
}

/* IMAGE */

.blog-image{

    position:relative;

    height:260px;

    overflow:hidden;
}

.blog-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;
}

.blog-card:hover img{

    transform:scale(1.08);
}

/* CATEGORY */

.category-badge{

    position:absolute;

    top:20px;

    left:20px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    padding:10px 18px;

    border-radius:50px;

    font-size:13px;

    font-weight:600;
}

/* CONTENT */

.blog-content{

    padding:30px;
}

/* META */

.blog-meta{

    display:flex;

    align-items:center;

    justify-content:space-between;

    margin-bottom:20px;

    color:#aaa;

    font-size:14px;
}

.author{

    display:flex;

    align-items:center;

    gap:10px;
}

.author i{

    color:#00f0ff;
}

/* TITLE */

.blog-content h2{

    font-size:32px;

    margin-bottom:18px;

    line-height:1.4;
}

/* TEXT */

.blog-content p{

    color:#aaa;

    line-height:1.9;

    margin-bottom:30px;
}

/* STATS */

.blog-stats{

    display:flex;

    gap:25px;

    margin-bottom:25px;

    color:#ccc;
}

.blog-stats span{

    display:flex;

    align-items:center;

    gap:8px;
}

/* BUTTON */

.read-btn{

    display:inline-flex;

    align-items:center;

    gap:10px;

    padding:16px 28px;

    border-radius:16px;

    text-decoration:none;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    color:white;

    font-weight:600;

    transition:.4s;
}

.read-btn:hover{

    transform:translateY(-4px);

    box-shadow:
    0 10px 30px rgba(0,240,255,.3);
}

/* EMPTY */

.empty-post{

    text-align:center;

    padding:120px 20px;
}

.empty-post i{

    font-size:90px;

    color:#00f0ff;

    margin-bottom:20px;
}

.empty-post h2{

    font-size:45px;

    margin-bottom:20px;
}

.empty-post p{

    color:#aaa;

    margin-bottom:40px;
}

.create-btn{

    display:inline-block;

    padding:18px 35px;

    border-radius:18px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    color:white;

    text-decoration:none;

    font-weight:600;
}

</style>

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<section class="blog-page">

<!-- HEADER -->

<div class="blog-header">

<h1>

Explore Blogs

</h1>

<p>

Discover premium developer stories,
AI insights, programming tutorials,
startup ideas and modern technology trends.

</p>

</div>

<?php

if(mysqli_num_rows($result) > 0){

?>

<div class="blog-grid">

<?php

while(
$row =
mysqli_fetch_assoc($result)
){

?>

<div class="blog-card">

<!-- IMAGE -->

<div class="blog-image">

<img
src="../uploads/<?php
echo $row['image'];
?>"
>

<div class="category-badge">

Technology

</div>

</div>

<!-- CONTENT -->

<div class="blog-content">

<!-- META -->

<div class="blog-meta">

<div class="author">

<i class="ri-user-3-line"></i>

<span>

<?php
echo $row['username'];
?>

</span>

</div>

<div>

<i class="ri-time-line"></i>

5 min read

</div>

</div>

<!-- TITLE -->

<h2>

<?php
echo $row['title'];
?>

</h2>

<!-- DESCRIPTION -->

<p>

<?php

echo substr(
$row['content'],
0,
150
);

?>

...

</p>

<!-- STATS -->

<div class="blog-stats">

<span>

<i class="ri-eye-line"></i>

<?php
echo $row['views'];
?>

</span>

<span>

<i class="ri-heart-3-line"></i>

<?php
echo $row['likes_count'];
?>

</span>



<i class="ri-chat-3-fill"></i>

<?php echo $row['comments_count']?>

Comments

</span>



</span>

</div>

<!-- BUTTON -->

<a
href="single-post.php?id=<?php
echo $row['id'];
?>"
class="read-btn"
>

Read Full Article

<i class="ri-arrow-right-line"></i>

</a>

</div>

</div>

<?php } ?>

</div>

<?php } else { ?>

<div class="empty-post">

<i class="ri-article-line"></i>

<h2>

No Blogs Found

</h2>

<p>

Start creating premium articles now.

</p>

<a
href="create-post.php"
class="create-btn"
>

Create First Blog

</a>

</div>

<?php } ?>

</section>

</body>
</html>