<?php

class HttpStatus
{
    public string $message;
    public int $code;

    public function __construct(string $message, int $code)
    {
        $this->message = $message;
        $this->code = $code;
    }
}

class HTTPResponseCode
{
    public static HttpStatus $SUCCESS;
    public static HttpStatus $CREATED;
    public static HttpStatus $NO_CONTENT;
    public static HttpStatus $BAD_REQUEST;
    public static HttpStatus $UNAUTHORIZED;
    public static HttpStatus $FORBIDDEN;
    public static HttpStatus $NOT_FOUND;
    public static HttpStatus $CONFLICT;
    public static HttpStatus $UNPROCESSABLE_ENTITY;
    public static HttpStatus $INTERNAL_SERVER_ERROR;

    public static function init(): void
    {
        self::$SUCCESS = new HttpStatus("Success", 200);
        self::$CREATED = new HttpStatus("Created", 201);
        self::$NO_CONTENT = new HttpStatus("No Content", 204);
        self::$BAD_REQUEST = new HttpStatus("Bad Request", 400);
        self::$UNAUTHORIZED = new HttpStatus("Unauthorized", 401);
        self::$FORBIDDEN = new HttpStatus("Forbidden", 403);
        self::$NOT_FOUND = new HttpStatus("Not Found", 404);
        self::$CONFLICT = new HttpStatus("Conflict", 409);
        self::$UNPROCESSABLE_ENTITY = new HttpStatus("Unprocessable", 422);
        self::$INTERNAL_SERVER_ERROR = new HttpStatus("Internal Server Error", 500);
    }
}

?>
