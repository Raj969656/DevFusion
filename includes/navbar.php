<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();

}

include(__DIR__ . "/../Config/db.php");

/* USER DATA */

$current_user = null;

if(isset($_SESSION['user_id'])){

    $user_id = $_SESSION['user_id'];

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

    $current_user =
    mysqli_fetch_assoc(
    $user_result
    );

}

?>

<nav class="navbar">

    <!-- LOGO -->

    <a
    href="/rwc/index.php"
    class="logo"
    >

        <div class="logo-icon">

            <i class="ri-code-box-fill"></i>

        </div>

        <div class="logo-text">

            DevFusion

        </div>

    </a>

    <!-- LINKS -->

    <ul class="nav-links">

        <li>

            <a href="/rwc/index.php">

                Home

            </a>

        </li>

        <li>

           

            <a href="/posts/all-posts.php">


                Blogs

            </a>

        </li>

        <?php if(isset($_SESSION['user_id'])){ ?>

        <li>

            <a href="/posts/create-post.php">

                Create Post

            </a>

        </li>

        <!-- PROFILE -->

        <li class="profile-dropdown">

            <div class="profile-box">

                <!-- AVATAR -->

                <div class="profile-avatar">

                    <?php

                    if(
                    !empty(
                    $current_user['profile_photo']
                    )
                    ){

                    ?>

                    <img
                    src="/rwc/uploads/profile/<?php
                    echo $current_user['profile_photo'];
                    ?>"
                    class="avatar-img"
                    >

                    <?php

                    }else{

                        echo strtoupper(
                        substr(
                        $_SESSION['username'],
                        0,
                        1
                        ));

                    }

                    ?>

                </div>

                <!-- INFO -->

                <div class="profile-info">

                    <span class="welcome-text">

                        Welcome

                    </span>

                    <h4>

                        <?php
                        echo $_SESSION['username'];
                        ?>

                    </h4>

                </div>

                <i class="ri-arrow-down-s-line dropdown-icon"></i>

            </div>

            <!-- DROPDOWN -->

            <div class="dropdown-menu" id="profileDropdown">

                <a href="/rwc/dashboard.php">

                    <i class="ri-dashboard-line"></i>

                    Dashboard

                </a>

                <a href="/posts/create-post.php">

                    <i class="ri-add-circle-line"></i>

                    Create Post

                </a>

                <a href="/posts/all-posts.php">

                    <i class="ri-article-line"></i>

                    My Blogs

                </a>

                <a
                href="/auth/logout.php"
                class="logout-btn"
                >

                    <i class="ri-logout-box-r-line"></i>

                    Logout

                </a>

            </div>

        </li>

        <?php } else { ?>

        <li>

            <a href="/register.php">

                Register

            </a>

        </li>

        <li>

            <a
            href="/Login.php"
            class="login-btn-nav"
            >

                Login

            </a>

        </li>

        <?php } ?>

    </ul>

</nav>

<style>

/* NAVBAR */

.navbar{

    width:100%;

    padding:22px 7%;

    display:flex;

    justify-content:space-between;

    align-items:center;

    position:sticky;

    top:0;

    z-index:999;

    background:rgba(2,6,23,.78);

    backdrop-filter:blur(20px);

    border-bottom:
    1px solid rgba(255,255,255,.06);
}

/* LOGO */

.logo{

    display:flex;

    align-items:center;

    gap:14px;

    text-decoration:none;
}

.logo-icon{

    width:52px;

    height:52px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:linear-gradient(
    135deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    font-size:24px;

    box-shadow:
    0 0 35px rgba(0,240,255,.3);
}

.logo-text{

    font-size:28px;

    font-weight:800;

    color:white;

    letter-spacing:.5px;
}

/* NAV LINKS */

.nav-links{

    display:flex;

    align-items:center;

    gap:18px;

    list-style:none;
}

.nav-links li{

    position:relative;
}

.nav-links a{

    text-decoration:none;

    color:#ddd;

    font-size:16px;

    transition:.3s;
}

.nav-links a:hover{

    color:#00f0ff;
}

/* LOGIN BUTTON */

.login-btn-nav{

    padding:12px 24px;

    border-radius:14px;

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white !important;

    font-weight:600;

    box-shadow:
    0 0 25px rgba(0,240,255,.25);
}

/* PROFILE */

.profile-dropdown{

    position:relative;
}

.profile-box{

    display:flex;

    align-items:center;

    gap:14px;

    padding:10px 18px;

    border-radius:22px;

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,.08),
    rgba(255,255,255,.03)
    );

    border:1px solid rgba(255,255,255,.08);

    cursor:pointer;

    transition:.4s;
}

.profile-box:hover{

    transform:translateY(-3px);

    box-shadow:
    0 0 35px rgba(0,240,255,.15);
}

/* AVATAR */

.profile-avatar{

    width:54px;

    height:54px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    overflow:hidden;

    background:linear-gradient(
    45deg,
    #00f0ff,
    #ff00f7
    );

    color:white;

    font-size:20px;

    font-weight:700;

    box-shadow:
    0 0 25px rgba(0,240,255,.3);
}

/* IMAGE */

.avatar-img{

    width:100%;

    height:100%;

    object-fit:cover;

    border-radius:50%;
}

/* INFO */

.profile-info{

    display:flex;

    flex-direction:column;
}

.welcome-text{

    font-size:12px;

    color:#999;
}

.profile-info h4{

    color:white;

    font-size:15px;

    margin-top:2px;
}

/* ICON */

.dropdown-icon{

    color:#bbb;

    font-size:20px;
}

/* DROPDOWN */

.dropdown-menu{

    position:absolute;

    top:85px;

    right:0;

    width:270px;

    padding:15px;

    border-radius:24px;

    background:
    linear-gradient(
    145deg,
    rgba(255,255,255,.08),
    rgba(255,255,255,.03)
    );

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(18px);

    opacity:0;

    visibility:hidden;

    transform:translateY(20px);

    transition:.4s;

    box-shadow:
    0 20px 60px rgba(0,0,0,.45);
}

/* SHOW */

.profile-dropdown:hover .dropdown-menu{

    opacity:1;

    visibility:visible;

    transform:translateY(0);
}

/* MENU LINKS */

.dropdown-menu a{

    display:flex;

    align-items:center;

    gap:14px;

    padding:15px 18px;

    border-radius:16px;

    color:white;

    transition:.3s;
}

.dropdown-menu a:hover{

    background:rgba(255,255,255,.06);

    transform:translateX(6px);
}

/* LOGOUT */

.logout-btn{

    margin-top:10px;

    background:rgba(255,0,90,.12);
}

.logout-btn:hover{

    background:rgba(255,0,90,.2) !important;

    color:#ff4d8d !important;
}

/* RESPONSIVE */

@media(max-width:900px){

    .navbar{

        padding:20px 5%;

        flex-direction:column;

        gap:20px;
    }

    .nav-links{

        flex-wrap:wrap;

        justify-content:center;
    }

    .profile-box{

        padding:10px 14px;
    }

    .profile-info{

        display:none;
    }

    .dropdown-menu{

        right:-40px;
    }

    .logo-text{

        font-size:24px;
    }

}

</style>