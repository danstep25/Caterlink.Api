<?php
include("../Config/required.php");


if ($pageSize > 0) {
  try {
    $offset = ((int)$pageIndex - 1) * $pageSize;
    $sql = "SELECT 
              i.*,
              s.name AS supplier,
              um.abbr AS um
            FROM `ingredients` i
            JOIN  `supplier` s ON i.supplierId = s.supplierId
            JOIN `unitofmeasurement` um ON i.umId = um.id
            WHERE i.isActive ";

    if (isset($_GET["searchValue"])) {
      if ($searchValue = $_GET["searchValue"]){
        $sql .= " AND i.name LIKE '%" . $searchValue . "%' OR
                i.description LIKE '%$searchValue%'";
      }
    }

    if (isset($_GET["purchaseDate"])) {
      if ($purchaseDate = $_GET["purchaseDate"]) {
        $sql .= " AND `purchaseDate` LIKE '%$purchaseDate%'";
      }
    }

    if (isset($_GET["expirationDate"])) {
      if ($expirationDate = $_GET["expirationDate"]) {
        $sql .= " AND `expirationDate` LIKE '%$expirationDate%'";
      }
    }

    $dataLimiter = $sql . "LIMIT $pageSize OFFSET $offset";

    $result = mysqli_query($conn, $sql);
    $paginatedResult = mysqli_query($conn, $dataLimiter);

    while ($row = mysqli_fetch_assoc($paginatedResult)) {
      $data[] = $row;
    }

    $totalRecords = mysqli_num_rows($result);
    $totalPages = ceil($totalRecords / $pageSize);

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$SUCCESS->message,
      data: $data,  // now data is user info
      code: HTTPResponseCode::$SUCCESS->code,
      totalRecords: $totalRecords,
      totalPages: $totalPages,
      pageIndex: $pageIndex,
      pageSize: $pageSize

    ))->toPaginateJson();

  } catch (Throwable $ex) {
    echo (new Response(
      status: 'failed',
      message: $ex->getMessage() . '.',
      data: null,
      code: $ex->getCode(),
    ))->toJson();
  }
}

function getAllUser(){

}
?>