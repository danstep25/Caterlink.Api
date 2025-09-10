<?php
include("../Config/required.php");

  try {
    $sql = "SELECT id AS id, abbr AS value FROM `unitofmeasurement` WHERE `isActive`";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$SUCCESS->message,
      data: $data,  // now data is user info
      code: HTTPResponseCode::$SUCCESS->code
    ))->toJson();

  } catch (Throwable $ex) {
    echo (new Response(
      status: 'failed',
      message: $ex->getMessage() . '.',
      data: null,
      code: $ex->getCode(),
    ))->toJson();
  }

function getAllUser(){

}
?>