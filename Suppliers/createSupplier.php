<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $name = !empty($request["name"]) ? $request["name"] : "";
    $contact = !empty($request["contact"]) ? $request["contact"] : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($contact)) {
      array_push($errors, new ErrorResponse("Contact is required"));
    }

    $validationQuery = "SELECT * FROM `supplier` WHERE 
      `name` = '$name' AND
      `contact` = '$contact' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Supplier,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "INSERT INTO `supplier` 
      (`name`, `contact`) 
      VALUES ('$name', '$contact')";

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