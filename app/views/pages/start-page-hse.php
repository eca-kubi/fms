<?php /** @var array $payload */ ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once APP_ROOT . '\views\includes\start-page-header.php'; ?>
<!--<div class="row">
    <p class="h6 callout callout-danger center-notice animated flash">No apps are available at this time!</p>
</div>-->
<div class="row mbr-justify-content-center">
    <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo HOST . '/nearmiss' ?>">
        <div class="wrap">
            <div class="ico-wrap w-25 mr-5">
                <span class="mbr-iconfont">
                    <?php require APP_ROOT . '\views\includes\svg-icons\vest.svg'; ?>
                </span>
            </div>
            <div class="text-wrap w-75">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5 m-0">Near Miss Draw<span></span></h2>
                <p class="mbr-fonts-style text1 mbr-text desc">
                    <span>Near Miss Draw</span>
                    <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo URL_ROOT . '/safety-flash' ?>">
        <div class="wrap">
            <div class="ico-wrap w-25 mr-5">
                <span class="mbr-iconfont">
                    <?php require APP_ROOT . '\views\includes\svg-icons\workers-with-safety-helmets.svg'; ?>
                </span>
            </div>
            <div class="text-wrap w-75">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5 m-0">Safety Flash<span></span></h2>
                <p class="mbr-fonts-style text1 mbr-text desc">
                    <span>Incident reporting.</span>
                    <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mbr-col-md-10" data-url="<?php echo HOST . '/nearmiss' ?>">
        <div class="wrap">
            <div class="ico-wrap w-25 mr-5">
                <span class="mbr-iconfont">
                    <?php require APP_ROOT . '\views\includes\svg-icons\vest.svg'; ?>
                </span>
            </div>
            <div class="text-wrap w-75">
                <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5 m-0">Report Near Miss<span></span></h2>
                <p class="mbr-fonts-style text1 mbr-text desc">
                    <span>Near Miss Reporting.</span>
                    <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                </p>
            </div>
        </div>
    </div>
</div>
</div>
</section>
<?php require_once APP_ROOT . '/views/includes/start-page-footer.php' ?>
</body>
</html>
