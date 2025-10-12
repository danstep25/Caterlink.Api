<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $userId =  !empty($request["userId"]) ? $request["userId"] : "";
    $username = !empty($request["username"]) ? $request["username"] : "";
    $password = !empty($request["password"]) ? $request["password"] : "";
    $firstname = !empty($request["firstname"]) ? $request["firstname"] : "";
    $middlename = !empty($request["middlename"]) ? $request["middlename"] : "";
    $lastname = !empty($request["lastname"]) ? $request["lastname"] : "";
    $suffix = !empty($request["suffix"]) ? $request["suffix"] : "";

    if (empty($firstname)) {
      array_push($errors, new ErrorResponse("First Name is required"));
    }

    if (empty($lastname)) {
      array_push($errors, new ErrorResponse("Last Name is required"));
    }

    $validationQuery = "SELECT * FROM `user` WHERE `userId` = $userId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::User, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `user` 
      SET `username` = '$username', 
      `password` = '$password',
      `firstname` = '$firstname',
      `middlename` = '$middlename',
      `lastname` = '$lastname',
      `suffix` = '$suffix',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `userId` = '$userId'

      ";

    $result = mysqli_query($conn, $sql);

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$SUCCESS->message,
      data: null,  // now data is user info
      code: HTTPResponseCode::$SUCCESS->code
    ))->toJson();
  }
} catch (Throwable $ex) {
  echo (new Response(
    status: 'failed',
    message: $ex->getMessage() . '.',
    data: null,
    code: $ex->getCode(),
  ))->toJson();
}
?>