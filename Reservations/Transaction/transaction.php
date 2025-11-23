<?php

use BcMath\Number;

class Transaction
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function addOrUpdateRange(int $reservationId, $transactionDetails)
  {
    $transactions = [];
    $existingIdList = [];

    $sql = "SELECT 
              t.*,
              r.totalPrice,
              r.reservationId,
              r.isActive
            FROM 
              reservation r
            JOIN `transaction` t ON r.reservationId = t.reservationId 
            WHERE r.reservationId = $reservationId 
            AND r.isActive";

    $result = mysqli_query($this->conn, query: $sql);

    while($trans = mysqli_fetch_assoc($result)){
      $transactions = $trans;
    }
    
    if(empty($transactions['transactionId']))
    {
      $addTransaction = "INSERT INTO `transaction` 
                                (`reservationId`, `balance`, `statusId`) 
                                VALUES('$reservationId', $transactionDetails, 0)";
      mysqli_query($this->conn, $addTransaction);
    }

    else{
      $id = $transactions['transactionId'];
      $statusId = $transactions['statusId'];
      $totalPrice = $transactions['totalPrice'];
      $amount = !empty($transactionDetails['amount']) ? $transactionDetails['amount'] : 0;
      $balance = !empty($transactions['balance']) && !empty($transactionDetails['amount']) ? ($transactions['balance'] <> 0 ? $transactions['balance'] : $totalPrice) : $transactionDetails;
      $balance = $balance - $amount;
      $refNo = !empty($transactionDetails['refNo']) ? $transactionDetails['refNo'] : "NULL";
      $paymentMethod = $transactionDetails['paymentMethod'];

      if(!empty($amount) || $amount != 0){
        if($statusId == 0 && $balance != 0){
          $statusId = 1;
        }

        else if($statusId == 1 && $balance != 0){
          $statusId = 2;
        }

        else if($balance == 0){
          $statusId = 3;
        }
      }

      $udpateTransaction = "UPDATE 
              `transaction` 
            SET 
              amount = $amount,
              statusId = $statusId,
              balance = $balance,
              refNo  = $refNo,
              paymentMethod = '$paymentMethod'
            WHERE transactionId = $id";

      mysqli_query($this->conn, query: $udpateTransaction);
    }
  }
}
?>