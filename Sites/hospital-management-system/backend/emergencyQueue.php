<?php
header('Access-Control-Allow-Origin: *');
include("connection.php");

// Select all patients from the patients table
$queryPatients = $mysqli->prepare('SELECT * FROM patients');
$queryPatients->execute();
$arrayPatients = $queryPatients->get_result();

// Prepare a statement to check if a patient is already in the emergency-queue table
$queryCheckEmergency = $mysqli->prepare('SELECT * FROM emergency_queue WHERE patient_id = ?');
$queryCheckEmergency->bind_param('i', $patientId);

// Prepare a statement to insert a patient into the emergency-queue table
$queryInsertEmergency = $mysqli->prepare('INSERT INTO emergency_queue (patient_id, added_date, status) VALUES (?, ?, "pending")');
$queryInsertEmergency->bind_param('is', $patientId, $currentDate);

$response = [];

while ($patient = $arrayPatients->fetch_assoc()) {
    // Assuming "id" is the patient_id in the patients table
    $patientId = $patient['id'];

    // Set the current date
    $currentDate = date("Y-m-d");

    // Check if the patient is already in the emergency-queue table
    $queryCheckEmergency->execute();
    $result = $queryCheckEmergency->get_result();

    if ($result->num_rows === 0) {
        // If the patient is not in the emergency-queue table, add them
        $queryInsertEmergency->execute();

        // You can add more data to the response if needed
        $response[] = [
            'patient_id' => $patientId,
            'status' => 'added to emergency queue'
        ];
    } else {
        // If the patient is already in the emergency-queue table, you can handle it accordingly
        $response[] = [
            'patient_id' => $patientId,
            'status' => 'already in emergency queue'
        ];
    }
}

echo json_encode($response);
?>


