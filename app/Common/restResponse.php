<?php

namespace App\Common;

use HTML5_TreeBuilder;

use function PHPUnit\Framework\returnSelf;

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

        if ($this->token === true) {
            $responseArray['token'] = csrf_token();
        }

        return $responseArray;
    }

    public function toJson()
    {
        return json_encode($this->toArray(), $this->getStatusCode());
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public static function createErrorResponse($status, $message, $traceId = null, $options = [])
    {
        $exitAuto = true;

        if (isset($options['exitAuto'])) {
            $exitAuto = $options['exitAuto'] == false ? false : true;
        }

        $response = new self(null, $status, $message, $traceId);
        return !$exitAuto ? $response : response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    }

    public static function createGenericResponse($data, $status, $message, $traceId = null, $options = [])
    {
        $exitAuto = true;

        if (isset($options['exitAuto'])) {
            $exitAuto = $options['exitAuto'] == false ? false : true;
        }

        $response = new self($data, $status, $message, $traceId);
        return !$exitAuto ? $response : response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    }

    public static function createSuccessResponse($data, $status, $options = [])
    {
        $message = '';
        $token = false;
        $exitAuto = true;

        if (isset($options['message'])) {
            $message = $options['message'];
        }
        if (isset($options['token'])) {
            $token = $options['token'] === true ? true : false;
        }
        if (isset($options['exitAuto'])) {
            $exitAuto = $options['exitAuto'] == false ? false : true;
        }

        $response = new self($data, $status, $message, null, $token);
        return !$exitAuto ? $response : response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    }

    public static function createTesteResponse($data = [], $message = 'Retorno teste', $options = [])
    {
        $exitAuto = true;

        if (isset($options['exitAuto'])) {
            $exitAuto = $options['exitAuto'] == false ? false : true;
        }

        $response = new self($data, 422, $message);
        return !$exitAuto ? $response : response()->json($response->toArray(), $response->getStatusCode())->throwResponse();
    }
}
