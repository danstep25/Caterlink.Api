<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $userId = $request["userId"];

    $validationQuery = "SELECT * FROM `user` WHERE `userId` = $userId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::User, METHOD::DELETE);

    $query = "UPDATE `user` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `userId`= '$userId' AND `isActive`";
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