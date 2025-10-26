<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $dishId = $request["dishId"];

    $validationQuery = "
      SELECT 
        di.*,
        CASE 
          WHEN EXISTS (
            SELECT 1 
            FROM dishpackage d 
            JOIN foodpackage f ON f.foodPackageId = d.foodPackageId
            WHERE d.dishId = di.dishId 
            AND f.isActive
          ) THEN 1 
          ELSE 0 
        END AS isUsed
      FROM dish di
      WHERE di.dishId = $dishId 
        AND di.isActive
      LIMIT 1;
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Dish, METHOD::DELETE);
    (new Validation($conn, $validationQuery))->isUsed(MODULE::Dish);

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