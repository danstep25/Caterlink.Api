<?php
include("CORS.php");
include("../Response/response.php");
include("../Response/errorResponse.php");
include("../Response/HTTPResponseCode.php");
include("../Shared/validation.php");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: application/json');
//initialize HTTP RESPONSE CODE
HTTPResponseCode::init();

try {
  $username = null;
  $password = null;
  $pageSize = null;
  $pageIndex = null;
  $totalRecords = null;
  $totalPages = null;
  $request = null;
  $errors = [];
  $data = [];

  //CREDENTIALS
  if (isset($input['username']) && isset($input['password'])) {
    $username = $input['username'];
    $password = $input['password'];
  }

  //PAGINATION REQUEST
  if (isset($_GET['pageSize'])) {
    $pageIndex = $_GET['pageIndex'];
    $pageSize = $_GET['pageSize'];

    if ($pageIndex == null)
      array_push($errors, new ErrorResponse("Page Index can't be zero"));

    if ($pageSize == 0 || $pageSize == null)
      array_push($errors, new ErrorResponse("Page Index can't be zero"));

    if (count($errors) != 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      throw new Exception($errorString, HTTPResponseCode::$BAD_REQUEST->code);
    }
  }

} catch (Exception $ex) {
  echo (new Response(
    status: 'failed',
    message: $ex->getMessage() . ".",
    data: null,
    code: $ex->getCode(),
  ))->toJson();
}
?>