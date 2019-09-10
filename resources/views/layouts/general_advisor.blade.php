@if($advisor === "true")
<div class="container col-md-12 row">
  <div class="panel-group">
    <div class="panel panel-default">
      
      <div class="panel-heading">
        <a data-toggle="collapse" href="#advisorModal">
          <h4 class="panel-title">
            <b>Advisor</b>
          </h4>
        </a>
      </div>
      
      <div id="advisorModal" class="panel-collapse collapse in">
        <div class="panel-body"><p align="justify" id="general_advisor"></p></div>
      </div>
    </div>
  </div>
</div>
@endif