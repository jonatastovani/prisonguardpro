<?php

namespace app\Common;

class restResponse {
    private $data;
    private $status;
    private $message;

    public function __construct($data, $status, $message = '') {
        $this->data = $data;
        $this->status = $status;
        $this->message = $message;
    }

    public function toJson() {
        return json_encode(array(
            'data' => $this->data,
            'status' => $this->status,
            'message' => $this->message
        ));
    }

    public function getStatusCode() : int {
        return $this->status;
    }

    public function getData() : ?array {
        return $this->data;
    }
}
