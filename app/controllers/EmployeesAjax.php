<?php /** @noinspection ALL */

use Simple\json;

class EmployeesAjax extends Controller
{
    /**
     * ActionLists constructor.
     */
    public function __construct()
    {
        parent::__construct();
        /* if (!isLoggedIn()) {
             redirect('users/login');
         }*/
    }

    public function index()
    {
        // Get posts
        $current_user = getUserSession();
        $db = Database::getDbh();
        $ret = [];
        if (isset($_GET['user_id'])) {
            $ret = User::get(['user_id' => $_GET['user_id']]);
        } else {
            if (isSecretary($current_user->user_id)) {
                $ret = getMembersAssignedToSecretary($current_user->user_id);
            } else{
                $ret = $db->orderBy('first_name', 'ASC')
                    ->get('users');
            }
        }
        foreach ($ret as $key => &$value) {
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->department = getDepartment($value['user_id']);
            $value['name'] = $employee->name;
            $value['employee'] = $employee;
            $value['department'] = $employee->department;
            unset($value['password']);
        }
        echo json_encode($ret);
    }

    public function Create()
    {
    }

    /**
     * @param $id_salary_advance
     */
    public function Update($id_salary_advance)
    {
    }

    public function Destroy()
    {
    }
}