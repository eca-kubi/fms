<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/26/2019
 * Time: 4:15 PM
 */
?>
<!-- Modal -->
<div class="modal fade" id="cancelRequest" tabindex="-1" role="dialog"
     aria-labelledby="stopProcessLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stopProcessLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This action will cancel the visitor access request! <br>
                        Warning: It cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <a type="button" class="btn btn-primary"
                   href="#">Yes</a>
            </div>
        </div>
    </div>
</div>
