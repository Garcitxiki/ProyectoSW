<?php
if (!isset($_SESSION)) {
    session_start();
}
$token = $_GET['id'];
$json = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=" . $token);
$json = json_decode($json);
$_SESSION['email'] = $json->email;
$_SESSION['foto'] = $json->picture;
$_SESSION['tipo'] = 'A';
$_SESSION['google'] = 'S';
