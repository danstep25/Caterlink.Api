<?php
include("../Config/required.php");
include("ReservationPackage/reservationPackage.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $fullName = !empty($request["fullName"]) ? $request["fullName"] : "";
    $address = !empty($request["address"]) ? $request["address"] : "";
    $contactNo = !empty($request["contactNo"]) ? $request["contactNo"] : "";
    $eventId = !empty($request["eventId"]) ? $request["eventId"] : "";
    $reservationPackage = !empty($request["reservationPackage"]) ? $request["reservationPackage"] : "";
    $noOfGuest = !empty($request["noOfGuest"]) ? $request["noOfGuest"] : "";
    $dateFrom = !empty($request["dateFrom"]) ? $request["dateFrom"] : "";
    $dateTo = !empty($request["dateTo"]) ? $request["dateTo"] : "";

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
    
    $validationQuery = "SELECT * FROM `reservation` WHERE 
      `fullName` = '$fullName' AND
      `address` = '$address' AND
      `dateFrom` = '$dateFrom' AND
      `dateTo` = '$dateTo' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Reservation,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $reservationCountQuery = "SELECT COUNT(*) as Total FROM reservation";
    $reservationCount = mysqli_fetch_assoc(mysqli_query($conn, $reservationCountQuery));
    $reservationPackageId = (int) $reservationCount['Total'] + 1;

    (new ReservationPackage($conn))->addOrUpdateRange($reservationPackageId, $reservationPackage);

    $sql = "INSERT INTO `reservation` 
      (`fullName`, `address`, `contactNo`, `eventId`, `noOfGuest`, `dateFrom`, `dateTo`) 
      VALUES ('$fullName', '$address' , '$contactNo', '$eventId', '$noOfGuest', '$dateFrom', '$dateTo')";

    $result = mysqli_query($conn, $sql);

    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$CREATED->message,
      data: null,  // now data is user info
      code: HTTPResponseCode::$CREATED->code
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