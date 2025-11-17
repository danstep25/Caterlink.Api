<?php
include("../Config/required.php");

if (isset($username) && isset($password)) {

    $sql = "SELECT * FROM user WHERE `username` = '$username' AND `password` = '$password' AND `isActive` AND `isDeactivated` = 0";
    $result = mysqli_query($conn, $sql);
    $res = mysqli_num_rows($result);
    
    if($res > 0){
        $info = $result->fetch_assoc();
        $data = [
            "username" => $info["username"],
            "firstname" => $info["firstname"],
            "lastname" => $info["lastname"],
            "middlename" => $info["middlename"],
            "role"=> $info["role"],
        ];
    }

    if ($res === 1) {
        $response = new Response(
            status: 'success',
            message: HTTPResponseCode::$SUCCESS->message,
            data: $data,  // now data is user info
            code: HTTPResponseCode::$SUCCESS->code
        );
    } else {
        $response = new Response(
            status: 'failed',
            message: HTTPResponseCode::$UNAUTHORIZED->message,
            data: null,
            code: HTTPResponseCode::$UNAUTHORIZED->code
        );
    }

    echo $response->toJson();
}
?>