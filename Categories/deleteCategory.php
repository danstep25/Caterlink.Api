<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];
    $categoryId = $request["categoryId"];

    $validationQuery = "
      SELECT 
        c.*,
        CASE 
          WHEN EXISTS (
            SELECT 1 
            FROM dish d 
            WHERE d.categoryId = c.categoryId 
              AND d.isActive = 1
          ) THEN 1 
          ELSE 0 
        END AS isUsed
      FROM category c
      WHERE c.categoryId = $categoryId 
        AND c.isActive
      LIMIT 1;
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Category, METHOD::DELETE);
    (new Validation($conn, $validationQuery))->isUsed(MODULE::Category);

    $query = "UPDATE `category` SET `isActive` = 0, `updatedAt` = CURRENT_TIMESTAMP WHERE `categoryId`= '$categoryId' AND `isActive`";
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