<?php

class SalaryAdvance extends Controller
{
    public function index($request_number = null): void
    {
        currentUrl($_SERVER);
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/index/' . $request_number);
        } else {
            $payload = [];
            $payload['current_user'] = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['request_number'] = $request_number;
            $this->view('salary-advance/index', $payload);
        }
    }

    public function bulkRequests(string $bulk_request_number = null): void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/bulk-requests/' . $bulk_request_number);
        }
        $current_user = $payload['current_user'] = getUserSession();

        if (!(isCurrentManager($current_user->user_id) || isAssignedAsSecretary($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance/index/'.$bulk_request_number);
        }
        $payload['title'] = 'Bulk Salary Advance Applications';
        $payload['request_number'] = $bulk_request_number;
        $this->view('salary-advance/bulk-requests', $payload);
    }

    public function newBulkRequest(): void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/new-bulk-request/');
        }
        $current_user = getUserSession();
        if (!isSecretary($current_user->user_id)) {
            redirect('salary-advance/bulk-requests');
        }
        $payload['title'] = 'New Bulk Salary Advance Application';
        $request_number_parts = explode('-', genDeptRef($current_user->department_id, 'salary_advance', false));
        $payload['bulk_request_number'] = implode('-', [$request_number_parts[0], $request_number_parts[1], $request_number_parts[2]]);
        $this->view('salary-advance/new_bulk_request', $payload);
    }

    public function singleRequests($request_number = ''): void
    {
        $db = Database::getDbh();
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        $payload['request_number'] = $request_number;
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/single-requests/' . $request_number);
        }
        if (!(isCurrentManager($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentHR($current_user->user_id) || isCurrentGM($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance/index/' . $request_number);
        }
        if ($request_number && !$db->where('request_number', $request_number)->has('salary_advance')) {
            redirect('errors/index/404');
        }
        $this->view('salary-advance/single-requests', $payload);

    }

    public function print($id_salary_advance = null): void
    {
        $db = Database::getDbh();
        $payload['salary_advance'] = $db->where('id_salary_advance', $id_salary_advance)->objectBuilder()->getOne('salary_advance');
        $this->view('print-salary-advance', $payload);
    }

    public function uploadSalaries(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
            $fileName = filter_var($_FILES['uploadedFile']['name'], FILTER_SANITIZE_STRING);
            $fileSize = $_FILES['uploadedFile']['size'];
            $fileType = $_FILES['uploadedFile']['type'];
            $fileNameParts = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameParts));
            $allowedfileExtensions = array('xls', 'xlsx', 'csv');
            if (in_array($fileExtension, $allowedfileExtensions, true)) {
                $uploadFileDir = PATH_SALARIES;
                $dest_path = $uploadFileDir . FILE_NAME_SALARIES . '.xlsx';
                if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                    echo json_encode(['success' => false, 'error' => 'File upload failed!'], JSON_THROW_ON_ERROR, 512);
                } else {
                    echo json_encode(['success' => true], JSON_THROW_ON_ERROR, 512);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Unsupported file type!'], JSON_THROW_ON_ERROR, 512);
            }
        }
    }

    public function updateSalaries(): void
    {
        $inputFileName = PATH_SALARIES . FILE_NAME_SALARIES . '.xlsx';
        $db = Database::getDbh();
        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $staff_number = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $basic_salary = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $db->where('staff_number', $staff_number)->update('users', ['basic_salary' => $basic_salary]);
            }
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        }
    }

    public function exchangeRate(): void
    {
        $db = Database::getDbh();
        $exchange_rate = filter_var($_POST['exchange_rate'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (($_SERVER['REQUEST_METHOD'] === 'POST')) {
            $success = $db->where('prop', 'exchange_rate')->update('settings', ['value' => $exchange_rate]);
            if ($success) {
                echo json_encode(['success' => true], JSON_THROW_ON_ERROR, 512);
                return;
            }
        }
        echo json_encode(['success' => false], JSON_THROW_ON_ERROR, 512);
    }
}