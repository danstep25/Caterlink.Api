<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $venueId = $request["venueId"];

    $validationQuery = "
      SELECT 
        v.*,
        CASE 
          WHEN EXISTS (
            SELECT 1 
            FROM reservation r 
            WHERE r.venueId = v.venueId 
              AND r.isActive = 1
          ) THEN 1 
          ELSE 0 
        END AS isUsed
      FROM venue v
      WHERE v.venueId = $venueId 
        AND v.isActive
      LIMIT 1;
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Venue, METHOD::DELETE);
    (new Validation($conn, $validationQuery))->isUsed(MODULE::Venue);

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