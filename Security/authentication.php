<?php
include("../Config/required.php");

if (isset($input['username']) && isset($input['password'])) {
    $username = trim($input['username']);
    $password = trim($input['password']);

    $sql = "SELECT * FROM user WHERE `username` = '" . $username . "' AND `password` = '" . $password . "'";
    $data = '';
    $response = new Response();
    $result = mysqli_query($conn, $sql);
    $res = $result->num_rows;

    if ($res === 1) {
        $data = $result->fetch_assoc(); // fetch the single row
        $response = new Response(
            status: 'success',
            message: 'Successful',
            data: $data,  // now data is user info
            code: 200
        );
    } else {
        $response = new Response(
            status: 'failed',
            message: 'Unauthorized',
            data: null,
            code: 401
        );
    }

    echo $response->toJson();
}
?>