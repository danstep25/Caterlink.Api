<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $dishId =  !empty($request["dishId"]) ? $request["dishId"] : "";
    $name = !empty($request["name"]) ? $request["name"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $ingredients = !empty($request["ingredients"]) ? json_encode($request["ingredients"]) : "";
    $categoryId = !empty($request["categoryId"]) ? $request["categoryId"] : "";
    $price = !empty($request["price"]) ? json_encode($request["price"]) : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    if (empty($ingredients)) {
      array_push($errors, new ErrorResponse("Ingredients is required"));
    }

    if (empty($categoryId)) {
      array_push($errors, new ErrorResponse("Category is required"));
    }

    if (empty($price)) {
      array_push($errors, new ErrorResponse("Price is required"));
    }

    $validationQuery = "SELECT * FROM `dish` WHERE `dishId` = $dishId AND `isActive`";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Dish, METHOD::UPDATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "UPDATE `dish` 
      SET `name` = '$name', 
      `description` = '$description',
      `ingredients` = '$ingredients',
      `categoryId` = '$categoryId',
      `price` = '$price',
      `updatedAt` = CURRENT_TIMESTAMP
      WHERE `dishId` = '$dishId'

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