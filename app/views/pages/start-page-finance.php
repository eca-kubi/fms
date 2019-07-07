<?php /** @var array $payload */ ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once APP_ROOT . '/views/includes/start-page-header.php'?>
<body>
<section class="animated fadeInRight">
    <div class="container">
            <div class="row">
                <span class="p-3 h3">Finance</span>
                <span class="m-1 ml-auto">
                    <a href="<?php echo site_url("start-page") ?>" class="btn btn-pill btn-primary"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </span>
            </div>
        <div class="row mbr-justify-content-center">
            <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo site_url('salary-advance'); ?>">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-money fa"></span>
                    </div>
                    <div class="text-wrap">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5 m-0">Salary Advance<span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text desc">
                            <span>Apply for Salary Advance</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once APP_ROOT . '/views/includes/start-page-footer.php'?>
</body>
</html>
