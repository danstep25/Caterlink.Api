<?php
include("../Config/required.php");
include("ReservationPackage/reservationPackage.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $reservationId = !empty($request["reservationId"]) ? $request["reservationId"] : "";
    $fullName = !empty($request["fullName"]) ? $request["fullName"] : "";
    $address = !empty($request["address"]) ? $request["address"] : "";
    $contactNo = !empty($request["contactNo"]) ? $request["contactNo"] : "";
    $eventId = !empty($request["eventId"]) ? $request["eventId"] : "";
    $reservationPackage = !empty($request["reservationPackage"]) ? $request["reservationPackage"] : "";
    $noOfGuest = !empty($request["noOfGuest"]) ? $request["noOfGuest"] : "";
    $dateFrom = !empty($request["dateFrom"]) ? $request["dateFrom"] : "";
    $dateTo = !empty($request["dateTo"]) ? $request["dateTo"] : "";
    $venueId = !empty($request["venueId"]) ? $request["venueId"] : "";
    $totalPrice = !empty($request["totalPrice"]) ? $request["totalPrice"] : "";
    $isDiscount = !empty($request["isDiscount"]) ? $request["isDiscount"] : '0';
    $discount = !empty($request["discount"]) ? $request["discount"] : "";

    if (empty($fullName)) {
      array_push($errors, new ErrorResponse("Full Name is required"));
    }

    if (empty($address)) {
      array_push($errors, new ErrorResponse("Address is required"));
    }

    if (empty($reservationPackage)) {
      array_push($errors, new ErrorResponse("Package is required"));
    }

    if (empty($noOfGuest)) {
      array_push($errors, new ErrorResponse("Number of guest is required"));
    }

    if (empty($dateFrom)) {
      array_push($errors, new ErrorResponse("Date from is required"));
    }

    if (empty($venueId)) {
      array_push($errors, new ErrorResponse("Venue is required"));
    }

    if (empty($totalPrice)) {
      array_push($errors, new ErrorResponse("Total Price is required"));
    }

    $validationQuery = "SELECT * FROM `reservation` WHERE `reservationId` = $reservationId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Reservation, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    (new ReservationPackage($conn))->addOrUpdateRange($reservationId, $reservationPackage);
    
    $sql = "UPDATE `reservation` SET 
        `fullName` = '$fullName', 
        `address` = '$address', 
        `contactNo` = '$contactNo', 
        `eventId` = '$eventId', 
        `noOfGuest` = '$noOfGuest', 
        `dateFrom` = '$dateFrom', 
        `dateTo` = '$dateTo',
        `venueId` = '$venueId',
        `isDiscount` = '$isDiscount',
        `discount` = '$discount',
        `totalPrice` = '$totalPrice' 
      WHERE reservationId = $reservationId";

    $result = mysqli_query($conn, $sql);

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