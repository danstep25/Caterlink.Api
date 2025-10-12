<?php
include("../Config/required.php");


if (isset($_GET['userGroupId'])) {
  $userGroupId = $_GET['userGroupId'];

  try {
    $sql = "SELECT * FROM usergroup WHERE `isActive` AND `userGroupId` = $userGroupId";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows(($result)) === 0)
      return throw new Error(HTTPResponseCode::$NOT_FOUND->message, HTTPResponseCode::$NOT_FOUND->code);

    while ($row = mysqli_fetch_assoc($result)) {
      $data = $row;
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