<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $supplierId =  !empty($request["supplierId"]) ? $request["supplierId"] : "";
    $name = !empty($request["name"]) ? $request["name"] : "";
    $contact = !empty($request["contact"]) ? $request["contact"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $address = !empty($request["address"]) ? $request["address"] : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($contact)) {
      array_push($errors, new ErrorResponse("Contact is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    if (empty($address)) {
      array_push($errors, new ErrorResponse("Address is required"));
    }

    $validationQuery = "SELECT * FROM `supplier` WHERE `supplierId` = $supplierId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Supplier, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `supplier` 
      SET `name` = '$name', 
      `contact` = '$contact',
      `description` = '$description',
      `address` = '$address',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `supplierId` = '$supplierId'

      ";

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