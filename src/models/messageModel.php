<?php

class messageModel
{
    private string $key = "message";
    private string $value;

    public function __construct(int $truthy, string $customValue) {
        if($truthy == 1) $this->value = "success";
        else if($truthy == 0) $this->value = "failure";
        else $this->value = $customValue;
    }
    
    public function getValue() {
        return $this->value;
    }

    public function getKey() {
        return $this->key;
    }
}


?>