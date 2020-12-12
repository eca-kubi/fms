
<!DOCTYPE html>
<html lang="en">
<?php require_once APP_ROOT . '\views\includes\start-page-header.php'; ?>
<style>

</style>
<body>
<div class="row mbr-justify-content-center blockable">
    <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo site_url('start-page/hse'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon invisible">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont "><?php require_once APP_ROOT . '\views\includes\svg-icons\vest.svg'; ?></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> HSE <span></span></h2>
                <p class="mbr-fonts-style mbr-text display-6 mb-0">
                    <a href="https://adamusgh.com/covid-19" target="_blank"  class="d-block w3-text-black child"><i class="fa fa-check text-black"></i> COVID-19</a>
                    <a href="https://adamusgh.com/apps/near-miss" target="_blank" class="d-block w3-text-black child"><i class="fa fa-check text-black"></i> Near Miss</a>
                    <a href="https://adamusgh.com/apps/incident-alert" target="_blank" class="d-block w3-text-black child"><i class="fa fa-check text-black"></i> Incident Alert</a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/security'); ?>">
        <span class="text-success text-sm tag-coming-soon invisible"><i class="fa fa-check"></i></span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fontastic-001-engineer-2"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Security</h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <a href='<?php echo site_url("visitor-access-form") ?>' target="_blank" class="d-block w3-text-black child"><i
                                class="fa fa fa-check animated text-black"></i> Visitor Access</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo site_url('start-page/finance') ?>">
        <span class="text-success text-sm tag-coming-soon invisible"><i class="fa fa-check"></i></span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fa fa-money"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Finance</h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <a class="d-block w3-text-black child" href="<?php echo URL_ROOT . '/salary-advance' ?>" target="_blank">
                        <i class="fa fa-check animated text-black"></i> Salary Advance </a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/admin'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fa-user-o fa"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Admin
                    <span></span>
                </h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All Admin-related forms.</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/it'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fa-computer-classic fa"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> IT <span></span></h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All IT-related forms.</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/hr'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fa-users fa"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> HR
                </h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All HR-related forms.</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/mining'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fontastic-mine-truck"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Mining <span></span></h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All Mining-related forms.</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/engineering'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fontastic-engineering"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Engineering <span></span></h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All Engineering-related forms.</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10 coming-soon" data-url="<?php echo site_url('start-page/processing'); ?>">
        <span class="badge badge-pill badge-outline-secondary text-sm tag-coming-soon">Coming soon</span>
        <div class="wrap">
            <div class="ico-wrap">
                <span class="mbr-iconfont fontastic-robot"></span>
            </div>
            <div class="text-wrap">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5"><i
                            class="fa fa-folder w3-text-amber"></i> Processing <span></span></h2>
                <p class="mbr-fonts-style mbr-text display-6">
                    <span class="">All Processing-related forms.</span>
                </p>
            </div>
        </div>
    </div>
</div>
</div> <!-- .container -->
</section> <!-- .section-1 -->
<?php require_once APP_ROOT . '/views/includes/start-page-footer.php' ?>
</body>
</html>
