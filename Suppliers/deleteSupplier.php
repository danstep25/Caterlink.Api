<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $supplierId = $request["supplierId"];

    $validationQuery = "SELECT * FROM `supplier` WHERE `supplierId` = $supplierId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Supplier, METHOD::DELETE);

    $query = "UPDATE `supplier` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `supplierId`= '$supplierId' AND `isActive`";
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