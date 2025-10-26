<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $foodPackageId = $request["foodPackageId"];

    $validationQuery = "SELECT * FROM `foodpackage` WHERE `foodPackageId` = $foodPackageId AND `isActive`";
    $validationQuery = "
      SELECT 
        f.*,
        CASE 
          WHEN EXISTS (
            SELECT 1 
            FROM reservationpackage re
            JOIN reservation r ON re.reservationId = r.reservationId
            WHERE re.packageId = f.foodPackageId 
            AND r.isActive
          ) THEN 1 
          ELSE 0 
        END AS isUsed
      FROM foodpackage f
      WHERE f.foodPackageId = $foodPackageId 
        AND f.isActive
      LIMIT 1;
      ";


    (new Validation($conn, $validationQuery))->isValid(MODULE::FoodPackage, METHOD::DELETE);
    (new Validation($conn, $validationQuery))->isUsed(MODULE::FoodPackage);

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