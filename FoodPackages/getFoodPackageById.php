<?php
include("../Config/required.php");


if (isset($_GET['foodPackageId'])) {
  $foodPackageId = $_GET['foodPackageId'];

  try {
    $sql = "SELECT 
              f.*,
              d.dishId as dishId,
              d.dishPackageId as dishPackageId,
              d.servingSize as servingSize
            FROM `foodpackage` f
            JOIN `dishpackage` d 
              ON f.foodPackageId = d.foodPackageId 
            WHERE 
              f.isActive AND
              d.isActive AND 
            f.foodPackageId = $foodPackageId";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows(($result)) === 0)
      return throw new Error(HTTPResponseCode::$NOT_FOUND->message, HTTPResponseCode::$NOT_FOUND->code);

    while ($row = mysqli_fetch_assoc($result)) {
      if (empty($data)) {
        $data = $row;
        $data['dishes'] = []; // ✅ initialize once
      }

      $data['dishes'][] = [
        'dishPackageId' => $row['dishPackageId'],
        'dishId' => $row['dishId'],
        'servingSize' => $row['servingSize']
      ];
    }



    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$SUCCESS->message,
      data: $data,  // now data is user info
      code: HTTPResponseCode::$SUCCESS->code,
    ))->toJson();

  } catch (Throwable $ex) {
    echo (new Response(
      status: 'failed',
      message: $ex->getMessage() . '.',
      data: null,
      code: $ex->getCode(),
    ))->toJson();
  }
}
?>