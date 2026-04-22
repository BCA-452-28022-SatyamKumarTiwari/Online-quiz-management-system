<?php
require_once "../config/db.php";

function getUser(){
    global $conn;
    $id = $_SESSION['user_id'];
    $q = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
    return mysqli_fetch_assoc($q);
}

function sanitize($data){
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
