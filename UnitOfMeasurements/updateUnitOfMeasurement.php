<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $id =  !empty($request["id"]) ? $request["id"] : "";
    $abbr = !empty($request["abbr"]) ? $request["abbr"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($abbr)) {
      array_push($errors, new ErrorResponse("Abbreviation is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    $validationQuery = "SELECT * FROM `unitofmeasurement` WHERE `id` = $id AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::UnitOfMeasurement, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `unitofmeasurement` 
      SET `abbr` = '$abbr', 
      `description` = '$description',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `id` = '$id'

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