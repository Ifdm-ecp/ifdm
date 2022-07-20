
   <script>
       var enable_Advisor= true;
   </script>

   <div id="advisorModalButton" class="modal fade">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">Advisor</h4>
            </div>
            <div class="modal-body">
               <ul class="nav nav-tabs">
                  <li id="info_tab"><a  data-toggle="tab" href="#Info">Information</a></li>
                  <li id="import_tab"><a data-toggle="tab" href="#Tree">Import Data From Another Scenario</a></li>
               </ul>
               <div class="tab-content">
                  <div id="Info" class="tab-pane">
                     <div class="panel-body"><p align="justify" id="input_text_advisor"></p></div>
                     <div id="advisor_footer" class="panel-footer"><p align="justify" id="input_ref_advisor"></p></div>
                  </div>
                  <div id="Tree" class="tab-pane">
                  <br>
                  <div class="row">
                     <div class="form-inline d-none" id="text_tree">
                           {!! Form::text('value_db',null, ['class' =>'form-control', 'id' => 'value_db']) !!}
                           <button type="button" class="btn btn-default set_value" data-dismiss="modal">Set</button>
                     </div>
                  </div>
                  <div class="row">
                     <div id="treeAdvisor"></div>
                  </div>
                  </div>
               </div>

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>

   <div class="modal" tabindex="-1" role="dialog" id="validationBhpModal">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Error</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>'BHP' value in tab 'Operative Data' must be less than 'Current Reservoir Pressure' value in tab 'Rock Properties'.</p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
