<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$user_id = $_POST['user_id'];

$query = $mysqli->prepare('DELETE FROM users WHERE id=?');
$query->bind_param('i' , $user_id);
$query->execute();

$response = ['status' => 'Patient deleted successfully'];
echo json_encode($response);
?>