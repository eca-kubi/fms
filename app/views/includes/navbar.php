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
            <li class="nav-item dropdown d-none">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fa fa-comments-o"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="" alt="User ..." class="img-size-50 mr-3 img-circle"/>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-right text-sm text-danger">
                                        <i class="fa fa-star"></i>
                                    </span>
                                </h3>
                                <p class="text-sm">Call me whenever you can...</p>
                                <p class="text-sm text-muted">
                                    <i class="fa fa-clock-o mr-1"></i> 4 Hours Ago
                                </p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="" alt="User ..." class="img-size-50 img-circle mr-3"/>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    John Pierce
                                    <span class="float-right text-sm text-muted">
                                        <i class="fa fa-star"></i>
                                    </span>
                                </h3>
                                <p class="text-sm">I got your message bro</p>
                                <p class="text-sm text-muted">
                                    <i class="fa fa-clock-o mr-1"></i> 4 Hours Ago
                                </p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="" alt="User ..." class="img-size-50 img-circle mr-3"/>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-right text-sm text-warning">
                                        <i class="fa fa-star"></i>
                                    </span>
                                </h3>
                                <p class="text-sm">The subject goes here</p>
                                <p class="text-sm text-muted">
                                    <i class="fa fa-clock-o mr-1"></i> 4 Hours Ago
                                </p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php
                    $user = getUserSession();
                    $initials = $user->first_name[0] . $user->last_name[0];
                    if ($user->profile_pic == DEFAULT_PROFILE_PIC) {
                        $name = $user->first_name . ' ' . $user->last_name;
                        $src = PROFILE_PIC_DIR . $user->profile_pic . '?' . microtime();
                        echo "<img  alt='<?php echo $initials ?>' avatar=\"$name\" src=\"$src\" class=\"user-image img-size-32 img-fluid img-circle d-inline-block\" >";
                    } else { ?>
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
                        <div class="col fa p-2">
                            <?php
                            if ($user->profile_pic == DEFAULT_PROFILE_PIC) {
                                $initials = $user->first_name[0] . $user->last_name[0];
                                $name = $user->first_name . ' ' . $user->last_name;
                                echo "<img alt='<?php echo $initials ?>' class=\"user-image img-size-32 img-fluid img-circle d-inline-block \" avatar=\"$name\" >";
                            } else { ?>
                                <img src="<?php echo PROFILE_PIC_DIR . $user->profile_pic . '?' . microtime(); ?>"
                                     class="user-image img-size-32 img-fluid img-circle d-inline-block"
                                     alt="<?php echo $initials; ?>" /><?php } ?>
                            <p class="text-bold mb-1">
                                <?php echo ucwords($user->first_name . ' ' . $user->last_name, ' -'); ?>
                            </p>
                            <p class="text-bold mb-1">
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
                        <i class="fa fa-home ml-4"></i> DASHBOARD
                    </a>
                </li>
                <li class="nav-item dropdown fa  mx-2 d-none">
                    <a class="nav-link dropdown-item dropdown-toggle btn border-0 text-bold flat text-warning"
                       data-toggle="dropdown">
                        <i class="fas fa-edit"></i>
                        Forms
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownId"
                        style="position:absolute">
                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Finance</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo site_url('salary-advance') ?>">Salary
                                        Advance</a></li>
                                <!--<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                        <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                        <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                    </ul>
                                </li>-->
                            </ul>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                IT
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                HR
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Admin
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Mining
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown fa  mx-2 d-none">
                    <a class="nav-link dropdown-item text-warning dropdown-toggle btn border-0 text-bold flat"
                       data-toggle="dropdown">
                        <i class="fa fa-history"></i>
                        History
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownId"
                        style="position:absolute">
                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Finance</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo site_url('salary-advance') ?>">Salary
                                        Advance</a></li>
                                <!--<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                        <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                        <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                    </ul>
                                </li>-->
                            </ul>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                IT
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                HR
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Admin
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Mining
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if ($current_user->role == ROLE_SECRETARY || $current_user->role == ROLE_MANAGER || $current_user->role == ROLE_SUPERINTENDENT || isFinanceOfficer($current_user->user_id)) { ?>
                    <li class="nav-item dropdown fa  mx-2">
                        <a class="nav-link dropdown-item text-warning dropdown-toggle btn border-0 text-bold flat"
                           data-toggle="dropdown">
                            <i class="fa fa-history"></i>
                            Roles
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownId"
                            style="position:absolute">
                            <li><a class="dropdown-item"
                                   href="<?php echo site_url('salary-advance') ?>">User</a>
                            </li>
                            <?php if ($current_user->role == ROLE_SECRETARY) { ?>
                                <li><a class="dropdown-item"
                                       href="<?php echo site_url('salary-advance-secretary') ?>">Secretary</a>
                                </li>
                            <?php } ?>
                            <?php if ($current_user->role == ROLE_MANAGER || $current_user->role == ROLE_SUPERINTENDENT) { ?>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo site_url('salary-advance-manager') ?>">
                                        Manager
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (isFinanceOfficer($current_user->user_id)) { ?>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo site_url('salary-advance-finance-officer') ?>">
                                        Finance Officer
                                    </a>
                                </li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>


            </ul>
        </div>
    </nav>
</div>
