<?php
include("../Config/required.php");


try {
  $offset = ((int) $pageIndex - 1) * $pageSize;
  $sql = "SELECT 
              r.*,
              t.*,
              e.description
            FROM `reservation` r
            JOIN  `event` e ON e.eventId = r.eventId
            JOIN `transaction` t ON t.reservationId = r.reservationId
            WHERE r.isActive";

  if (isset($_GET["searchValue"])) {
    if ($searchValue = $_GET["searchValue"]) {
      $sql .= " AND r.fullName LIKE '%" . $searchValue . "%' OR
                e.description LIKE '%$searchValue%'";
    }
  }

  if (isset($_GET["dateFrom"])) {
    $dateFrom = $_GET["dateFrom"];
    $sql .= " AND r.dateFrom LIKE '%" . $dateFrom . "%'";
  }

  $sql .= " GROUP BY r.reservationId ";

  if (!empty($pageSize))
    $dataLimiter = $sql . "LIMIT $pageSize OFFSET $offset";

  $result = mysqli_query($conn, $sql);

  if (!empty($dataLimiter))
    $paginatedResult = mysqli_query($conn, $dataLimiter);
  else
    $paginatedResult = $result;

  while ($row = mysqli_fetch_assoc($paginatedResult)) {
    $reservationId = $row['reservationId'];
    
    // Fetch reservation packages
    $packageSql = "SELECT reservationPackageId, packageId 
                   FROM reservationpackage 
                   WHERE reservationId = $reservationId AND isActive";
    $packageResult = mysqli_query($conn, $packageSql);
    $row['reservationPackage'] = [];
    while ($packageRow = mysqli_fetch_assoc($packageResult)) {
      $row['reservationPackage'][] = [
        'reservationPackageId' => $packageRow['reservationPackageId'],
        'packageId' => $packageRow['packageId']
      ];
    }
    
    // Fetch service packages
    $serviceSql = "SELECT servicePackageId, serviceId, quantity 
                   FROM servicePackage 
                   WHERE reservationId = $reservationId AND isActive";
    $serviceResult = mysqli_query($conn, $serviceSql);
    $row['servicePackage'] = [];
    while ($serviceRow = mysqli_fetch_assoc($serviceResult)) {
      $row['servicePackage'][] = [
        'servicePackageId' => $serviceRow['servicePackageId'],
        'serviceId' => $serviceRow['serviceId'],
        'quantity' => $serviceRow['quantity']
      ];
    }
    
    $data[] = $row;
  }

  $totalRecords = mysqli_num_rows($result);
  
  if(!empty($pageSize))
    $totalPages = ceil($totalRecords / $pageSize);

  echo (new Response(
    status: 'success',
    message: HTTPResponseCode::$SUCCESS->message,
    data: $data,  // now data is user info
    code: HTTPResponseCode::$SUCCESS->code,
    totalRecords: $totalRecords,
    totalPages: $totalPages ?? 0,
    pageIndex: $pageIndex ?? 0,
    pageSize: $pageSize ?? 0

  ))->toPaginateJson();

} catch (Throwable $ex) {
  echo (new Response(
    status: 'failed',
    message: $ex->getMessage() . '.',
    data: null,
    code: $ex->getCode(),
  ))->toJson();
}