<?php

session_start();

include("Config/db.php");

/* LATEST POSTS */

/* LATEST POSTS */

$sql = "
SELECT posts.*, users.username
FROM posts
JOIN users
ON posts.user_id = users.id
ORDER BY posts.id DESC
LIMIT 6
";

$result = mysqli_query($conn, $sql);

if(!$result){

    die(mysqli_error($conn));

}

/* FEATURED POSTS */

$featured_query = "
SELECT *
FROM posts
ORDER BY id DESC
LIMIT 3
";

$featured_result = mysqli_query(
$conn,
$featured_query
);

if(!$featured_result){

    die(mysqli_error($conn));

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

DevFusion Blog

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

/* HERO */




.hero-badge{

    display:inline-block;

    padding:14px 28px;

    border-radius:50px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    margin-bottom:40px;

    font-size:18px;
}

.hero h1{

    font-size:140px;

    line-height:150px;

    margin-bottom:40px;

    font-weight:800;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;
}

.hero p{

    font-size:28px;

    line-height:50px;

    color:#ccc;

    margin-bottom:50px;
}

.hero-buttons{

    display:flex;

    justify-content:center;

    gap:25px;

    flex-wrap:wrap;
}

.hero-btn{

    padding:18px 40px;

    border-radius:18px;

    text-decoration:none;

    color:white;

    font-size:18px;

    transition:.4s;
}

.primary-btn{

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );
}

.secondary-btn{

    background:rgba(255,255,255,.08);

    border:1px solid rgba(255,255,255,.1);
}

.hero-btn:hover{

    transform:translateY(-8px);
}

/* SECTION */

.section-title{

    font-size:55px;

    text-align:center;

    margin-bottom:20px;
}

.section-subtitle{

    text-align:center;

    color:#bbb;

    margin-bottom:60px;

    font-size:20px;
}

/* STATS */

.stats-section{

    width:100%;

    padding:90px 8%;

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(220px,1fr));

    gap:30px;
}

.stat-box{

    padding:45px;

    border-radius:30px;

    text-align:center;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    backdrop-filter:blur(18px);

    transition:.4s;
}

.stat-box:hover{

    transform:translateY(-10px);

    box-shadow:0 0 20px rgba(0,240,255,.2);
}

.stat-box h1{

    font-size:60px;

    margin-bottom:15px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;
}

/* FEATURED */

.featured-section{

    padding:90px 8%;
}

.featured-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(350px,1fr));

    gap:35px;
}

.featured-card{

    position:relative;

    height:550px;

    border-radius:35px;

    overflow:hidden;

    transition:.4s;
}

.featured-card:hover{

    transform:translateY(-10px);
}

.featured-card img{

    width:100%;

    height:100%;

    object-fit:cover;
}

.featured-overlay{

    position:absolute;

    inset:0;

    background:linear-gradient(
        to top,
        rgba(0,0,0,.95),
        rgba(0,0,0,.2)
    );

    padding:40px;

    display:flex;

    flex-direction:column;

    justify-content:end;
}

.featured-tag{

    display:inline-block;

    padding:10px 20px;

    border-radius:50px;

    background:rgba(255,255,255,.08);

    margin-bottom:25px;

    width:max-content;
}

.featured-overlay h2{

    font-size:40px;

    margin-bottom:20px;
}

.featured-overlay p{

    line-height:32px;

    color:#ddd;

    margin-bottom:25px;
}

.featured-overlay a{

    color:#00f0ff;

    text-decoration:none;

    font-weight:600;
}

/* CATEGORIES */

.categories-section{

    padding:90px 8%;
}

.category-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(280px,1fr));

    gap:30px;
}

.category-card{

    padding:45px;

    border-radius:30px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    backdrop-filter:blur(18px);

    transition:.4s;
}

.category-card:hover{

    transform:translateY(-10px);

    box-shadow:0 0 20px rgba(0,240,255,.2);
}

.category-card i{

    font-size:55px;

    color:#00f0ff;

    margin-bottom:25px;
}

.category-card h3{

    margin-bottom:20px;

    font-size:28px;
}

.category-card p{

    color:#bbb;

    line-height:32px;
}

/* ARTICLES */

.articles-section{

    padding:90px 8%;
}

.articles-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(350px,1fr));

    gap:35px;
}

.article-card{

    border-radius:30px;

    overflow:hidden;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    transition:.4s;
}

.article-card:hover{

    transform:translateY(-10px);
}

.article-image{

    height:280px;
}

.article-image img{

    width:100%;

    height:100%;

    object-fit:cover;
}

.article-content{

    padding:35px;
}

.article-meta{

    display:flex;

    justify-content:space-between;

    margin-bottom:20px;

    color:#bbb;

    font-size:15px;
}

.article-content h2{

    margin-bottom:20px;

    font-size:32px;

    line-height:42px;
}

.article-content p{

    color:#ccc;

    line-height:32px;

    margin-bottom:30px;
}

.read-more{

    color:#00f0ff;

    text-decoration:none;

    font-size:17px;
}

/* TECH */

.tech-section{

    padding:90px 8%;
}

.tech-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(250px,1fr));

    gap:30px;
}

.tech-card{

    padding:45px;

    border-radius:30px;

    text-align:center;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    transition:.4s;
}

.tech-card:hover{

    transform:translateY(-10px);

    box-shadow:0 0 20px rgba(0,240,255,.3);
}

.tech-card i{

    font-size:60px;

    margin-bottom:20px;

    color:#00f0ff;
}

.tech-card h3{

    margin-bottom:15px;
}

.tech-card p{

    color:#bbb;

    line-height:30px;
}

/* AUTHORS */

.authors-section{

    padding:90px 8%;
}

.authors-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(280px,1fr));

    gap:30px;
}

.author-card{

    padding:45px;

    border-radius:30px;

    text-align:center;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);
}

.author-avatar{

    width:120px;

    height:120px;

    margin:auto;

    margin-bottom:25px;

    border-radius:50%;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );
}

.author-card h3{

    margin-bottom:15px;
}

.author-card p{

    color:#bbb;
}

/* TESTIMONIALS */

.testimonial-section{

    padding:90px 8%;
}

.testimonial-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(320px,1fr));

    gap:30px;
}

.testimonial-card{

    padding:45px;

    border-radius:30px;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);

    transition:.4s;
}

.testimonial-card:hover{

    transform:translateY(-10px);
}

.testimonial-card p{

    line-height:35px;

    color:#ddd;

    margin-bottom:25px;
}

.testimonial-card h4{

    color:#00f0ff;
}

/* NEWSLETTER */

.newsletter{

    padding:100px 8%;
}

.newsletter-box{

    padding:70px;

    border-radius:35px;

    text-align:center;

    background:rgba(255,255,255,.06);

    border:1px solid rgba(255,255,255,.1);
}

.newsletter-box h2{

    font-size:50px;

    margin-bottom:20px;
}

.newsletter-box p{

    color:#bbb;

    margin-bottom:40px;

    font-size:18px;
}

.newsletter form{

    display:flex;

    justify-content:center;

    gap:20px;

    flex-wrap:wrap;
}

.newsletter input{

    width:400px;

    padding:18px 25px;

    border:none;

    outline:none;

    border-radius:15px;

    background:rgba(255,255,255,.08);

    color:white;
}

.newsletter button{

    padding:18px 35px;

    border:none;

    border-radius:15px;

    background:linear-gradient(
        45deg,
        #00f0ff,
        #ff00f7
    );

    color:white;

    cursor:pointer;
}

/* RESPONSIVE */

@media(max-width:900px){

    .hero h1{

        font-size:80px;

        line-height:90px;
    }

    .hero p{

        font-size:20px;

        line-height:36px;
    }

    .section-title{

        font-size:40px;
    }
}

</style>

</head>

<body>

<!-- ANIMATED BACKGROUND -->

<div class="bg-animation">

<span></span>
<span></span>
<span></span>
<span></span>
<span></span>

</div>

<!-- NAVBAR -->

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">

<div class="hero-content">

<span class="hero-badge">

🔥 Trending Developer Platform

</span>

<h1>

Build.<br>
Write.<br>
Inspire.

</h1>

<p>

Discover premium developer articles,
programming tutorials,
AI insights and modern
technology trends.

</p>

<div class="hero-buttons">

<a
href="posts/all-posts.php"
class="hero-btn primary-btn"
>

Explore Blogs

</a>

<a
href="posts/create-post.php"
class="hero-btn secondary-btn"
>

Start Writing

</a>

</div>

</div>

</section>

<!-- STATS -->

<section class="stats-section">

<div class="stat-box">

<h1>25K+</h1>

<p>Active Developers</p>

</div>

<div class="stat-box">

<h1>1200+</h1>

<p>Premium Articles</p>

</div>

<div class="stat-box">

<h1>350+</h1>

<p>Tech Tutorials</p>

</div>

<div class="stat-box">

<h1>99%</h1>

<p>User Satisfaction</p>

</div>

</section>

<!-- FEATURED -->

<section class="featured-section">

<h2 class="section-title">

Featured Articles

</h2>

<p class="section-subtitle">

Most viewed developer stories

</p>

<div class="featured-grid">

<?php

while($featured =
mysqli_fetch_assoc($featured_result)){

?>

<div class="featured-card">

<img
src="uploads/<?php
echo $featured['image'];
?>"
>

<div class="featured-overlay">

<span class="featured-tag">

🔥 Trending

</span>

<h2>

<?php
echo $featured['title'];
?>

</h2>

<p>

<?php

echo substr(
$featured['content'],
0,
100
);

?>

...

</p>

<a
href="posts/single-post.php?id=<?php
echo $featured['id'];
?>"
>

Read Full Article

</a>

</div>

</div>

<?php } ?>

</div>

</section>

<!-- CATEGORIES -->

<section class="categories-section">

<h2 class="section-title">

Popular Categories

</h2>

<p class="section-subtitle">

Explore latest technologies and trends

</p>

<div class="category-grid">

<div class="category-card">

<i class="ri-code-s-slash-line"></i>

<h3>Web Development</h3>

<p>

Modern frontend and backend tutorials

</p>

</div>

<div class="category-card">

<i class="ri-smartphone-line"></i>

<h3>Mobile Apps</h3>

<p>

Android, Flutter and iOS development

</p>

</div>

<div class="category-card">

<i class="ri-ai-generate"></i>

<h3>AI & ML</h3>

<p>

Artificial intelligence and machine learning

</p>

</div>

<div class="category-card">

<i class="ri-cloud-line"></i>

<h3>Cloud Computing</h3>

<p>

DevOps, AWS and scalable systems

</p>

</div>

</div>

</section>

<!-- LATEST ARTICLES -->

<section class="articles-section">

<h2 class="section-title">

Latest Articles

</h2>

<p class="section-subtitle">

Trending developer stories and tutorials

</p>

<div class="articles-grid">

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<div class="article-card">

<div class="article-image">

<img
src="uploads/<?php
echo $row['image'];
?>"
>

</div>

<div class="article-content">

<div class="article-meta">

<span>

<i class="ri-user-line"></i>

<?php
echo $row['username'];
?>

</span>

<span>

<i class="ri-time-line"></i>

5 min read

</span>

</div>

<h2>

<?php
echo $row['title'];
?>

</h2>

<p>

<?php

echo substr(
$row['content'],
0,
120
);

?>

...

</p>

<a
href="posts/single-post.php?id=<?php
echo $row['id'];
?>"
class="read-more"
>

Read Full Article

<i class="ri-arrow-right-line"></i>

</a>

</div>

</div>

<?php } ?>

</div>

</section>

<!-- TECHNOLOGIES -->

<section class="tech-section">

<h2 class="section-title">

Trending Technologies

</h2>

<p class="section-subtitle">

Modern technologies developers love

</p>

<div class="tech-grid">

<div class="tech-card">

<i class="ri-reactjs-line"></i>

<h3>React JS</h3>

<p>

Modern frontend library

</p>

</div>

<div class="tech-card">

<i class="ri-nodejs-line"></i>

<h3>Node JS</h3>

<p>

Backend JavaScript runtime

</p>

</div>

<div class="tech-card">

<i class="ri-database-2-line"></i>

<h3>MongoDB</h3>

<p>

Scalable NoSQL database

</p>

</div>

<div class="tech-card">

<i class="ri-code-box-line"></i>

<h3>PHP</h3>

<p>

Powerful backend language

</p>

</div>

<div class="tech-card">

<i class="ri-cloud-line"></i>

<h3>AWS</h3>

<p>

Cloud infrastructure platform

</p>

</div>

<div class="tech-card">

<i class="ri-ai-generate"></i>

<h3>Artificial AI</h3>

<p>

Next generation AI systems

</p>

</div>

</div>

</section>

<!-- AUTHORS -->

<section class="authors-section">

<h2 class="section-title">

Featured Authors

</h2>

<p class="section-subtitle">

Meet our top developer writers

</p>

<div class="authors-grid">

<div class="author-card">

<div class="author-avatar"></div>

<h3>Razz</h3>

<p>

Full Stack Developer & Blogger

</p>

</div>

<div class="author-card">

<div class="author-avatar"></div>

<h3>Alex Morgan</h3>

<p>

AI Engineer & Researcher

</p>

</div>

<div class="author-card">

<div class="author-avatar"></div>

<h3>David Roy</h3>

<p>

Cloud Architect & DevOps Expert

</p>

</div>

</div>

</section>

<!-- TESTIMONIALS -->

<section class="testimonial-section">

<h2 class="section-title">

What Developers Say

</h2>

<p class="section-subtitle">

Loved by thousands of creators

</p>

<div class="testimonial-grid">

<div class="testimonial-card">

<p>

"One of the best modern blogging platforms for developers."

</p>

<h4>

- Michael Anderson

</h4>

</div>

<div class="testimonial-card">

<p>

"The UI experience feels premium and modern."

</p>

<h4>

- Sarah Johnson

</h4>

</div>

<div class="testimonial-card">

<p>

"I use DevFusion daily for AI and web development blogs."

</p>

<h4>

- David Miller

</h4>

</div>

</div>

</section>

<!-- NEWSLETTER -->

<section class="newsletter">

<div class="newsletter-box">

<h2>

Join Our Developer Community

</h2>

<p>

Get latest blogs, tutorials and tech updates

</p>

<form>

<input
type="email"
placeholder="Enter your email"
>

<button type="submit">

Subscribe

</button>

</form>

</div>

</section>

<!-- FOOTER -->

<?php include("includes/footer.php"); ?>

</body>
</html>