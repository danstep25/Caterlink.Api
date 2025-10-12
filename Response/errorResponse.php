<?php

class ErrorResponse{
  public string $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public static function constructMessage($errors): string{
    $errorMessages = array_map(function ($error) {
        return $error->message;
      }, $errors);

      return implode(", ", $errorMessages);
  }
}
?>