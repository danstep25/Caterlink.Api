<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $serviceId =  !empty($request["serviceId"]) ? $request["serviceId"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $price = !empty($request["price"]) ? $request["price"] : "";

    if (empty($description)) {
      array_push($errors, new ErrorResponse("description is required"));
    }

    $validationQuery = "SELECT * FROM `service` WHERE `serviceId` = $serviceId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Service, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `service` 
      SET
      `description` = '$description',
      `price` = '$price',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `serviceId` = '$serviceId'

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