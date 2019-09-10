<div id="externalModalButton" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">External Import</h4>
         </div>
         <div class="modal-body">

            <div id="Tree" class="tab-pane">
               <br>
               <div class="row">
                  <div class="form-inline d-none" id="text_tree">
                     {!! Form::text('value_db',null, ['class' =>'form-control', 'id' => 'value_db']) !!}
                     <button type="button" class="btn btn-default set_value" data-dismiss="modal">Set</button>
                  </div>
               </div>
               <div class="row">
                  <div id="treeImport"></div>
               </div>
            </div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>