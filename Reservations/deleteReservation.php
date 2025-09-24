<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $reservationId = $request["reservationId"];

    $validationQuery = "SELECT * FROM `reservation` WHERE `reservationId` = $reservationId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Reservation, METHOD::DELETE);

    $query = "UPDATE `reservation` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `reservationId`= '$reservationId' AND `isActive`";
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