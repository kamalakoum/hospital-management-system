<?php
header('Access-Controll-Allow-Origin:*');
include("connection.php");
$query=$mysqli->prepare('select * from doctors');
$query->execute();
$array=$query->get_result();
$response=[];

while($doctor=$array->fetch_assoc()){
    $response[]=$doctor;
}
echo json_encode($response);