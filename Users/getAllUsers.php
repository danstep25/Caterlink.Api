<?php
include("../Config/required.php");

$sql = "SELECT * FROM user";
$data = [];
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

echo (new Response(
  status: 'success',
  message: 'Successful',
  data: $data,  // now data is user info
  code: 200
))->toJson();

?>