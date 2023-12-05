<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$user_id = $_POST['user_id']; 
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$password = $_POST['password'];
$specialization = $_POST['specialization'];
$gender = $_POST['gender'];

$query = $mysqli->prepare('UPDATE users SET name=?, last_name=?, password=? WHERE id=?');
$query->bind_param('sssi', $name, $last_name, $password, $user_id);
$query->execute();

// Update the 'doctors' table
$queryDoctors = $mysqli->prepare('UPDATE doctors SET name=?, last_name=?, specialization=?, gender=? WHERE user_id=?');
$queryDoctors->bind_param('ssssi', $name, $last_name, $specialization, $gender, $user_id);
$queryDoctors->execute();

$response = ['status' => 'Doctor updated successfully'];
echo json_encode($response);
?>