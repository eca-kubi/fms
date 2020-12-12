<?php $current_user = getUserSession(); ?>
<div class="navbar-fixed fixed-top blockable">
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom px-3">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Messages Dropdown Menu -->


            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php
                    $user = getUserSession();
                    $initials = $user->first_name[0] . $user->last_name[0];
                    if ($user->profile_pic === DEFAULT_PROFILE_PIC) {
                        $name = $user->first_name . ' ' . $user->last_name;
                        $src = PROFILE_PIC_DIR . $user->profile_pic . '?' . microtime(); ?>
                        <img alt="<?php echo $initials ?>"
                             class="user-image img-size-32 img-fluid img-circle d-inline-block"
                             avatar="<?php echo $name; ?>">
                    <?php } else { ?>
                        <img src="<?php echo PROFILE_PIC_DIR . $user->profile_pic . '?' . microtime(); ?>"
                             class="user-image img-size-32 img-fluid img-circle d-inline-block"
                             alt="<?php echo $initials; ?>" /><?php } ?>
                    <span class="hidden-xs text-capitalize">
                        <?php echo ucwords($user->first_name . ' ' . $user->last_name); ?>
                    </span>
                </a>
                <ul class="dropdown-menu m-0 p-1 dropdown-menu-right" style="min-width: 19rem">
                    <!-- User image -->
                    <li class="user-header"></li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="col p-2">
                            <?php
                            if ($user->profile_pic === DEFAULT_PROFILE_PIC) {
                                $initials = $user->first_name[0] . $user->last_name[0];
                                $name = $user->first_name . ' ' . $user->last_name; ?>
                                <img alt="<?php echo $initials ?>"
                                     class="user-image img-size-32 img-fluid img-circle d-inline-block"
                                     avatar="<?php echo $name; ?>">
                            <?php } else { ?>
                                <img src="<?php echo PROFILE_PIC_DIR . $user->profile_pic . '?' . microtime(); ?>"
                                     class="user-image img-size-32 img-fluid img-circle d-inline-block"
                                     alt="<?php echo $initials; ?>" /><?php } ?>
                            <p class="text-bold mb-1">
                                <?php echo ucwords($user->first_name . ' ' . $user->last_name, ' -'); ?>
                            </p>
                            <p class="text-bold mb-1 text-sm">
                                <?php echo ucwords($user->job_title, '- '); ?>
                            </p>
                            <p class="text-nowrap text-muted d-none">
                                Member since ...
                            </p>
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer glyphicon-arrow-down row px-2">
                        <div class="pull-left col">
                            <a href="<?php echo site_url('users/profile'); ?>"
                               class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo site_url('users/logout'); ?>" class="btn btn-default btn-flat">Sign
                                out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <nav class="d-md-block d-none navbar navbar-light bg-navy text-white flex-nowrap flex-row" id="navbar2"
         style="z-index: 0">
        <div class="container-fluid">
            <ul class="navbar-nav flex-row float-left">
                <li class="nav-item d-none">
                    <a href="#" class="btn btn-default btn-lg w3-hover-text-grey btn-sm">
                        <i class="fa fa-angle-double-left  mr-1"></i>Go Back
                    </a>
                </li>
                <li class="nav-item ml-0 ml-sm-4 text-left pr-1 border-right border-warning fa">
                    <a href="<?php echo site_url('start-page'); ?>"
                       class="ajax-link nav-link btn border-0 text-bold flat text-left font-raleway text-warning">
                        <i class="fal fa-home ml-4"></i> START PAGE
                    </a>
                </li>

                <li class="nav-item text-left mx-2  border-warning fa">
                    <a href="<?php echo site_url('visitor-access-form'); ?>"
                       class="ajax-link nav-link btn border-0 text-bold flat text-left font-raleway text-warning">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item text-left mx-2  border-warning fa">
                    <a href="<?php echo site_url('visitor-access-form/a'); ?>"
                       class="ajax-link nav-link btn border-0 text-bold flat text-left font-raleway text-warning">
                        <i class="fa fa-user"></i> New Request
                    </a>
                </li>

                <li class="nav-item dropdown mx-2">
                    <a class="nav-link text-warning  btn border-0 text-bold flat"
                       href="#">
                        <i class="fal fa-history"></i>
                        REQUESTS
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownId"
                        style="position:absolute">
                        <li><a class="dropdown-item"
                               href="<?php echo site_url('visitor-access-form/dashboard/pending') ?>">Pending</a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item"
                               href="<?php echo site_url('visitor-access-form/dashboard/completed') ?>">Completed</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
