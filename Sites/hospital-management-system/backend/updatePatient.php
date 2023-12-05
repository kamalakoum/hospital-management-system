<?php
header('Access-Control-Allow-Origin:*');
include("connection.php");

$user_id = $_POST['user_id']; 
$password = $_POST['password'];
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$gender = $_POST['gender'];
$isAssigned =$_POST['isAssigned'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$queryUser = $mysqli->prepare('UPDATE users SET name=?, last_name=?, password=? WHERE id=?');
$queryUser->bind_param('sssi', $name, $last_name, $hashed_password, $user_id);
$queryUser->execute();

$query = $mysqli->prepare('UPDATE patients SET name=?, last_name=?, gender=? , isAssigned =? WHERE user_id=?');
$query->bind_param('sssii', $name, $last_name, $gender,$isAssigned ,$user_id);
$query->execute();

if ($isAssigned == 1) {
        // Check if there is a free room
        $queryCheckFreeRoom = $mysqli->prepare('SELECT id, room_number FROM rooms WHERE status = "free" LIMIT 1');
        $queryCheckFreeRoom->execute();
        $resultCheckFreeRoom = $queryCheckFreeRoom->get_result();
    
        if ($resultCheckFreeRoom->num_rows > 0) {
            // There is a free room, allocate it to the patient
            $rowFreeRoom = $resultCheckFreeRoom->fetch_assoc();
            $roomId = $rowFreeRoom['id'];
    
            // Update the room record
            $queryUpdateRoom = $mysqli->prepare('UPDATE rooms SET user_id=?, status="reserved" WHERE id=?');
            $queryUpdateRoom->bind_param('ii', $user_id, $roomId);
            $queryUpdateRoom->execute();
        } else {
            // Handle the case when there is no free room available
            $response = ['status' => 'No free rooms available'];
            echo json_encode($response);
            exit; // Terminate the script to prevent further execution
        }
}


$response = ['status' => 'Patient updated successfully'];
echo json_encode($response);
?>