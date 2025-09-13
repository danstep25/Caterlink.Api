<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $foodPackageId = $request["foodPackageId"];

    $validationQuery = "SELECT * FROM `foodpackage` WHERE `foodPackageId` = $foodPackageId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::FoodPackage, METHOD::DELETE);

    $query = "UPDATE `foodpackage` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `foodPackageId`= '$foodPackageId' AND `isActive`";
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