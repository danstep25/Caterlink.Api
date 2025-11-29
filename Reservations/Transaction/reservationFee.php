<?php
include("../../Shared/constants.php");
use BcMath\Number;

class ReservationFee
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function paid(int $reservationId, $reservationDetails)
  {
    $updateReservationFee = "
    UPDATE `reservation`
    SET 
        `status` = '" . STATUS::ACTIVE->name . "',
        `refNo` = " . (!empty($reservationDetails['refNo']) 
                        ? "'" . $reservationDetails['refNo'] . "'" 
                        : "NULL") . ",
        `reservationFee` = " . $reservationDetails['amount'] . "
    WHERE `reservationId` = $reservationId";

    mysqli_query($this->conn, query: $updateReservationFee);
  }
}
?>