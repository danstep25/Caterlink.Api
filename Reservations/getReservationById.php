<?php
include("../Config/required.php");


if (isset($_GET['reservationId'])) {
  $reservationId = $_GET['reservationId'];

  try {
    $sql = "SELECT 
              r.*,
              rp.packageId as packageId,
              rp.reservationPackageId as reservationPackageId,
              sp.serviceId as serviceId,
              sp.servicePackageId as servicePackageId,
              sp.quantity as quantity
            FROM `reservation` r
            JOIN `reservationpackage` rp 
              ON rp.reservationId = r.reservationId
            LEFT JOIN `servicePackage` sp 
              ON sp.reservationId = r.reservationId AND sp.isActive
            WHERE 
              r.isActive AND 
              rp.isActive AND 
            r.reservationId = $reservationId

";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows(($result)) === 0)
      return throw new Error(HTTPResponseCode::$NOT_FOUND->message, HTTPResponseCode::$NOT_FOUND->code);

    $addedPackageIds = [];
    $addedServiceIds = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
      if(empty($data)) {
        $data = $row;
        $data['reservationPackage'] = [];
        $data['servicePackage'] = [];
      }

      if (!in_array($row['reservationPackageId'], $addedPackageIds)) {
        $data['reservationPackage'][] = [
          'reservationPackageId' => $row['reservationPackageId'],
          'packageId' => $row['packageId']
        ];
        $addedPackageIds[] = $row['reservationPackageId'];
      }

      if (!empty($row['serviceId']) && !empty($row['servicePackageId'])) {
        if (!in_array($row['servicePackageId'], $addedServiceIds)) {
          $data['servicePackage'][] = [
            'servicePackageId' => $row['servicePackageId'],
            'serviceId' => $row['serviceId'],
            'quantity' => $row['quantity']
          ];
          $addedServiceIds[] = $row['servicePackageId'];
        }
      }
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