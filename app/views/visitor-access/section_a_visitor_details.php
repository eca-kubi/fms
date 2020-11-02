<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar.php'; ?>
<?php
$user = getUserSession();
?>
<!-- .content-wrapper -->
<div class="content-wrapper animated fadeInRight" style="margin-top: <?php echo NAVBAR_MT; ?>">
    <!-- .content-header-->
    <section class="content-header d-none">
        <!-- .container-fluid -->
        <div class="container-fluid">
            <!-- .row -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <a href="javascript: window.history.back();" class="btn w3-btn bg-gray">
                                <i class="fa fa-backward"></i> Go Back
                            </a>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <h1>
                        <?php echo APP_NAME; ?>
                    </h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content-header-->
    <!-- content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h5>
                    <?php flash('flash_' . SECTION_A_VISITOR_DETAILS); ?>
                </h5>
                <h3 class="box-title text-bold d-none">
                    <?php /** @var array $payload */
                    echo $payload['title']; ?>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body pt-0">
                <form action="" method="POST"  id="visitor_access_form" role="form" data-toggle="validator"
                      onsubmit=""
                      enctype="multipart/form-data">
                    <div class="row p-2 border">
                        <fieldset class="w-100">
                            <h6 class="text-bold font-italic">
                                <a href="#section_a" data-toggle="collapse">
                                    <i class="fa fa-minus"></i> Section A: Visitor Details
                                    <span class="text-muted">(To be Completed by - Originator)</span>
                                </a>
                            </h6>
                            <div id="section_a" class="collapse show section row">
                                <!-- Visitor's Name -->
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="full_name" class="col-sm-4 text-sm-right">Full Name</label>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                   class="form-control"
                                                   value=""
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
                                            <input type="text"
                                                   class="form-control"
                                                   value=""
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
                                            <select class="form-control bs-select has-other-type"
                                                    data-none-selected-text="Select Visitor Type"
                                                    name="visitor_type" id="visitor_type"
                                                    aria-describedby="helpId"
                                                    required>
                                                <option value="" class="d-none"></option>
                                                <option value="Adamus Employee from Other Site">
                                                    Adamus Employee from Other Site
                                                </option>
                                                <option >Consultant</option>
                                                <option value="VIP">VIP</option>
                                                <option value="Contractor">Contractor
                                                </option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <small id="helpId" class="form-text with-errors help-block"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Other Visitor Type -->
                                <div class="col-sm-6 d-none">
                                    <div class="form-group form-row">
                                        <label for="company" class="col-sm-4 text-sm-right">Other Visitor Type</label>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                   class="form-control" name="other_visitor_type"
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
                                            <select class="form-control bs-select"
                                                    data-none-selected-text="Select Visitor Category"
                                                    name="visitor_category" id="visitor_category"
                                                    aria-describedby="helpId"
                                                    required>
                                                <option value="" class="d-none"></option>
                                                <option  value="Day Visitor">
                                                    Day Visitor
                                                </option>
                                                <option  value="Short Term Visitor (7 Days Max)">Short Term
                                                    Visitor (7 Days Max)
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
                                            <div class="input-group ">
                                                <div class="input-group-prepend cursor-pointer"
                                                     title="">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                </div>
                                                <input type="text" class="form-control" id="arrival_date"
                                                       name="arrival_date"
                                                       placeholder="" required>
                                            </div>
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
                                            <div class="input-group ">
                                                <div class="input-group-prepend cursor-pointer"
                                                     title="">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                </div>
                                                <input type="text" class="form-control" id="departure_date"
                                                       name="departure_date"
                                                       placeholder="" required>
                                            </div>
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
                                            <select class="form-control bs-select"
                                                    data-none-selected-text="Select Identification Type"
                                                    name="identification_type" id="identification_type"
                                                    aria-describedby="helpId"
                                                    required>
                                                <option value="" class="d-none"></option>
                                                <option  value="Voter's ID">
                                                    Voter's ID
                                                </option>
                                                <option value="SSNIT Card">SSNIT Card</option>
                                                <option value="Driver's License">Driver's License</option>
                                                <option value="Ghana Card">Ghana Card
                                                </option>
                                                <option value="Passport">Passport</option>
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
                                            <input type="text"
                                                   class="form-control"
                                                   value=""
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
                                            <input type="text"
                                                   class="form-control"
                                                   value=""
                                                   aria-describedby="helpId" placeholder="" id="phone_num"
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
                                          <textarea type="text"
                                                    class="form-control" name="reason_for_visit"
                                                    id="reason_for_visit"
                                                    aria-describedby="helpId" placeholder="" required></textarea>
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
                                                       class="form-control" name="additional_info[]"
                                                       id="additional_info"
                                                       required type="file"/>
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

                                <!-- Section A Approver -->
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="section_a_approver" class="col-sm-4 text-sm-right">
                                            Select Approver
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control bs-select"
                                                    data-none-selected-text="Select Approver "
                                                    name="section_a_approver" id="section_a_approver"
                                                    aria-describedby="helpId"
                                                    required>
                                                <option value="" class="d-none"></option>
                                                <option value="Krisztian Mat">Krisztian Mat
                                                </option>
                                                <option value="Eric Akoto Dompre">Eric Akoto Dompre
                                                </option>
                                                <option value="James Sackey">James Sackey</option>
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
                                                <input type="checkbox" name="certify_details" id="certify_details" required>
                                                <small class="text-bold">I hereby certify that the information I
                                                    have provided above is true, complete and accurate.
                                                </small>
                                            </label>
                                            <small id="helpId" class="with-errors help-block"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit and Cancel buttons -->
                                <div class="col-sm-6" id="button_container">
                                    <div class="float-right pr-sm-2">
                                        <a href="javascript: window.history.back();"
                                           class="btn bg-danger w3-btn">Cancel</a>

                                        <button type="submit" class="btn bg-success w3-btn" form="visitor_access_form">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"></div>
            <!-- /.box-footer-->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
