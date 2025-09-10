<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $dishId = $request["dishId"];

    $validationQuery = "SELECT * FROM `dish` WHERE `dishId` = $dishId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Dish, METHOD::DELETE);

    $query = "UPDATE `dish` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `dishId`= '$dishId' AND `isActive`";
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