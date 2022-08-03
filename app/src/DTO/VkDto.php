<?php

namespace Arslav\Newbot\DTO;

class VkDto
{
    public $id;
    public $data;
    public $message;

    /**
     * @param $id
     * @param $data
     * @param $message
     */
    public function __construct($id, $data, $message)
    {
        $this->id = $id;
        $this->data = $data;
        $this->message = $message;
    }
}
