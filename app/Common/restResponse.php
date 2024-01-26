<?php

namespace App\Common;

class RestResponse
{
    private $status;
    private $message;
    private $data;
    private $traceId;
    private $token;

    public function __construct($data, $status, $message = null, $traceId = null, $token = false)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->traceId = $traceId;
        $this->token = $token;
    }

    public function toArray()
    {
        $responseArray = [
            // 'data' => $this->data,
            'status' => $this->status,
            // 'message' => $this->message,
            'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
        ];

        if ($this->data) {
            $responseArray['data'] = $this->data;
        }

        if ($this->message) {
            $responseArray['message'] = $this->message;
        }

        if ($this->traceId) {
            $responseArray['trace_id'] = $this->traceId;
        }

        if ($this->token) {
            $responseArray['token'] = csrf_token();
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

    public static function createSuccessResponse($data, $status, $options = [])
    {
        $message = '';
        $token = false;

        if (isset($options['message'])) {
            $message = $options['message'];
        }
        if (isset($options['token'])) {
            $token = $options['token'] === true ? true : false;
        }

        return new self($data, $status, $message, null, $token);
    }

    public static function createTesteResponse($data = [], $message = 'Retorno teste')
    {
        $response = new self($data, 422, $message);

        return response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    }
}
