<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$patient_id = $_POST['patient_id'];
$diagnosis = $_POST['diagnosis'];
$prescription = $_POST['prescription'];

$currentDate = date("Y-m-d");
$query = $mysqli->prepare('insert into medical_history(patient_id,prescription,diagnosis,date) values(?,?,?,?)');
$query->bind_param('isss', $patient_id, $prescription ,$diagnosis, $currentDate);
$query->execute();

?>