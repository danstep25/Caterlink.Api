<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $foodPackageId =  !empty($request["foodPackageId"]) ? $request["foodPackageId"] : "";
    $name = !empty($request["name"]) ? $request["name"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $dishes = !empty($request["dishes"]) ? json_encode($request["dishes"]) : "";
    $price = !empty($request["price"]) ? json_encode($request["price"]) : "";
    $pax = !empty($request["pax"]) ? json_encode($request["pax"]) : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    if (empty($dishes)) {
      array_push($errors, new ErrorResponse("Dishes are required"));
    }

    if (empty($price)) {
      array_push($errors, new ErrorResponse("Price is required"));
    }

    if (empty($pax)) {
      array_push($errors, new ErrorResponse("Pax is required"));
    }

    $validationQuery = "SELECT * FROM `foodpackage` WHERE `foodPackageId` = $foodPackageId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::FoodPackage, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `foodpackage` 
      SET `name` = '$name', 
      `description` = '$description',
      `dishes` = '$dishes',
      `pax` = '$pax',
      `price` = '$price',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `foodPackageId` = '$foodPackageId'

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