<?php
include("../Config/required.php");

try {
  if (!empty($input["request"])) {
    $request = $input["request"];

    $username = !empty($request["username"]) ? $request["username"] : "";
    $action = !empty($request["action"]) ? $request["action"] : "";
    $module = !empty($request["module"]) ? $request["module"] : "";
    $description = !empty($request["description"]) ? $request["description"] : "";

    if (empty($username)) {
      array_push($errors, new ErrorResponse("Username is required"));
    }

    if (empty($action)) {
      array_push($errors, new ErrorResponse("Action is required"));
    }

    if (empty($module)) {
      array_push($errors, new ErrorResponse("Module is required"));
    }
    
    if(is_array($description) > 0) {
      parse_str(http_build_query($description), $arr);
      $pairs = [];

      foreach ($arr as $key => $value) {
          $pairs[] = "$key=$value";
      }

      $description = implode(', ', $pairs);
    }

    $sql = "INSERT INTO `audit` 
      (`username`, `action`, `module`, `description`) 
      VALUES ('$username', '$action', '$module', '$description')";

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