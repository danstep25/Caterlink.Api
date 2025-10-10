<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $venueId =  !empty($request["venueId"]) ? $request["venueId"] : "";
    $name = !empty($request["name"]) ? $request["name"] : "";
    $price = !empty($request["price"]) ? $request["price"] : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($price)) {
      array_push($errors, new ErrorResponse("Price is required"));
    }

    $validationQuery = "SELECT * FROM `venue` WHERE `venueId` = $venueId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Venue, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `venue` 
      SET `name` = '$name', 
      `price` = '$price',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `venueId` = '$venueId'

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