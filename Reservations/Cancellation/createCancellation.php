<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $reservationId = !empty($request["reservationId"]) ? $request["reservationId"] : "";
    // $contact = !empty($request["contact"]) ? $request["contact"] : "";
    // $description = !empty($request["description"]) ? $request["description"] : "";
    // $address = !empty($request["address"]) ? $request["address"] : "";

    if (empty($reservationId)) {
      array_push($errors, new ErrorResponse("ReservationId is required"));
    }

    $validationQuery = "SELECT * FROM `reservation` WHERE 
      `reservationId` = '$reservationId' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::reservation,METHOD::CANCEL);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `reservation` SET 
      `status` = '".STATUS::Cancelled->value."',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE reservationId = $reservationId";

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