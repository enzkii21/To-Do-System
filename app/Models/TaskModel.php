<?php

namespace App\Models;

use App\DAO\DAOTask;
use App\DAO\DAOUserList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    public function getAllTaskList(){
        $dao_tasklist = new DAOTask();
        return $dao_tasklist->getAllTaskList();
    }

    public function UpdateInsertTaskList($req){
        $dao_tasklist = new DAOTask();
        if($req->method == 'ADD'){
            
            $latestID = $dao_tasklist->getLatestID();
            return $dao_tasklist->insertTaskList($req,$latestID);
        }else if($req->method == 'EDIT'){
            return $dao_tasklist->updateTaskList($req);
        }else if($req->method == 'DELETE'){
            return $dao_tasklist->updateDeleteTaskList($req);
        }
    }
}
