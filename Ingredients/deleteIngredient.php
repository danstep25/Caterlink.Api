<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $ingredientId = $request["ingredientId"];

    $validationQuery = "SELECT * FROM `ingredients` WHERE `ingredientId` = $ingredientId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Ingredients, METHOD::DELETE);

    $query = "UPDATE `ingredients` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `ingredientId`= '$ingredientId' AND `isActive`";
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