<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $eventId = $request["eventId"];

    $validationQuery = "SELECT * FROM `event` WHERE `eventId` = $eventId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Event, METHOD::DELETE);

    $query = "UPDATE `event` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `eventId`= '$eventId' AND `isActive`";
    $result = mysqli_query($conn, $query);

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