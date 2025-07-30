<?php

class Response
{
  public string $status;
  public string $message;
  public mixed $data;
  public int $code;
  public int $totalRecords = 0;
  public int $totalPages = 0;
  public int $pageIndex = 1;
  public int $pageSize = 10;

    public function __construct(
    string $status = 'success',
    string $message = '',
    mixed $data = null,
    int $code = HTTPResponseCode::SUCCESS->code,
    int $totalRecords = 0,
    int $totalPages = 0,
    int $pageIndex = 1,
    int $pageSize = 10
  ) {
    $this->status = $status;
    $this->message = $message;
    $this->data = $data;
    $this->code = $code;
    $this->totalRecords = $totalRecords;
    $this->totalPages = $totalPages;
    $this->pageIndex = $pageIndex;
    $this->pageSize = $pageSize;
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

  public function toPaginateJson(): string
  {
    // return json_encode($this->code);
    http_response_code($this->code);
    return json_encode([
      'status' => $this->status,
      'message' => $this->message,
      'data' => $this->data,
      'code' => $this->code,
      'totalRecords' => $this->totalRecords,
      'totalPages' => $this->totalPages,
      'pageIndex'=> $this->pageIndex,
      'pageSize'=> $this->pageSize
    ]);
  }
}

