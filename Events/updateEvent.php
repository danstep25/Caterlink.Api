<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $eventId =  !empty($request["eventId"]) ? $request["eventId"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($description)) {
      array_push($errors, new ErrorResponse("description is required"));
    }

    $validationQuery = "SELECT * FROM `event` WHERE `eventId` = $eventId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Event, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `event` 
      SET
      `description` = '$description',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `eventId` = '$eventId'

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