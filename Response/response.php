<?php

class Response
{
  public string $status;
  public string $message;
  public mixed $data;
  public int $code;

  public function __construct(
    string $status = 'success',
    string $message = '',
    mixed $data = null,
    int $code = 200
  ) {
    $this->status = $status;
    $this->message = $message;
    $this->data = $data;
    $this->code = $code;
  }

  public function toJson(): string
  {
    // return json_encode($this->code);
    http_response_code($this->code);
    return json_encode([
      'status' => $this->status,
      'message' => $this->message,
      'data' => $this->data,
      'code' => $this->code
    ]);
  }
}

