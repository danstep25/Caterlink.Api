<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($description)) {
      array_push($errors, new ErrorResponse("description is required"));
    }

    $validationQuery = "SELECT * FROM `event` WHERE 
      `description` = '$description' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Event,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "INSERT INTO `event` 
      (`description`) 
      VALUES ('$description')";

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