<?php
include("../Config/required.php");


try {
  $offset = ((int) $pageIndex - 1) * $pageSize;
  $sql = "SELECT 
              c.*
            FROM `category` c
            GROUP BY c.categoryId ";

  if (isset($_GET["searchValue"])) {
    if ($searchValue = $_GET["searchValue"]) {
      $sql .= " AND c.description LIKE '%" . $searchValue . "%'";
    }
  }

  if (!empty($pageSize))
    $dataLimiter = $sql . "LIMIT $pageSize OFFSET $offset";

  $result = mysqli_query($conn, $sql);

  if (!empty($dataLimiter))
    $paginatedResult = mysqli_query($conn, $dataLimiter);
  else
    $paginatedResult = $result;

  while ($row = mysqli_fetch_assoc($paginatedResult)) {
    $data[] = $row;
  }

  $totalRecords = mysqli_num_rows($result);
  
  if(!empty($pageSize))
    $totalPages = ceil($totalRecords / $pageSize);

  echo (new Response(
    status: 'success',
    message: HTTPResponseCode::$SUCCESS->message,
    data: $data,
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
?>




