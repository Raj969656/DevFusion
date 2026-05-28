<?php

session_start();

include("Config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: auth/Login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

$username = $_SESSION['username'];

$page = $_GET['page'] ?? 'home';

/* USER DATA */

$user_query = "
SELECT *
FROM users
WHERE id='$user_id'
";

$user_result =
mysqli_query(
$conn,
$user_query
);

$user =
mysqli_fetch_assoc(
$user_result
);

/* TOTAL POSTS */

$post_query = "
SELECT *
FROM posts
WHERE user_id='$user_id'
";

$post_result =
mysqli_query(
$conn,
$post_query
);

$total_posts =
mysqli_num_rows(
$post_result
);

/* TOTAL COMMENTS */

$comment_query = "
SELECT comments.*
FROM comments
JOIN posts
ON comments.post_id = posts.id
WHERE posts.user_id='$user_id'
";

$comment_result =
mysqli_query(
$conn,
$comment_query
);

$total_comments =
mysqli_num_rows(
$comment_result
);

/* TOTAL VIEWS */

$views_query = "
SELECT SUM(views)
AS total_views
FROM posts
WHERE user_id='$user_id'
";

$views_result =
mysqli_query(
$conn,
$views_query
);

$views_data =
mysqli_fetch_assoc(
$views_result
);

$total_views =
$views_data['total_views'] ?? 0;

/* TOTAL LIKES */

$likes_query = "
SELECT SUM(likes_count)
AS total_likes
FROM posts
WHERE user_id='$user_id'
";

$likes_result =
mysqli_query(
$conn,
$likes_query
);

$likes_data =
mysqli_fetch_assoc(
$likes_result
);

$total_likes =
$likes_data['total_likes'] ?? 0;
/* UPDATE PROFILE */

if(
isset($_POST['update_profile'])
){

    $full_name =
    mysqli_real_escape_string(
    $conn,
    $_POST['full_name']
    );

    $username =
    mysqli_real_escape_string(
    $conn,
    $_POST['username']
    );

    $phone =
    mysqli_real_escape_string(
    $conn,
    $_POST['phone']
    );

    $bio =
    mysqli_real_escape_string(
    $conn,
    $_POST['bio']
    );

    $github =
    mysqli_real_escape_string(
    $conn,
    $_POST['github']
    );

    $linkedin =
    mysqli_real_escape_string(
    $conn,
    $_POST['linkedin']
    );

    $website =
    mysqli_real_escape_string(
    $conn,
    $_POST['website']
    );

    /* OLD IMAGE */

    $profile_photo =
    $user['profile_photo'] ?? '';

    /* NEW IMAGE */

    if(
    isset($_FILES['profile_photo'])
    &&
    $_FILES['profile_photo']['name']
    != ""
    ){

        $image_name =
        time() .
        "_" .
        basename(
        $_FILES['profile_photo']['name']
        );

        $tmp_name =
        $_FILES['profile_photo']['tmp_name'];

        $target =
        "uploads/profile/" .
        $image_name;

        move_uploaded_file(
        $tmp_name,
        $target
        );

        $profile_photo =
        $image_name;

    }

    /* UPDATE */

    $update_query = "
    UPDATE users
    SET
    full_name='$full_name',
    username='$username',
    phone='$phone',
    bio='$bio',
    github='$github',
    linkedin='$linkedin',
    website='$website',
    profile_photo='$profile_photo'
    WHERE id='$user_id'
    ";
$update_result =
mysqli_query(
$conn,
$update_query
);

if($update_result){

    $_SESSION['username'] =
    $username;

    header(
    "Location: dashboard.php?page=settings&updated=1"
    );

    exit();

}else{

    die(
    "Update Failed: " .
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

Dashboard

</title>

<link
rel="stylesheet"
href="dashboard.css"
>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css"
/>

</head>

<body>

<div class="dashboard">

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar"> <div class="logo"> DevFusion </div>

<a
href="posts/create-post.php"
class="write-btn"
>

<i class="ri-add-line"></i>

Write Something

</a>

<ul>

<li class="<?php echo ($page == 'home') ? 'active' : ''; ?>">

<a href="dashboard.php">

<i class="ri-dashboard-line"></i>

Dashboard

</a>

</li>

<li class="<?php echo ($page == 'posts') ? 'active' : ''; ?>">

<a href="dashboard.php?page=posts">

<i class="ri-file-list-line"></i>

Posts

</a>

</li>

<li class="<?php echo ($page == 'comments') ? 'active' : ''; ?>">

<a href="dashboard.php?page=comments">

<i class="ri-chat-1-line"></i>

Comments

</a>

</li>

<li class="<?php echo ($page == 'statistics') ? 'active' : ''; ?>">

<a href="dashboard.php?page=statistics">

<i class="ri-bar-chart-line"></i>

Statistics

</a>

</li>

<li class="<?php echo ($page == 'settings') ? 'active' : ''; ?>">

<a href="dashboard.php?page=settings">

<i class="ri-settings-3-line"></i>

Settings

</a>

</li>

<li>

<a href="auth/logout.php">

<i class="ri-logout-box-line"></i>

Logout

</a>

</li>

</ul>

</div>

<!-- MAIN CONTENT -->

<div class="main-content">

<!-- TOPBAR -->
<div class="topbar"> <div class="top-left"> <button class="menu-toggle" id="menuToggle"> <i class="ri-menu-line"></i> </button> <div> <h1> Dashboard </h1> <p class="welcome-text"> Welcome back, <?php echo $user['username']; ?> 👋 </p> </div> </div> <div class="top-actions"> <a href="index.php"> <i class="ri-home-5-line"></i> Home </a> <a href="posts/all-posts.php"> <i class="ri-article-line"></i> Blogs </a> <a href="posts/create-post.php"> <i class="ri-add-circle-line"></i> Create </a> <div class="profile" id="profileMenu"> <?php if( !empty($user['profile_photo']) ){ ?> <img src="uploads/profile/<?php echo $user['profile_photo']; ?>" class="nav-profile-img" > <?php } else { ?> <div class="default-nav-profile"> <i class="ri-user-3-fill"></i> </div> <?php } ?> <div> <h4> <?php echo $user['username']; ?> </h4> <p> Creator </p> </div> </div> </div> </div>
<?php

/* HOME PAGE */

if($page == "home"){

?>

<div class="cards">

<div class="card">

<h2>

<?php echo $total_posts; ?>

</h2>

<p>

Total Posts

</p>

</div>

<div class="card">

<h2>

<?php echo $total_comments; ?>

</h2>

<p>

Comments

</p>

</div>

<div class="card">

<h2>

<?php echo $total_views; ?>

</h2>

<p>

Views

</p>

</div>

<div class="card">

<h2>

<?php echo $total_likes; ?>

</h2>

<p>

Likes

</p>

</div>

</div>

<div class="analytics-grid">

<div class="chart-box">

<h2>

Views Analytics

</h2>

<div class="bars">

<?php

$analytics_query = "
SELECT *
FROM posts
WHERE user_id='$user_id'
ORDER BY views DESC
LIMIT 7
";

$analytics_result =
mysqli_query(
$conn,
$analytics_query
);

$max_views = 1;

$temp_views = [];

/* FIND MAX */

while(
$temp =
mysqli_fetch_assoc(
$analytics_result
)
){

$temp_views[] = $temp;

if($temp['views'] > $max_views){

$max_views = $temp['views'];

}

}

/* GENERATE BARS */

foreach($temp_views as $index => $post){

$height =
($post['views'] / $max_views) * 300;

?>

<div class="bar-wrapper">

<div
class="
bar
<?php
echo ($index == 0)
? 'active-bar'
: '';
?>
"
style="
height:
<?php
echo $height;
?>px
"
>

<span class="bar-tooltip">

<?php
echo $post['views'];
?>

Views

</span>

</div>

<p>

<?php

echo substr(
$post['title'],
0,
10
);

?>

</p>

</div>

<?php } ?>

</div>

</div>
<div class="activity-box">

<h2>

Recent Activity

</h2>

<div class="activity">

<p>

🔥 New comment on your blog

</p>

<span>

2 mins ago

</span>

</div>

<div class="activity">

<p>

❤️ Someone liked your post

</p>

<span>

1 hour ago

</span>

</div>

<div class="activity">

<p>

📈 Your blog is trending

</p>

<span>

Today

</span>

</div>

</div>

</div>

<div class="recent-posts">

<h2>

Recent Posts

</h2>

<table>

<tr>

<th>Title</th>
<th>Status</th>
<th>Views</th>
<th>Likes</th>
<th>Action</th>

</tr>

<?php

$table_query = "
SELECT *
FROM posts
WHERE user_id='$user_id'
ORDER BY id DESC
LIMIT 5
";

$table_result =
mysqli_query(
$conn,
$table_query
);

while(
$table =
mysqli_fetch_assoc(
$table_result
)
){

?>

<tr>

<td>

<?php
echo $table['title'];
?>

</td>

<td>

<span class="status">

<?php
echo $table['status'];
?>

</span>

</td>

<td>

<?php
echo $table['views'];
?>

</td>

<td>

<?php
echo $table['likes_count'];
?>

</td>

<td>

<a
href="posts/edit-post.php?id=<?php
echo $table['id'];
?>"
class="edit-btn"
>

Edit

</a>

<a
href="posts/delete-post.php?id=<?php
echo $table['id'];
?>"
class="delete-btn"
>

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

<div class="popular-posts">

<h2>

Popular Posts

</h2>

<?php

$popular_query = "
SELECT *
FROM posts
WHERE user_id='$user_id'
ORDER BY views DESC
LIMIT 3
";

$popular_result =
mysqli_query(
$conn,
$popular_query
);

while(
$popular =
mysqli_fetch_assoc(
$popular_result
)
){

?>

<div class="popular-card">

<img
src="uploads/<?php
echo $popular['image'];
?>"
>

<div>

<h3>

<?php
echo $popular['title'];
?>

</h3>

<div class="post-stats">

<span>

👁 <?php echo $popular['views']; ?>

</span>

<span>

❤️ <?php echo $popular['likes_count']; ?>

</span>

</div>

</div>

</div>

<?php } ?>

</div>

<?php } ?>

<?php

/* POSTS */

if($page == "posts"){

?>

<div class="recent-posts">

<h2>

All Posts

</h2>

<table>

<tr>

<th>Title</th>
<th>Status</th>
<th>Views</th>
<th>Likes</th>
<th>Action</th>

</tr>

<?php

$post_table = "
SELECT *
FROM posts
WHERE user_id='$user_id'
ORDER BY id DESC
";

$post_table_result =
mysqli_query(
$conn,
$post_table
);

while(
$post =
mysqli_fetch_assoc(
$post_table_result
)
){

?>

<tr>

<td>

<?php echo $post['title']; ?>

</td>

<td>

<span class="status">

<?php echo $post['status']; ?>

</span>

</td>

<td>

<?php echo $post['views']; ?>

</td>

<td>

<?php echo $post['likes_count']; ?>

</td>

<td>

<a
href="posts/edit-post.php?id=<?php
echo $post['id'];
?>"
class="edit-btn"
>

Edit

</a>

<a
href="posts/delete-post.php?id=<?php
echo $post['id'];
?>"
class="delete-btn"
>

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } ?>

<?php

/* COMMENTS */

if($page == "comments"){

?>

<div class="recent-posts">

<h2>

Comments

</h2>

<table>

<tr>

<th>Comment</th>
<th>Post</th>

</tr>

<?php

$comments_query = "
SELECT comments.*, posts.title
FROM comments
JOIN posts
ON comments.post_id = posts.id
WHERE posts.user_id='$user_id'
ORDER BY comments.id DESC
";

$comments_result =
mysqli_query(
$conn,
$comments_query
);

while(
$comment =
mysqli_fetch_assoc(
$comments_result
)
){

?>

<tr>

<td>

<?php
echo $comment['comment'];
?>

</td>

<td>

<?php
echo $comment['title'];
?>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } ?>

<?php

/* STATISTICS */

if($page == "statistics"){

?>

<div class="cards">

<div class="card">

<h2>

<?php echo $total_posts; ?>

</h2>

<p>

Total Posts

</p>

</div>

<div class="card">

<h2>

<?php echo $total_views; ?>

</h2>

<p>

Views

</p>

</div>

<div class="card">

<h2>

<?php echo $total_likes; ?>

</h2>

<p>

Likes

</p>

</div>

<div class="card">

<h2>

<?php echo $total_comments; ?>

</h2>

<p>

Comments

</p>

</div>

</div>

<?php } ?>

<?php

/* SETTINGS */

if($page == "settings"){
    if(isset($_GET['updated'])){

echo '

<div class="success-message">

Profile Updated Successfully

</div>

';

}

?>

<div class="settings-box">

<h2>

Profile Settings

</h2>

<form
method="POST"
enctype="multipart/form-data"
class="settings-form"
>

<div class="profile-preview">

<?php

if(
!empty($user['profile_photo'])
){

?>

<img
src="uploads/profile/<?php
echo $user['profile_photo'];
?>"
class="profile-image"
>

<?php } else { ?>

<div class="default-profile">

<i class="ri-user-3-fill"></i>

</div>

<?php } ?>

</div>

<div class="input-group">

<label>

Full Name

</label>

<input
type="text"
name="full_name"
value="<?php
echo $user['full_name'] ?? '';
?>"
placeholder="Enter Full Name"
>

</div>

<div class="input-group">

<label>

Username

</label>

<input
type="text"
name="username"
value="<?php
echo $user['username'] ?? '';
?>"
placeholder="Enter Username"
>

</div>

<div class="input-group">

<label>

Phone Number

</label>

<input
type="text"
name="phone"
value="<?php
echo $user['phone'] ?? '';
?>"
placeholder="Enter Phone Number"
>

</div>

<div class="input-group">

<label>

Profile Photo

</label>

<input
type="file"
name="profile_photo"
>

</div>

<div class="input-group">

<label>

Bio

</label>

<textarea
name="bio"
placeholder="Write something about yourself"
><?php
echo $user['bio'] ?? '';
?></textarea>

</div>

<div class="input-group">

<label>

Github Link

</label>

<input
type="text"
name="github"
value="<?php
echo $user['github'] ?? '';
?>"
placeholder="Github Profile"
>

</div>

<div class="input-group">

<label>

LinkedIn Link

</label>

<input
type="text"
name="linkedin"
value="<?php
echo $user['linkedin'] ?? '';
?>"
placeholder="LinkedIn Profile"
>

</div>

<div class="input-group">

<label>

Website

</label>

<input
type="text"
name="website"
value="<?php
echo $user['website'] ?? '';
?>"
placeholder="Website URL"
>

</div>

<button
type="submit"
name="update_profile"
class="save-btn"
>

Save Changes

</button>

</form>

</div>

<?php } ?>

</div>

</div>
<script>

const menuToggle =
document.getElementById("menuToggle");

const sidebar =
document.getElementById("sidebar");

menuToggle.addEventListener(
"click",
() => {

sidebar.classList.toggle(
"show-sidebar"
);

}
);

/* AUTO CLOSE */

document.addEventListener(
"click",
function(e){

if(
!sidebar.contains(e.target)
&&
!menuToggle.contains(e.target)
){

sidebar.classList.remove(
"show-sidebar"
);

}

}
);

</script>
<script>

const menuToggle =
document.getElementById(
"menuToggle"
);

const sidebar =
document.getElementById(
"sidebar"
);

menuToggle.addEventListener(
"click",
function(e){

e.stopPropagation();

sidebar.classList.toggle(
"show-sidebar"
);

}
);

/* OUTSIDE CLICK CLOSE */

document.addEventListener(
"click",
function(e){

if(
!sidebar.contains(e.target)
&&
!menuToggle.contains(e.target)
){

sidebar.classList.remove(
"show-sidebar"
);

}

}
);

</script>
</body>
</html>