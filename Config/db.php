<?php

$host = "zephyr.proxy.rlwy.net";

$user = "root";

$pass = "uvCNiOAGyOgHJAkBnfMDgAYAZUjwqaLs";

$dbname = "railway";

$port = 29560;

$conn = mysqli_connect(
$host,
$user,
$pass,
$dbname,
$port
);

if(!$conn){

die(
"Connection Failed: "
. mysqli_connect_error()
);

}
/* UTF 8 */

mysqli_set_charset(
$conn,
"utf8mb4"
);

?>