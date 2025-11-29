<?php
include("../../Shared/constants.php");
use BcMath\Number;

class Refund
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function confirm(int $reservationId)
  {
    $updateReservation = "
    UPDATE `reservation`
    SET 
        `status` = '" . STATUS::CANCELLED->name . "'
    WHERE `reservationId` = $reservationId";

    mysqli_query($this->conn, query: $updateReservation);
  }
}
?>