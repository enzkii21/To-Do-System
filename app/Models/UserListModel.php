<?php

namespace App\Models;

use App\DAO\DAOUserList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserListModel extends Model
{
    public function getUserList($req){
        $dao_userlist = new DAOUserList();
        return $dao_userlist->getUserList($req);
    }
}
