<?php

class Respuesta {
    public bool $success;
    public mixed $data=[];
    public ?string $errorCode;
    public ?string $errorMessage;

    public function __construct(bool $success, $data = null, $errorCode = null, $errorMessage = null) {
        $this->success = $success;
        $this->data = $data;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
}
