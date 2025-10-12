<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $id = $request["id"];

    $validationQuery = "SELECT * FROM `unitofmeasurement` WHERE `id` = $id AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::UnitOfMeasurement, METHOD::DELETE);

    $query = "UPDATE `unitofmeasurement` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `id`= '$id' AND `isActive`";
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