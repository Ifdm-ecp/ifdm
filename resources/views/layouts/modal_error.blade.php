<!--Mensaje modal para errores-->
@if(count($errors) > 0)
    <div id="modal_error" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">
                        <small>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="modal_error_js" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body">
                <p class="text-danger">
                    <small>
                        <li id="alertErrorJS"></li>
                    </small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_error_frontend" class="modal fade" style="z-index: 99999">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="modal_error_frontend_title" class="modal-title">Error</h4>
            </div>
            <div class="modal-body">
                <p>
                    <small>
                        <ul id="modal_error_frontend_messages"></ul>
                    </small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_error_frontend_button_ok" class="btn btn-default" data-dismiss="modal">Ok</button>
                <button type="button" id="modal_error_frontend_button_continue" class="btn btn-default" style="display: none" onclick="saveForm()" data-dismiss="modal">Continue saving</button>
            </div>
        </div>
    </div>
</div>