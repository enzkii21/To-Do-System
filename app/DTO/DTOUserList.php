<?php
namespace App\DTO;

class DTOUserList{
    public $dto_userlist;

    /**
     * Get the value of dto_userlist
     */ 
    public function getDto_userlist()
    {
        return $this->dto_userlist;
    }

    /**
     * Set the value of dto_userlist
     *
     * @return  self
     */ 
    public function setDto_userlist($dto_userlist)
    {
        $this->dto_userlist = $dto_userlist;

        return $this;
    }
}
?>