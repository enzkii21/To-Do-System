<?php

namespace App\DTO;


class DTOTask{

    public $dto_task;

    /**
     * Get the value of dto_task
     */ 
    public function getDto_task()
    {
        return $this->dto_task;
    }

    /**
     * Set the value of dto_task
     *
     * @return  self
     */ 
    public function setDto_task($dto_task)
    {
        $this->dto_task = $dto_task;

        return $this;
    }
}
?>