<?php
class ApiResponse {
    public $success;
    public $data;
    public $message;
    public $timestamp;

    public function __construct($success = true, $data = null, $message = '') {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function toArray() {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'timestamp' => $this->timestamp
        ];
    }
}