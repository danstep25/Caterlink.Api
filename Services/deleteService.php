<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $serviceId = $request["serviceId"];

    $validationQuery = "SELECT * FROM `service` WHERE `serviceId` = $serviceId AND `isActive`";
    $validationQuery = "
      SELECT 
        s.*,
        CASE 
          WHEN EXISTS (
            SELECT 1 
            FROM servicePackage sp
            JOIN reservation r ON sp.reservationId = r.reservationId
            WHERE sp.serviceId = s.serviceId 
            AND r.isActive
          ) THEN 1 
          ELSE 0 
        END AS isUsed
      FROM service s
      WHERE s.serviceId = $serviceId 
        AND s.isActive
      LIMIT 1;
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Service, METHOD::DELETE);
    (new Validation($conn, $validationQuery))->isUsed(MODULE::Service);

    $query = "UPDATE `service` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `serviceId`= '$serviceId' AND `isActive`";
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