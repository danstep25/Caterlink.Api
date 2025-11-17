<?php
include("../Config/required.php");
include("ReservationPackage/reservationPackage.php");
include("Transaction/transaction.php");

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
    $timeFrom = !empty($request["timeFrom"]) ? $request["timeFrom"] : "";

    // If dateTo is empty, set it to NULL (not "NULL" string)
    $dateTo = !empty($request["dateTo"]) ? $request["dateTo"] : null;
    $timeTo = !empty($request["timeTo"]) ? $request["timeTo"] : null;
    $venueId = !empty($request["venueId"]) ? $request["venueId"] : "";
    $totalPrice = !empty($request["totalPrice"]) ? $request["totalPrice"] : "";
    $isDiscount = !empty($request["isDiscount"]) ? $request["isDiscount"] : "0";
    $discount = !empty($request["discount"]) ? $request["discount"] : "";
    $remarks = !empty($request["remarks"]) ? $request["remarks"] : "";

    // Validate required fields
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

    // Validate the reservation (ensure no duplicates with the same fullName, address, dateFrom, dateTo)
    $validationQuery = "SELECT * FROM `reservation` WHERE 
      `fullName` = '$fullName' AND
      `address` = '$address' AND
      `dateFrom` = '$dateFrom' AND
      `dateTo` = " . ($dateTo === null ? "NULL" : "'$dateTo'") . " AND
      `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Reservation,METHOD::CREATE);
    (new Validation($conn))->isOverlappingReservation(MODULE::Reservation,$timeFrom, $timeTo);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    // Count the total number of reservations
    $reservationCountQuery = "SELECT COUNT(*) as Total FROM reservation";
    $reservationCount = mysqli_fetch_assoc(mysqli_query($conn, $reservationCountQuery));
    $reservationPackageId = (int) $reservationCount['Total'] + 1;

    (new ReservationPackage($conn))->addOrUpdateRange($reservationPackageId, $reservationPackage);
    (new Transaction($conn))->addOrUpdateRange($reservationPackageId, $totalPrice);

    // Prepare the SQL statement
    $sql = "INSERT INTO `reservation` 
      (`fullName`, `address`, `contactNo`, `eventId`, `noOfGuest`, `dateFrom`, `dateTo`, `venueId`, `isDiscount`, `discount`, `totalPrice`, `remarks`) 
      VALUES ('$fullName', '$address', '$contactNo', '$eventId', '$noOfGuest', '$timeFrom', " . ($dateTo === null ? "NULL" : "'". $timeTo ."'") . ", '$venueId', '$isDiscount', '$discount', '$totalPrice', '$remarks')";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Return success response
    echo (new Response(
      status: 'success',
      message: HTTPResponseCode::$CREATED->message,
      data: null,  // Now data is user info
      code: HTTPResponseCode::$CREATED->code
    ))->toJson();
  }
} catch (Throwable $ex) {
  // Handle errors and exceptions
  echo (new Response(
    status: 'failed',
    message: $ex->getMessage() . '.',
    data: null,
    code: $ex->getCode(),
  ))->toJson();
}
?>
