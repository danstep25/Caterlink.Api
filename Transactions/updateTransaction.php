<?php
include("../Config/required.php");
include("../Reservations/Transaction/transaction.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $reservationId =  !empty($request["reservationId"]) ? $request["reservationId"] : "";
    $amount = !empty($request["amount"]) ? $request["amount"] : "";

    if (empty($reservationId)) {
      array_push($errors, new ErrorResponse("Reservation Id is required"));
    }

    if (empty($amount)) {
      array_push($errors, new ErrorResponse("Amount is required"));
    }

    $validationQuery = "SELECT  
                          t.*,
                          r.reservationId,
                          r.isActive
                        FROM 
                          reservation r
                        JOIN
                          `transaction` t
                        ON r.reservationId = t.reservationId
                        WHERE r.reservationId = $reservationId 
                        AND r.isActive
                        AND t.statusId <> 3";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Transaction, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    (new Transaction($conn))->addOrUpdateRange($reservationId, $request);

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$SUCCESS->message,
      data: null,  // now data is user info
      code: HTTPResponseCode::$SUCCESS->code
    ))->toJson();
  }
} catch (Throwable $ex) {
  echo (new Response(
    status: 'failed',
    message: $ex->getMessage() . '.',
    data: null,
    code: $ex->getCode(),
  ))->toJson();
}
?>