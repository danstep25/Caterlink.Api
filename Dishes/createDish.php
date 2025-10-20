<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $name = !empty($request["name"]) ? $request["name"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";
    $ingredients = !empty($request["ingredients"]) ? json_encode($request["ingredients"]): "";
    $categoryId = !empty($request["categoryId"]) ? $request["categoryId"] : "";
    $price = !empty($request["price"]) ? $request["price"] : "";

    if (empty($name)) {
      array_push($errors, new ErrorResponse("Name is required"));
    }

    if (empty($description)) {
      array_push($errors, new ErrorResponse("Description is required"));
    }

    if (empty($categoryId)) {
      array_push($errors, new ErrorResponse("Category is required"));
    }

    if (empty($price)) {
      array_push($errors, new ErrorResponse("Price is required"));
    }

    $validationQuery = "SELECT * FROM `dish` WHERE 
      `name` = '$name' AND
      `description` = '$description' AND
      `ingredients` = '$ingredients' AND
      `categoryId` = '$categoryId' AND
      `price` = '$price' AND
      `isActive`
      ";

    (new Validation($conn, $validationQuery))->isValid(MODULE::Dish,METHOD::CREATE);

    if (count($errors) > 0) {
      $errorString = ErrorResponse::constructMessage($errors);
      return throw new Exception($errorString, code: HTTPResponseCode::$BAD_REQUEST->code);
    }

    $sql = "INSERT INTO `dish` 
      (`name`, `description`, `ingredients`, `categoryId`, `price`) 
      VALUES ('$name', '$description', '$ingredients', '$categoryId', '$price')";

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