<?php
include("../Config/required.php");


if ($pageSize > 0) {
  try {
    $offset = ((int)$pageIndex - 1) * $pageSize;
    $sql = "SELECT 
              d.*,
              c.description as category 
            FROM `dish` d
            JOIN `category` c ON d.categoryId = c.categoryId
            WHERE d.isActive ";

    if (isset($_GET["searchValue"])) {
      if ($searchValue = $_GET["searchValue"]){
        $sql .= " AND d.name LIKE '%" . $searchValue . "%' OR
                d.description LIKE '%$searchValue%'";
      }
    }

    $dataLimiter = $sql . "LIMIT $pageSize OFFSET $offset";

    $result = mysqli_query($conn, $sql);
    $paginatedResult = mysqli_query($conn, $dataLimiter);

    while ($row = mysqli_fetch_assoc($paginatedResult)) {
      if (isset($row['ingredients'])) {
        $row['ingredients'] = json_decode($row['ingredients'], true);
      }

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