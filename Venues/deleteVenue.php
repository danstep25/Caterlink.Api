<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $venueId = $request["venueId"];

    $validationQuery = "SELECT * FROM `venue` WHERE `venueId` = $venueId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Venue, METHOD::DELETE);

    $query = "UPDATE `venue` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `venueId`= '$venueId' AND `isActive`";
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