<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $username = !empty($request["username"]) ? $request["username"] : "";
    $password = !empty($request["password"]) ? $request["password"] : "";
    $firstname = !empty($request["firstname"]) ? $request["firstname"] : "";
    $middlename = !empty($request["middlename"]) ? $request["middlename"] : "";
    $lastname = !empty($request["lastname"]) ? $request["lastname"] : "";
    $suffix = !empty($request["suffix"]) ? $request["suffix"] : "";
    $role = !empty($request["role"]) ? $request["role"] : "";

    if (empty($firstname)) {
      array_push($errors, new ErrorResponse("First Name is required"));
    }

    if (empty($lastname)) {
      array_push($errors, new ErrorResponse("Last Name is required"));
    }

    if (empty($role)) {
      array_push($errors, new ErrorResponse("Role is required"));
    }

    $validationQuery = "SELECT * FROM `user` WHERE 
      `firstname` = '" . $firstname . "' AND
      `middlename` = '" . $middlename . "' AND
      `lastname` = '" . $lastname . "' AND
      `suffix` = '" . $suffix . "' AND
      `role` = '" . $role . "' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::User,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "INSERT INTO `user` 
      (`username`, `password`, `firstname`, `middlename`, `lastname`, `suffix`, `role`) 
      VALUES ('" . $username . "', '" . $password . "', '" . $firstname . "', '" . $middlename . "', '" . $lastname . "' , '". $suffix ."', '". $role ."')";

    $result = mysqli_query($conn, $sql);

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$CREATED->message,
      data: null,  // now data is user info
      code: HTTPResponseCode::$CREATED->code
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