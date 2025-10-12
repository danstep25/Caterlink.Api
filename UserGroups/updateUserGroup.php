<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $userGroupId =  !empty($request["userGroupId"]) ? $request["userGroupId"] : "";
    $abbr = !empty($request["abbr"]) ? $request["abbr"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($abbr)) {
      array_push($errors, new ErrorResponse("Abbr is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    $validationQuery = "SELECT * FROM `usergroup` WHERE `userGroupId` = $userGroupId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::UserGroup, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `usergroup` 
      SET `abbr` = '$abbr', 
      `description` = '$description',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `userGroupId` = '$userGroupId'

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