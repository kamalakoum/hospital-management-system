<?php
header('Access-Controll-Allow-Origin:*');
include("connection.php");
$user_id = $_POST['id'];
$name = $_POST['name'];
// $last_name = $_POST['last_name'];
$password = $_POST['password'];
$role = $_POST['role'];

$query=$mysqli->prepare('select name, password from users where id =?'); //last_name,
$query->bind_param('s',$user_id);
$query->execute();
$query->store_result();
$num_rows=$query->num_rows;
$query->bind_result($name,$hashed_password);//,$last_name,
$query->fetch();


$response=[];
if($num_rows== 0){
    $response['status']= 'user not found';
    echo json_encode($response);
} else {
    if(password_verify($password,$hashed_password)){
        $response['status']= 'logged in';
        // $response['last_name']=$last_name;
        $response['name']=$name;
        echo json_encode($response);
    } else {
        $response['status']= 'wrong credentials';
        echo json_encode($response);
    }
};




function jwt_encode($payload, $secret_key) {
    // Base64Url encode the JWT header and payload
    $base64UrlHeader = base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $base64UrlPayload = base64UrlEncode(json_encode($payload));

    // Creating the signature using HMAC-SHA256
    $signature = hash_hmac("sha256", "$base64UrlHeader.$base64UrlPayload", $secret_key, true);

    // Base64Url encode the signature
    $base64UrlSignature = base64UrlEncode($signature);

    // Concatenate the base64Url-encoded header, payload, and signature to form the JWT token
    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}

// Function to perform Base64Url encoding
function base64UrlEncode($data) {
    $base64 = base64_encode($data);
    $base64Url = strtr($base64, '+/', '-_');
    return rtrim($base64Url, '=');
}

if ($query->affected_rows > 0) {
    $token_payload = [
        "user_id" => $user_id,
        "name" => $name,
        "role" => $role
    ];
    $secret_key = "kamal123";
    $jwt_token = jwt_encode($token_payload, $secret_key);
    $response = ["status" => "true", "token" => $jwt_token];

    echo json_encode($response);
} else {
    $response = ["status" => "false", "message" => "Failed to generate JWT"];
    echo json_encode($response);
}