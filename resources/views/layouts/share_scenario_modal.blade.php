<div class="modal fade" id="shared_scenario" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Share Scenario</h4>
            </div>
            <div class="modal-body" style="overflow: visible;">
                {!! Form::open(array('url'=>'share_scenario', 'method'=>'post')) !!}
                <div class="form-group">
                    {!! Form::label('Share To...') !!}
                    {!! Form::select('shared_users_s[]', Array(), null, array('class'=>'form-control selectpicker', 'data-live-search'=>'true','data-style'=>'btn-default','id'=>'shared_users_s', 'multiple' => 'true')) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('scenario_t', null, array('class'=>'form-control','id'=>'scenario_t', 'style' => 'display: none;')) !!}
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::submit('Save' , array('id'=>'submit_button','class' => 'btn btn-primary')) !!}
                {!! Form::Close() !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_scenario" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Scenario</h4>
            </div>
            <div class="modal-body" style="overflow: visible;">
                {!! Form::open(array('url'=>'edit_scenario', 'method'=>'post')) !!}
                <div class="form-group">
                    {!! Form::label('Name') !!}
                    {!! Form::text('name_scenario', null, array('class'=>'form-control','id'=>'name_scenario')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Description') !!}
                    {!! Form::textarea('description_scenario', null, array('class'=>'form-control','id'=>'description_scenario')) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('id_scenario', null, array('class'=>'form-control','id'=>'id_scenario', 'style' => 'display: none;')) !!}
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::submit('Edit' , array('id'=>'submit_button','class' => 'btn btn-warning')) !!}
                {!! Form::Close() !!}
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    textarea {
        resize: none;
    } 
</style>