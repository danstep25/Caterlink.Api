<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $name = !empty($request["name"]) ? $request["name"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $supplierId = !empty($request["supplierId"]) ? $request["supplierId"] : "";
    $purchaseDate = !empty($request["purchaseDate"]) ? $request["purchaseDate"] : "";
    $expirationDate = !empty($request["expirationDate"]) ? $request["expirationDate"] : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("description is required"));
    }

    if (empty($supplierId)) {
      array_push($errors, new ErrorResponse("Supplier is required"));
    }

    if (empty($purchaseDate)) {
      array_push($errors, new ErrorResponse("Purchase date is required"));
    }

    if (empty($expirationDate)) {
      array_push($errors, new ErrorResponse("Expiration date is required"));
    }

    $validationQuery = "SELECT * FROM `ingredients` WHERE 
      `name` = '$name' AND
      `description` = '$description' AND
      `supplierId` = '$supplierId' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Ingredients,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "INSERT INTO `ingredients` 
      (`name`, `description`, `supplierId`, `purchaseDate`, `expirationDate`) 
      VALUES ('$name', '$description', '$supplierId', '$purchaseDate', '$expirationDate')";

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