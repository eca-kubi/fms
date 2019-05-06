<div class="modal fade slacker-modal " id="choose_session_modal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-slacker" role="document">
        <div class="modal-content">
            <div class="modal-header mx-auto">
                <h4 class="modal-title" id="modelTitleId">Pick A Session</h4>
                <button type="button" class="close d-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col text-center">
                    <img class="img-circle img-thumbnail" alt="U" avatar="User" style="width: 100px; height: 100px;"
                         src=""/>
                    <h2 class="pt-3">User</h2>
                    <p class="fa text-muted font-italic pb-5">

                    </p>
                    <p>
                        <a class="btn btn-default" href="<?php echo site_url('salary-advance'); ?>" role="button">
                            Go
                            <i class="fa fa-arrow-alt-circle-right"></i>
                        </a>
                    </p>
                </div>

                <div class="col text-center">
                    <img class="img-circle img-thumbnail" alt="A" avatar="Administrator" src=""
                         style="width: 100px; height: 100px;"/>
                    <h2 class="pt-3">Admin</h2>
                    <p class="fa text-muted font-italic pb-5">

                    </p>
                    <p>
                        <a class="btn btn-default" href="<?php echo site_url('salary-advance-admin'); ?>">Go
                            <i class="fa fa-arrow-alt-circle-right"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

