<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$doctor_id = $_POST['doctor_id'];
$patient_id = $_POST['patient_id'];
$date = $_POST['date'];
$time = $_POST['time'];

// $currentDate = date("Y-m-d");
$query = $mysqli->prepare('insert into appointments(doctor_id,patient_id,date,time) values(?,?,?,?)');
$query->bind_param('iiss', $doctor_id, $patient_id ,$date, $time);
$query->execute();

?>