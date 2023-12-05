<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$name = $_POST['name'];
// $last_name = $_POST['last_name'];
$password = $_POST['password'];
$role = $_POST['role'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('insert into users(name,password,role) values(?,?,?,?)'); //last_name,
$query->bind_param('ssss', $name, $last_name ,$hashed_password, $role);
$query->execute();

$user_id = $mysqli->insert_id;
echo "$user_id";

$response = [];
$response["status"] = "true";

echo json_encode($response);


if(strtolower($role) == 'doctor'){
    $specialization = $_POST['specialization'];
    $gender =$_POST['gender'];

    $query = $mysqli->prepare('INSERT INTO doctors (user_id, name, specialization , gender, last_name) VALUES (?, ?, ?, ?, ?)');
    $query->bind_param('issss', $user_id, $name, $specialization,$gender, $last_name);
    $query->execute();
    
    $response = ['status' => 'Doctor added successfully'];
    echo json_encode($response);
}

if (strtolower($role) == 'patient'){
    $gender =$_POST['gender'];
    $query = $mysqli->prepare('INSERT INTO patients (user_id ,name, last_name, gender) VALUES (?, ?, ?, ?)');
    $query->bind_param('isss',$user_id, $name, $last_name, $gender);
    $query->execute();
    
    $response = ['status' => 'Patient added successfully'];
    echo json_encode($response);
}