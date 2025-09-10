<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $categoryId = $request["categoryId"];

    $validationQuery = "SELECT * FROM `category` WHERE `categoryId` = $categoryId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Category, METHOD::DELETE);

    $query = "UPDATE `category` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `categoryId`= '$categoryId' AND `isActive`";
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