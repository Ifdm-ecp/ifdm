@extends('layouts.generaldatabase')

@section('title', 'IFDM Statistics')

@section('content')


  <div class="row">
    <br>
    <br>
    <div id="statistics"></div>
    <br>
    <br>
    <br>

    <div class="bs-example">
      <div class="panel-group" id="accordionBasin">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionBasin" href="#collapseBasin">Basin</a>
                  </h4>
              </div>

              @if (count($basins) > 0)
                <div id="collapseBasin" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($basins as $basin)
                        @foreach ($basin->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>

    <div class="bs-example">
      <div class="panel-group" id="accordionField">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionField" href="#collapseField">Field</a>
                  </h4>
              </div>
              @if (count($fields) > 0)
                <div id="collapseField" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($fields as $field)
                        @foreach ($field->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            <caption>
                              {{ $revision->created_at }} record {{ $field->identifiableNameField($revision->row_id)->nombre }} <strong>{{ $revision->action }}</strong>.
                            </caption>
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                @if($key == "cuenca_id")
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $field->identifiableNameBasin($revision->old($key))->nombre }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $field->identifiableNameBasin($revision->new($key))->nombre }}</td>
                                  </tr>
                                @else
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                                @endif
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>

    <div class="bs-example">
      <div class="panel-group" id="accordionFormation">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionFormation" href="#collapseFormation">Formation</a>
                  </h4>
              </div>
              @if (count($formations) > 0)
                <div id="collapseFormation" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($formations as $formation)
                        @foreach ($formation->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            <caption>
                              {{ $revision->created_at }} record {{ $formation->identifiableNameFormation($revision->row_id)->nombre }} <strong>{{ $revision->action }}</strong>.
                            </caption>
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                @if($key == "campo_id")
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $formation->identifiableNameField($revision->old($key))->nombre }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $formation->identifiableNameField($revision->new($key))->nombre }}</td>
                                  </tr>
                                @else
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                                @endif
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>



    <div class="bs-example">
      <div class="panel-group" id="accordionWell">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionWell" href="#collapseWell">Well</a>
                  </h4>
              </div>
              @if (count($wells) > 0)
                <div id="collapseWell" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($wells as $well)
                        @foreach ($well->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            <caption>
                              {{ $revision->created_at }} record {{ $well->identifiableNameWell($revision->row_id)->nombre }} <strong>{{ $revision->action }}</strong>.
                            </caption>
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                @if($key == "campo_id")
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $well->identifiableNameField($revision->old($key))->nombre }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $well->identifiableNameField($revision->new($key))->nombre }}</td>
                                  </tr>
                                @else
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                                @endif
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>

    <div class="bs-example">
      <div class="panel-group" id="accordionProducingInterval">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionProducingInterval" href="#collapseProducingIntervals">Producing Interval</a>
                  </h4>
              </div>
              @if (count($producing_intervals) > 0)
                <div id="collapseProducingIntervals" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($producing_intervals as $producing_intervals)
                        @foreach ($producing_intervals->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            <caption>
                              {{ $revision->created_at }} record <strong>{{ $revision->action }}</strong>.
                            </caption>
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                @if($key == "pozo_id")
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $producing_intervals->identifiableNameWell($revision->old($key))->nombre }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $producing_intervals->identifiableNameWell($revision->new($key))->nombre }}</td>
                                  </tr>
                                @elseif($key == "formacion_id")
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $producing_intervals->identifiableNameFormation($revision->old($key))->nombre }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $producing_intervals->identifiableNameFormation($revision->new($key))->nombre }}</td>
                                  </tr>
                                @else
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                                @endif
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>


    <div class="bs-example">
      <div class="panel-group" id="accordionScenario">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionScenario" href="#collapseScenario">Scenario</a>
                  </h4>
              </div>
              @if (count($scenarios) > 0)
                <div id="collapseScenario" class="panel-collapse collapse">
                    <div class="panel-body">
                      @foreach ($scenarios as $scenario)
                        @foreach ($scenario->revisions as $revision)
                        <a data-toggle="collapse" data-target="#demo{{ $revision->id }}">
                          <div class="list-group-item list-group-item-warning">
                            <caption>
                              {{ $revision->created_at }} record {{ $scenario->identifiableNameScenario($revision->row_id)->nombre }} <strong>{{ $revision->action }}</strong>.
                            </caption>
                          </div>
                        </a>
                        <div id="demo{{ $revision->id }}" class="collapse">  
                           
                          @if (count($revision->old))
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Old Value</th>
                                  <th>New Value</th>
                                </tr>
                              </thead>
                             
                              @foreach ($revision->old as $key => $v)
                                  @if($key == "cuenca_id")
                                    <tr>
                                      <td>{{ $revision->label($key) }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $scenario->identifiableNameBasin($revision->old($key))->nombre }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $scenario->identifiableNameBasin($revision->new($key))->nombre }}</td>
                                    </tr>
                                  @elseif($key == "campo_id")
                                    <tr>
                                      <td>{{ $revision->label($key) }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $scenario->identifiableNameField($revision->old($key))->nombre }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $scenario->identifiableNameField($revision->new($key))->nombre }}</td>
                                    </tr>
                                  @elseif($key == "pozo_id")
                                    <tr>
                                      <td>{{ $revision->label($key) }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $scenario->identifiableNameWell($revision->old($key))->nombre }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $scenario->identifiableNameWell($revision->new($key))->nombre }}</td>
                                    </tr>
                                  @elseif($key == "formacion_id")
                                    <tr>
                                      <td>{{ $revision->label($key) }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $scenario->identifiableNameFormation($revision->old($key))->nombre }}</td>
                                      <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $scenario->identifiableNameFormation($revision->new($key))->nombre }}</td>
                                    </tr>
                                  @else
                                  <tr>
                                    <td>{{ $revision->label($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
                                    <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }}</td>
                                  </tr>
                                  @endif
                              @endforeach
                            </table>
                          @endif
                        </div>
                        @endforeach
                      @endforeach
                    </div>
                </div>
              @endif
          </div>
      </div>
    </div>

    <br>
    <br>
    <br>
    <p class="pull-right">
      <a href="{!! url('UserStatistics') !!}" class="btn btn-danger" role="button">Cancel</a>
    </p>
  </div>


  

@endsection


@section('Scripts')
  <script src="{{ asset('js/highcharts.js') }}"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  @include('js/statisticsbyuser')

@endsection


