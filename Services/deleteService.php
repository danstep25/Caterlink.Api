<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $serviceId = $request["serviceId"];

    $validationQuery = "SELECT * FROM `service` WHERE `serviceId` = $serviceId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Service, METHOD::DELETE);

    $query = "UPDATE `service` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `serviceId`= '$serviceId' AND `isActive`";
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