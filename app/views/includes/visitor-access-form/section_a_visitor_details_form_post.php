<?php

use ViewModels\VisitorAccessForm\SectionAVisitorDetailsViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\IdentificationType;
use VisitorAccessForm\VisitorType;
use VisitorAccessForm\VisitorCategory;

/**
 * @var SectionsViewModel $view_model
 */
$section_a = $view_model->getSectionA();
$visitor_access_form = $view_model->visitor_access_form;
$section_a->setHtmlFormId();
?>

<form action="<?php echo URL_ROOT .'/visitor_access_form/' . SECTION_A_VISITOR_DETAILS . "/$visitor_access_form->visitor_access_form_id" ?>" method="POST" id="<?php echo SectionAVisitorDetailsViewModel::getHtmlFormId() ?>" role="form" data-toggle="validator"
      enctype="multipart/form-data">
    <div class="row p-2 border">
        <fieldset class="w-100">
            <h6 class="text-bold font-italic">
                <a href="#section_a" data-toggle="collapse">
                    <i class="fa fa-minus"></i> <?php echo $view_model->title ?>
                    <span class="text-muted">(To be Completed by - Originator)</span>
                </a>
            </h6>
            <div id="section_a" class="collapse show section row">
                <!-- Visitor's Name -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="full_name" class="col-sm-4 text-sm-right">Full Name</label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="1"
                                   class="form-control"
                                   value="<?php echo $visitor_access_form->full_name?? '' ?>"
                                   aria-describedby="helpId" placeholder="" id="full_name"
                                   name="full_name" required/>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Visitor's Company -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="company" class="col-sm-4 text-sm-right">Company</label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="1"
                                   class="form-control"
                                   value="<?php echo $visitor_access_form->company?? '' ?>"
                                   aria-describedby="helpId" placeholder="" id="company" name="company"
                                   required/>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Visitor Type -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="visitor_type" class="col-sm-4 text-sm-right">Visitor Type</label>
                        <div class="col-sm-8">
                            <select class="form-control bs-select has-other-type" tabindex="2"
                                    data-none-selected-text="Select Visitor Type"
                                    name="visitor_type" id="visitor_type"
                                    aria-describedby="helpId"
                                    required>
                                <option value="" class="d-none"></option>
                                <option value="Adamus Employee from Other Site" <?php echo $visitor_access_form->visitor_type === VisitorType::ADAMUS_EMPLOYEE_FROM_OTHER_SITE? 'selected' : ''?>><?php echo VisitorType::ADAMUS_EMPLOYEE_FROM_OTHER_SITE ?></option>
                                <option value="Consultant" <?php echo $visitor_access_form->visitor_type === VisitorType::CONSULTANT? 'selected' : ''?>><?php echo VisitorType::CONSULTANT ?></option>
                                <option value="VIP" <?php echo $visitor_access_form->visitor_type === VisitorType::VIP? 'selected' : ''?>><?php echo VisitorType::VIP ?></option>
                                <option value="Contractor" <?php echo $visitor_access_form->visitor_type === VisitorType::CONTRACTOR? 'selected' : ''?>><?php echo VisitorType::CONTRACTOR ?></option>
                                <option value="Other" <?php echo $visitor_access_form->visitor_type === VisitorType::OTHER? 'selected' : ''?>><?php echo VisitorType::OTHER ?></option>
                            </select>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Other Visitor Type -->
                <div class="col-sm-6 <?php echo $visitor_access_form->other_visitor_type? '' : 'd-none'?> other-option">
                    <div class="form-group form-row">
                        <label for="company" class="col-sm-4 text-sm-right">Other Visitor Type</label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="3"
                                   class="form-control" name="other_visitor_type" value="<?php  echo $visitor_access_form->other_visitor_type?? '' ?>"
                                   id="other_visitor_type"
                                   aria-describedby="helpId"
                                   placeholder="Specify Other Visitor Type Here"
                                   title="Specify Other Visitor Type Here"/>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Visitor Category -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="visitor_category" class="col-sm-4 text-sm-right">Visitor
                            Category</label>
                        <div class="col-sm-8">
                            <select class="form-control bs-select" tabindex="4"
                                    data-none-selected-text="Select Visitor Category"
                                    name="visitor_category" id="visitor_category"
                                    aria-describedby="helpId"
                                    required>
                                <option value="" class="d-none"></option>
                                <option value="Day Visitor" <?php echo $visitor_access_form->visitor_category === VisitorCategory::DAY_VISITOR? 'selected' : ''?>>Day Visitor</option>
                                <option value="Short Term Visitor (7 Days Max)" <?php echo $visitor_access_form->visitor_category === VisitorCategory::SHORT_TERM_VISITOR? 'selected' : ''?>>Short Term Visitor (7
                                    Days Max)
                                </option>
                            </select>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Arrival Date -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="arrival_date" class="col-sm-4 text-sm-right">
                            Arrival Date
                        </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="arrival_date" tabindex="5"
                                   name="arrival_date" min="<?php today(DATE_FORMAT_YMD) ?>"
                                   placeholder="" value="<?php echo $visitor_access_form->arrival_date?? today(DATE_FORMAT_YMD) ?>"  required>
                            <small class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Departure Date -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="departure_date" class="col-sm-4 text-sm-right">
                            Departure Date
                        </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="departure_date" tabindex="6"
                                   name="departure_date" value="<?php echo $visitor_access_form->departure_date?? today(DATE_FORMAT_YMD) ?>" min="<?php today(DATE_FORMAT_YMD); ?>"
                                   placeholder="" required>
                            <small class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Identification Type -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="identification_type" class="col-sm-4 text-sm-right">
                            Identification Type
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control bs-select" tabindex="7"
                                    data-none-selected-text="Select Identification Type"
                                    name="identification_type" id="identification_type"
                                    aria-describedby="helpId"
                                    required>
                                <option value="" class="d-none"></option>
                                <option value="Voter's ID" <?php echo $visitor_access_form->identification_type === IdentificationType::VOTERS_ID? 'selected' : ''?>>Voter's ID
                                </option>
                                <option value="SSNIT Card" <?php echo $visitor_access_form->identification_type === IdentificationType::SSNIT_CARD? 'selected' : ''?>>SSNIT Card</option>
                                <option value="Driver's License" <?php echo $visitor_access_form->identification_type === IdentificationType::DRIVERS_LICENSE? 'selected' : ''?>>Driver's License</option>
                                <option value="Ghana Card" <?php echo $visitor_access_form->identification_type === IdentificationType::GHANA_CARD? 'selected' : ''?>>Ghana Card
                                </option>
                                <option value="Passport" <?php echo $visitor_access_form->identification_type === IdentificationType::PASSPORT? 'selected' : ''?>>Passport</option>
                            </select>
                            <small class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!--  Identification Number -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="identification_num" class="col-sm-4 text-sm-right">Identification
                            No.</label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="8"
                                   class="form-control"
                                   value="<?php echo $visitor_access_form->identification_num?? '' ?>"
                                   aria-describedby="helpId" placeholder="" id="identification_num"
                                   name="identification_num" required/>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="phone_num" class="col-sm-4 text-sm-right">Phone No.</label>
                        <div class="col-sm-8">
                            <input type="tel" tabindex="9" pattern="[0-9]{10}"
                                   data-pattern-error="Please enter a ten-digit phone number"
                                   class="form-control"
                                   value="<?php echo $visitor_access_form->phone_num?? '' ?>"
                                   aria-describedby="helpId" placeholder="0547468603" id="phone_num"
                                   name="phone_num" required/>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>
                <!-- Reason for visit -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="reason_for_visit" class="col-sm-4 text-sm-right">
                            Reason for Visit
                        </label>
                        <div class="col-sm-8">
                                          <textarea type="text" tabindex="10"
                                                    class="form-control" name="reason_for_visit"
                                                    id="reason_for_visit"
                                                    aria-describedby="helpId" placeholder="" required><?php echo $visitor_access_form->reason_for_visit?? '' ?></textarea>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="col-sm-6">
                    <div class="form-row multiple-form-group" data-max="3">
                        <label for="additional_info" class="col-sm-4 text-sm-right">
                            Additional Information <br>
                            <small>(Maximum of three)</small>
                        </label>
                        <div class="col-sm-8 form-group">
                            <div class="input-group">
                                <input accept="<?php echo implode(',', DOC_FILE_TYPES); ?>"
                                       class="form-control" name="additional_info[]" tabindex="11"
                                       id="additional_info"
                                       type="file"/>
                                <div class="input-group-append cursor-pointer"
                                     title="Click to add more files.">
                                    <div class="input-group-text add-input"><i
                                            class="fas fa-plus-square text-success"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="help-block text-muted">Hint: Attach any additional
                                documents
                            </small>
                            <small id="helpId" class="form-text with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Department Approver -->
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label for="department_approver_id" class="col-sm-4 text-sm-right">
                            Select Approver
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control bs-select" tabindex="12"
                                    data-none-selected-text="Select Approver "
                                    name="site_sponsor_id" id="site_sponsor_id"
                                    aria-describedby="helpId"
                                    required>
                                <option value="" class="d-none"></option>
                                <?php foreach ($section_a->department_approvers as $department_approver) {
                                    $name = $department_approver->first_name. ' ' . $department_approver->last_name;
                                    $selected = $visitor_access_form->site_sponsor_id === $department_approver->user_id? 'selected' : '';
                                    echo "<option value=" . $department_approver->user_id  ." $selected>$name</option>";
                                } ?>
                            </select>
                            <small id="helpId" class="with-errors help-block"></small>
                        </div>
                    </div>
                </div>

                <!-- Accept Terms -->
                <div class="col-sm-6 pl-sm-5">
                    <div class="form-group form-row">
                        <div class="checkbox ml-md-2 pl-1 pl-md-4">
                            <label>
                                <input type="checkbox" name="details_confirmed" id="details_confirmed" value="1"
                                       tabindex="13" required>
                                <small class="text-bold">I hereby certify that the information I
                                    have provided above is true, complete and accurate to the best of my knowledge. I will notify Adamus Resources
                                    Limited of any changes. I will provide appropriate documentation outlining the visitor's qualifications to drive/operate
                                    equipment and will not do so unless legally authorized.
                                </small>
                            </label>
                            <small id="helpId" class="with-errors help-block"></small>
                        </div>
                    </div>
                </div>


                <?php if ($section_a->display_form_default_submit_button): ?>
                  <div class="col-sm-6">
                    <div class="float-right pr-sm-2">

                        <button type="submit" class="btn btn-success" form="<?php echo $section_a::getHtmlFormId() ?>" tabindex="15">Submit</button>
                    </div>
                </div>
                <?php endif ?>
                <!-- Submit and Cancel buttons -->
               <!-- <div class="col-sm-6 d-none" id="button_container">
                    <div class="float-right pr-sm-2">
                        <a href="#" tabindex="14"
                           class="btn bg-danger w3-btn" data-dismiss="modal">Cancel</a>

                        <button type="submit" class="btn bg-success w3-btn" form="visitor_access_form_<?php /*echo $section_a->visitor_access_form_id */?>"
                                tabindex="15">
                            Submit
                        </button>
                    </div>
                </div>-->
            </div>
        </fieldset>
    </div>
</form>
