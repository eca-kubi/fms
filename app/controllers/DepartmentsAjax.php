<?php /** @noinspection ALL */

class DepartmentsAjax extends Controller
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
        if (isset($_GET['department_id'])) {
            $ret = Database::getDbh()->where('department_id', $_GET['department_id'])
                ->getValue('departments', 'department', null);
        } else {
            $ret = Database::getDbh()->arrayBuilder()
                ->getValue('departments', 'department', null);
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

    public function transformArrayData($ret) {
        $temp = [];
        foreach ($ret as $item => $value) {
            $temp = $value;
        }
        return $temp;
    }

}