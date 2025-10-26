<?php
include("../Config/config.php");
include("constants.php");

class Validation
{
  private string $validationQuery;
  private mysqli $conn;

  public function __construct(mysqli $conn, string $validationQuery)
  {
    $this->conn = $conn;
    $this->validationQuery = $validationQuery;
  }

  public function isValid(MODULE $moduleName, METHOD $method)
  {
    $result = mysqli_query($this->conn, $this->validationQuery);
    switch ($method) {
      case METHOD::CREATE:
        if (mysqli_num_rows($result) > 0) {
          return throw new Exception(HTTPResponseCode::$CONFLICT->message . ": " . $moduleName->name . " already exist", HTTPResponseCode::$CONFLICT->code);
        }
        break;

      case METHOD::DELETE:
      case METHOD::UPDATE:
        if (mysqli_num_rows($result) == 0) {
          return throw new Exception($moduleName->name . " " . HTTPResponseCode::$NOT_FOUND->message, HTTPResponseCode::$NOT_FOUND->code);
        }
        break;
    }
  }

  public function isUsed(MODULE $moduleName){
    $result = mysqli_query($this->conn, $this->validationQuery);

if ($result && $row = mysqli_fetch_assoc($result)) {
    if ($row['isUsed'] == 1) {
      throw new Exception(
          $moduleName->name . " cannot be deleted because it is being used. " . HTTPResponseCode::$CONFLICT->message,
          HTTPResponseCode::$CONFLICT->code
      );
    }
}
  }
}