<?php

namespace App\DAO;

use App\DTO\DTOTask;
use Illuminate\Support\Facades\DB;


class DAOTask{
    
    public function getAllTaskList(){
        $user_id = $_COOKIE['UserID'];
        $data = DB::connection('mysql')
            ->table('Task')
            ->whereNull('DeletedDate')
            ->where('UserID', $user_id)
            ->orderBy('TaskID', 'desc')
            ->get();

        $dto_task = new DTOTask();
        $dto_task->setDto_task($data);
        return $dto_task->getDto_task();

        
    }


    public function getLatestID(){
        $data = DB::connection('mysql')
            ->table('task')
            ->selectRaw('MAX(TaskID) as MaxID')
            ->value('MaxID');
        $dto_task = new DTOTask();
        $dto_task->setDto_task($data);
        return $dto_task->getDto_task();
    }


    public function insertTaskList($req,$id){
        $id++;
        $user_id =  $_COOKIE['UserID'];
        DB::connection('mysql')->table('task')->insert([
            'TaskID' => $id,
            'UserID' => $user_id,
            'TaskTitle' => $req->TaskTitle,
            'Description' => $req->Description,
            'CreatedDate' => DB::raw('NOW()'),
            'UpdatedDate' => DB::raw('NOW()'),
            'DeletedDate' => null,
            'UpdatedByID' => null
        ]);

        return 'Inserted Successfully';
    }



    public function updateTaskList($req){
        DB::connection('mysql')
            ->table('task')
            ->where('TaskID', $req->TaskID)
            ->update([
                'TaskTitle' => $req->TaskTitle,
                'Description' => $req->Description,
                'UpdatedDate' => DB::raw('NOW()')
            ]);

        return 'Updated Successfully';
    }



    public function updateDeleteTaskList($req){
        DB::connection('mysql')
            ->table('task')
            ->where('TaskID', $req->TaskID)
            ->update([
                'DeletedDate' => DB::raw('NOW()'),
                'UpdatedDate' => DB::raw('NOW()')
            ]);

        return 'Deleted Successfully';
    }
}
?>