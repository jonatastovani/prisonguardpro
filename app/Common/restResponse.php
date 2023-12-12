<?php

namespace App\Common;

class RestResponse
{
    private $status;
    private $message;
    private $data;

    public function __construct($data, $status, $message = '')
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'data' => $this->data,
            'status' => $this->status,
            'message' => $this->message,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ];
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
        $response = new self(null, $status, $message);

        if ($traceId) {
            $response->addTraceId($traceId);
        }

        return $response;
    }

    public static function createGenericResponse($data, $status, $message, $traceId = null)
    {
        $response = new self(["resource" => $data], $status, $message);

        if ($traceId) {
            $response->addTraceId($traceId);
        }

        return $response;
    }

    public static function createSuccessResponse($data, $status, $message='')
    {
        return new self($data, $status, $message);
    }

    private function addTraceId($traceId)
    {
        $this->data['trace_id'] = $traceId;
    }
}
