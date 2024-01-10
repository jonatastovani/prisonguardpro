<?php

namespace App\Common;

class RestResponse
{
    private $status;
    private $message;
    private $data;
    private $traceId;

    public function __construct($data, $status, $message = '', $traceId = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->traceId = $traceId;
    }

    public function toArray()
    {
        $responseArray = [
            'data' => $this->data,
            'status' => $this->status,
            'message' => $this->message,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ];

        if ($this->traceId) {
            $responseArray['trace_id'] = $this->traceId;
        }

        return $responseArray;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public static function createErrorResponse($status, $message, $traceId = null)
    {
        return new self(null, $status, $message, $traceId);
    }

    public static function createGenericResponse($data, $status, $message, $traceId = null)
    {
        return new self($data, $status, $message, $traceId);
    }

    public static function createSuccessResponse($data, $status, $message = '')
    {
        return new self($data, $status, $message);
    }

}
