<?php
namespace App\DAO;

use App\DTO\DTOUserList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class DAOUserList{
    

    public function getUserList($req){

       if($req->username != '' && $req->password != ''){
            $username = $req->username;
            $password = $req->password;
       }else{
        return 'Empty Text';
       }

       $data = DB::connection('mysql')
        ->table('userlist')
        ->where('username', $username)
        ->get();


        
         // Check if user exists
        if (count($data) == 0) {
            return 'No Data Found!';
        }

         // Get the first user record
        $user = $data[0];


        if (!Hash::check($password, $user->Password)) {
            // Add debugging information
            Log::debug('Password verification failed', [
                'input_password' => $password,
                'stored_hash' => $user->Password,
                'user_data' => $user
            ]);
            return 'No Data Found!';
        }

        setcookie('UserID',$data[0]->UserID);
        setcookie('FirstName',$data[0]->FirstName);
        setcookie('MiddleName',$data[0]->MiddleName);
        setcookie('LastName',$data[0]->LastName);

        
        $dto_userlist = new DTOUserList();
        $dto_userlist->setDto_userlist($data);
        return $dto_userlist->getDto_userlist();
    }
}
?>