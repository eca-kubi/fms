<?php

use DbModels\VisitorAccessForm\AccessLevelDbModel;
use DbModels\VisitorAccessForm\VisitorAccessFormDbModel;
use ViewModels\VisitorAccessForm\DashboardViewModel;
use ViewModels\VisitorAccessForm\PrintViewModel;
use ViewModels\VisitorAccessForm\SectionAVisitorDetailsViewModel;
use ViewModels\VisitorAccessForm\SectionBAccessLevelViewModel;
use ViewModels\VisitorAccessForm\SectionCViewModel;
use ViewModels\VisitorAccessForm\SectionDViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\AccessLevelCollection;
use VisitorAccessForm\Approval;
use VisitorAccessForm\DashboardViewFilter;
use VisitorAccessForm\EmailComposerHelper;
use VisitorAccessForm\Entities\AccessLevel;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;
use VisitorAccessForm\ValidationHelper;

class VisitorAccessForm extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access-form');
        }

        $this->dashboard();
    }

    public function a(?int $form_id = null)
    {
        redirect("visitor-access-form/section-a-visitor-details/$form_id");
    }

    public function sections(int $form_id = 0, string $target_section = '')
    {
        if (!VisitorAccessFormDbModel::hasForm($form_id)) {
            redirect('errors/index/404');
        }

        if (!isLoggedIn()) {
            redirect("users/login/visitor-access-form/sections/$form_id/$target_section");
        }
        $current_user = getUserSession();

        if (!(VisitorAccessFormDbModel::isOwner($current_user->user_id, $form_id) ||
            isCurrentHR($current_user->user_id) ||
            isCurrentSecurityManager($current_user->user_id) ||
            isCurrentGM($current_user->user_id)
        )) {
            redirect('errors/index/' . ErrorCodes::PERMISSION_DENIED);
        }
        $visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($form_id);
        $access_level_collection = AccessLevelDbModel::getWithFormId($form_id);
        $view_model = new SectionsViewModel($visitor_access_form);
        $view_model->title = 'Visitor Access Form (' . $view_model->visitor_access_form->document_id . ')';
        $view_model->section_a = new SectionAVisitorDetailsViewModel();
        $view_model->section_b = new SectionBAccessLevelViewModel(new AccessLevelCollection());
        $view_model->section_c = new SectionCViewModel(new User($visitor_access_form->site_sponsor_id));
        $view_model->section_d = new SectionDViewModel(null);
        $view_model->section_a->department_approvers = getApproversForDepartment($view_model->visitor_access_form->department_id);
        $view_model->section_b->access_levels = $access_level_collection->isEmpty() ? AccessLevel::getDefaultAccessLevels($form_id) : $access_level_collection;

        switch ($target_section) {
            case SECTION_A_VISITOR_DETAILS:
                $this->SectionAVisitorDetails($form_id);
                break;
            case SECTION_B_ACCESS_LEVEL:
                $this->SectionBAccessLevel($form_id);
                break;
            case SECTION_C_SITE_SPONSORS_APPROVAL:
                $this->SectionCSiteSponsorsApproval($form_id);
                break;
            case SECTION_D_SITE_ACCESS_APPROVAL:
                $this->SectionDSiteAccessApproval($form_id);
                break;
            default:
                $this->view('visitor-access-form/sections', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
        }
    }

    public function sectionAVisitorDetails(?int $form_id = null)
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access-form/section-a-visitor-details');
        }
        $current_user = getUserSession();
        $view_model = new SectionsViewModel(new VisitorAccessFormEntity([]));
        $view_model->section_a = new SectionAVisitorDetailsViewModel();
        $view_model->section_a->department_approvers = getApproversForDepartment($current_user->department_id);
        $validation_helper = new ValidationHelper();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Trim post data to remove surrounding whitespace
            $_POST = array_map('trim', $_POST);
            //Sanitize
            //filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if ($form_id && VisitorAccessFormDbModel::isOwner($current_user->user_id, $form_id)) {
                // update
                $view_model->visitor_access_form = new VisitorAccessFormEntity($_POST);
                if ($validation_helper->validateSectionA($view_model->visitor_access_form)->getErrors()->isEmpty() && VisitorAccessFormDbModel::update($form_id, $_POST)) {
                    flash('flash_sections', 'Success!');
                    redirect("visitor-access-form/sections/$form_id");
                }
            }
            if (!$form_id) {
                // insert new request
                $view_model->visitor_access_form = new VisitorAccessFormEntity($_POST);
                $view_model->visitor_access_form->department_id = $current_user->department_id;
                $view_model->visitor_access_form->originator_id = $current_user->user_id;
                $view_model->visitor_access_form->date_raised = today(DATE_FORMAT_YMD);
                $view_model->visitor_access_form->document_id = generateDocumentID($current_user->department_id);
                $view_model->visitor_access_form->section_a_completed = true;
                if ($validation_helper->validateSectionA($view_model->visitor_access_form)->getErrors()->isEmpty()) {
                    if ($ret = VisitorAccessFormDbModel::insert($view_model->visitor_access_form->jsonSerialize())) {
                        try {
                            if ($insertId = Database::getDbh()->getInsertId()) {
                                // send email
                                $ret = $ret && EmailComposerHelper::sectionA($current_user, $view_model->visitor_access_form, URL_ROOT . "/visitor-access-form/sections/$insertId");
                                $ret = $ret && VisitorAccessFormDbModel::update($insertId, $view_model->visitor_access_form->jsonSerialize());
                                if ($ret) {
                                    flash('flash_sections', 'Success!');
                                    redirect("visitor-access-form/sections/$insertId");
                                }
                            } else {
                                flash("flash_" . $view_model->section_a->description_code, 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                            }
                        } catch (Exception $e) {
                        }
                        $view_model->visitor_access_form = new VisitorAccessFormEntity($_POST);
                        flash("flash_" . $view_model->section_a->description_code, 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                        $this->view('visitor-access-form/section_a_visitor_details', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
                    }
                }
            }
        } else {
            if ($form_id) {
                if (VisitorAccessFormDbModel::isOwner($current_user->user_id, $form_id)) {
                    $view_model->visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($form_id);
                    $this->view('visitor-access-form/section_a_visitor_details', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
                    return;
                } else {
                    redirect('visitor-access-form/' . SECTION_A_VISITOR_DETAILS);
                }
            }
            $this->view('visitor-access-form/section_a_visitor_details', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
        }
    }

    public function sectionBAccessLevel(int $form_id)
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access-form/sections' . $form_id);
        }
        $current_user = getUserSession();
        $validation_helper = new ValidationHelper();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize
            //filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if ($form_id && VisitorAccessFormDbModel::isOwner($current_user->user_id, $form_id)) {
                $visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($form_id);
                $accessLevelCollection = AccessLevelCollection::createFromArrayValues($_POST['access_level']);
                //$filtered = $accessLevelCollection->filterCollection(fn(AccessLevel $al) => $al->bus_hrs == 1 || $al->twenty_four_seven == 1);
                if ($validation_helper->validateSectionB($accessLevelCollection)->getErrors()->isEmpty()) {
                    // update
                    $ret = AccessLevelDbModel::updateMultiple($accessLevelCollection->jsonSerialize());
                    if ($ret) {
                        $visitor_access_form->section_b_completed = true;
                        $ret = EmailComposerHelper::sectionB($current_user, $visitor_access_form, URL_ROOT . "/visitor-access-form/sections/$form_id");
                        if ($ret) {
                            $ret = VisitorAccessFormDbModel::update($form_id, $visitor_access_form->jsonSerialize());
                        }
                        if ($ret) {
                            flash('flash_sections', 'Success!');
                        } else {
                            flash('flash_sections', 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                        }
                    } else {
                        flash('flash_sections', 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                    }
                    redirect("visitor-access-form/sections/$form_id");
                }
            } else {
                flash('flash_sections', 'A required field is missing!', HTMLHelper::ALERT_DANGER_CLASS);
            }
        } else {
            redirect("visitor-access-form/sections/$form_id");
        }
    }

    public function sectionCSiteSponsorsApproval(int $form_id)
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access-form/sections' . $form_id);
        }
        $current_user = getUserSession();
        $validation_helper = new ValidationHelper();
        $visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($form_id);
        $formUrl = URL_ROOT . "/visitor-access-form/sections/$form_id";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($current_user->user_id !== $visitor_access_form->site_sponsor_id) {
                redirect("visitor-access-form/sections/$form_id");
            }
            if (!$validation_helper->validateSectionC()->getErrors()->isEmpty()) {
                flash('flash_sections', 'A required field is missing!', HTMLHelper::ALERT_DANGER_CLASS);
            } else {
                $visitor_access_form->approved_by_site_sponsor = $_POST['approved_by_site_sponsor'];
                $visitor_access_form->site_sponsor_approval_date = today(DATE_FORMAT_YMD);
                $visitor_access_form->section_c_completed = true;
                $visitor_access_form->site_sponsor_comment = $_POST['site_sponsor_comment'];
                $approval = new Approval(new User($current_user->user_id), today(DATE_FORMAT_YMD), $visitor_access_form->site_sponsor_comment, $visitor_access_form->approved_by_site_sponsor, true);
                $ret = EmailComposerHelper::sectionC($visitor_access_form, $approval, $formUrl);
                $ret = $ret && VisitorAccessFormDbModel::update($form_id, $visitor_access_form->jsonSerialize());
                if ($ret) {
                    flash('flash_sections', 'Success!');
                } else {
                    flash('flash_sections', 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                }
            }
        }
        redirect("visitor-access-form/sections/$form_id");
    }

    public function sectionDSiteAccessApproval(int $form_id)
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access-form/sections' . $form_id);
        }
        $approval = null;
        $current_user = getUserSession();
        $validation_helper = new ValidationHelper();
        $visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($form_id);
        $formUrl = URL_ROOT . "/visitor-access-form/sections/$form_id";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$validation_helper->validateSectionD()->getErrors()->isEmpty()) {
                flash('flash_sections', 'A required field is missing!', HTMLHelper::ALERT_DANGER_CLASS);
                redirect("visitor-access-form/sections/$form_id");
            }
            switch ($current_user->user_id) {
                case $visitor_access_form->hr_approver_id:
                case getCurrentHR():
                    $visitor_access_form->hr_approver_id = $current_user->user_id;
                    $visitor_access_form->approved_by_hr = $_POST['approved'];
                    $visitor_access_form->hr_approval_date = today(DATE_FORMAT_YMD);
                    $visitor_access_form->hr_comment = $_POST['comment'];
                    $visitor_access_form->hr_completed = true;
                    break;
                case $visitor_access_form->security_approver_id:
                case getCurrentSecurityManager():
                    $visitor_access_form->security_approver_id = $current_user->user_id;
                    $visitor_access_form->security_approval_date = today(DATE_FORMAT_YMD);
                    $visitor_access_form->security_approver_comment = $_POST['comment'];
                    $visitor_access_form->approved_by_security = $_POST['approved'];
                    $visitor_access_form->security_manager_completed = true;
                    break;
                case $visitor_access_form->gm_id:
                case getCurrentGM():
                    $visitor_access_form->gm_id = $current_user->user_id;
                    $visitor_access_form->gm_comment = $_POST['comment'];
                    $visitor_access_form->gm_approval_date = today(DATE_FORMAT_YMD);
                    $visitor_access_form->approved_by_gm = $_POST['approved'];
                    $visitor_access_form->gm_completed = true;
                    if ($visitor_access_form->security_manager_completed && $visitor_access_form->hr_completed && $visitor_access_form->gm_completed) {
                        $visitor_access_form->section_d_completed = true;
                    }
                    break;
                default:
                    redirect("visitor-access-form/sections/$form_id");
            }
            if (isCurrentHR($current_user->user_id) || isCurrentGM($current_user->user_id) || isCurrentSecurityManager($current_user->user_id)) {
                $approval = new Approval(new User($current_user->user_id), today(DATE_FORMAT_YMD), $_POST['comment'], $_POST['approved'], true);
                $ret = EmailComposerHelper::SectionD($visitor_access_form, $approval, $formUrl);
                $ret = $ret && VisitorAccessFormDbModel::update($form_id, $visitor_access_form->jsonSerialize());
                if ($ret) {
                    flash('flash_sections', 'Success!');
                } else {
                    flash('flash_error', 'An error occurred!', HTMLHelper::ALERT_DANGER_CLASS);
                }
            }
        }
        redirect("visitor-access-form/sections/$form_id");
    }

    public function dashboard(string $filter = '')
    {
        if (!isLoggedIn()) {
            redirect("users/login/visitor-access-form/dashboard");
        }
        $view_model = new DashboardViewModel();
        $current_user = getUserSession();
        //$pending_approvals = new VisitorAccessFormCollection();
        //$completed_approvals = new VisitorAccessFormCollection();
        switch ($current_user->user_id) {
            case getCurrentHR():
            case getCurrentGM():
            case getCurrentSecurityManager():
            $pending_approvals = VisitorAccessFormDbModel::getPendingApprovals();
            $completed_approvals = VisitorAccessFormDbModel::getCompletedApprovals();
            break;
            default:
                $pending_approvals = VisitorAccessFormDbModel::getPendingApprovalsForDepartment($current_user->department_id);
                $completed_approvals = VisitorAccessFormDbModel::getCompletedApprovalsForDepartment($current_user->department_id);
        }

        switch ($filter) {
            case 'pending':
                $view_model->view_filters = [new DashboardViewFilter(
                    $pending_approvals,
                    'Pending Approvals',
                    'warning',
                    'pending_approvals'
                )];
                break;
            case 'completed':
                $view_model->view_filters = [
                    new DashboardViewFilter(
                        $completed_approvals,
                        'Completed Approvals',
                        'success',
                        'completed_approvals'
                    )];
                break;
            default:
                $view_model->view_filters = [new DashboardViewFilter(
                    $pending_approvals,
                    'Pending Approvals',
                    'warning',
                    'pending_approvals'
                ), new DashboardViewFilter(
                    $completed_approvals,
                    'Completed Approvals',
                    'success',
                    'completed_approvals'
                )];
        }
        $this->view('visitor-access-form/dashboard', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
    }

    public function startPage()
    {
        $payload['title'] = 'Start Page';
        $this->view('pages/start-page', []);
    }

    public function print(int $form_id)
    {
        /*if (!isLoggedIn()) {
            redirect("users/login/visitor-access-form/print/$form_id");
        }*/

        $view_model = new PrintViewModel($form_id, 'Visitor Access Form');
        $view_model->print = true;

        $this->view('visitor-access-form/print', ['view_model' => $view_model, 'custom_scripts' => ['visitor-access-form']]);
    }



    public function test()
    {
        /*$alevel = new AccessLevel();
        $alevel->area = AccessLevel::GENERAL_SITE;
        $alevel->bus_hrs = false;
        $alevel->twenty_four_seven = false;
        $alevel2 = new AccessLevel((array)$alevel);
        $alevel->access_level_id = 1;
        $alevel->visitor_access_form_id = 200;
        $alevel2->access_level_id = 2;
        $alevel2->visitor_access_form_id = 100;
        $collection = new AccessLevelCollection($alevel, $alevel2);
        AccessLevelDbModel::updateMultiple($collection->jsonSerialize());
        AccessLevel::getDefaultAccessLevels();*/
        // $approver = new User(getCurrentHR());
        //EmailComposerHelper::sectionD(VisitorAccessFormDbModel::getEntitySingle(10), new Approval($approver, now(), 'ok', true, true), 'https://local.arlgh.com/forms/visitor-access-form/sections/6');
        /*$template = new \TemplateModels\EmailTemplateModel('Test', 'Content');
        echo \VisitorAccessForm\EmailComposerHelper::applyTemplate($template);
      */
//\VisitorAccessForm\EmailComposerHelper::sectionA(new User(getUserSession()->user_id), VisitorAccessFormDbModel::getWithId(6)->getSingle(), 'http://local.arlgh.com/forms');

        //$dbm = new VisitorAccessFormDbModel();
        //$dbm2 = new EmailDbModel();
        //$dbm->save();
        //$ret = VisitorAccessFormDbModel::getPendingApprovals();
        //Approval::getStatusAsString(null);
        /*        try {
                    Browsershot::url(URL_ROOT . '/visitor-access-form/print/1')
                        ->timeout(60)
                        ->bodyHtml();
                } catch (CouldNotTakeBrowsershot $e) {
                }*/
    }
}
