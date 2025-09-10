<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $categoryId =  !empty($request["categoryId"]) ? $request["categoryId"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    $validationQuery = "SELECT * FROM `category` WHERE `categoryId` = $categoryId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Category, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `category` 
      SET `description` = '$description',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `categoryId` = '$categoryId'

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