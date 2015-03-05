@extends('../admin')
@section('content')
   <link rel="stylesheet" type="text/css" href="../../assets/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" />
   <link rel="stylesheet" href="../../css/reveal.css" />
   <link rel="stylesheet" type="text/css" href="../../assets/bootstrap-daterangepicker/daterangepicker.css" />
   <style type="text/css">
      .daterangepicker.dropdown-menu {
          max-width: none;
          z-index: 9991;
      }
   </style>
   <!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား          
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <!-- <li><a href="/">Dashboard</a></li> -->
                        
                     </ul>
                     <!-- END PAGE TITLE & BREADCRUMB-->
                  </div>
               </div>
            <!-- END PAGE HEADER-->
            <div id="dashboard">
               <!-- BEGIN PAGE -->
                  <h4>{{$response['name']}}</h4> 
                  <!-- Credit form -->
                  <div class="row-fluid">
                     <div class="span12">
                        <div id="credit_form" class="reveal-modal small reveal-frame" data-reveal> 
                           <h4 class="reveal-title">ေၾကြးေဟာင္း ထည့္ရန္</h4> 
                           <div class="reveal-form-frame">
                              <form action="/report/agentoldcredit" method="post">
                                 <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <h4>"{{$response['name']}}"</h4>
                                       <input type="hidden" class="span9" name="agent_id" value="{{$response['id']}}">
                                       <input type="text" class="span9" name="credit">
                                    </div>
                                 </div>

                                 <div class="row-fluid">
                                    <div class="span12">
                                       <input type="submit" class="btn grey" value="ထြက္မည္">
                                       <input type="submit" class="btn green" value="သိမ္းမည္">
                                    </div>
                                 </div>
                              </form>
                           </div>
                           
                        </div>
                     </div>
                  </div>

                  <!-- Deposit form -->
                  <div class="row-fluid">
                     <div class="span12">
                        <div id="deposit_form" class="reveal-modal small reveal-frame" data-reveal> 
                           <h4 class="reveal-title">ၾကိဳတင္ေငြ ထည့္ရန္</h4> 
                           <div class="reveal-form-frame">
                              <form action="/report/agentdeposit" method="post">
                                 <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <h4>"{{$response['name']}}"</h4>
                                       <input type="hidden" class="span9" name="agent_id" value="{{$response['id']}}">
                                       <input type="text" class="span9" name="deposit">
                                    </div>
                                 </div>

                                 <div class="row-fluid">
                                    <div class="span12">
                                       <input type="button" class="btn grey" value="ထြက္မည္">
                                       <input type="submit" class="btn green" value="သိမ္းမည္">
                                    </div>
                                 </div>
                              </form>
                           </div>
                           
                        </div>
                     </div>
                  </div>

                  <!-- Commission form -->
                  <div class="row-fluid">
                     <div class="span12">
                        <div id="commission_form" class="reveal-modal small reveal-frame" data-reveal> 
                           <h4 class="reveal-title">ေကာ္မစ္ရွင္ ေၾကးထည့္သြင္းရန္</h4> 
                           <div class="reveal-form-frame">
                              <form action="/report/agentcommission" method="post">
                                 <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                                 <input type="hidden" class="span9" name="agent_id" value="{{$response['id']}}">
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <div class="control-group">
                                          <label for="from">ခရီးစဥ္</label>
                                          <div class="controls">
                                             <select name="trip_id" class="m-wrap span12 chosen">
                                                @if($trips)
                                                   @foreach($trips as $row)
                                                      @if($row->from_city && $row->to_city && $row->busclass)
                                                         <option value="{{$row->id}}">{{$row->from_city->name.'-'.$row->to_city->name}} ({{$row->busclass->name .'==>'.$row->time}})</option>
                                                      @endif
                                                   @endforeach
                                                @endif
                                             </select>
                                          </div>
                                       </div><br>
                                       
                                    </div>
                                 </div>

                                 <div class="clear">&nbsp;</div>
                                 <div class="row-fluid hide">
                                    <div class="span4">
                                       <label><input type="radio" class="span4" value="2" name="commission_id">Percentage</label>
                                    </div>
                                    <div class="span4">
                                       <label><input type="radio" class="span4" value="1" name="commission_id" checked="">Fixed</label>
                                    </div>
                                 </div>

                                 <div class="row-fluid">
                                    <div class="span12">
                                       <div class="control-group">
                                          <label for="from">Commission</label>
                                          <div class="controls">
                                             <input type="text" class="span9" name="commission">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="row-fluid">
                                    <div class="span12">
                                       <div class="control-group">
                                          <input type="hidden" id="hdstartdate" value="{{date('d-m-Y',strtotime($date_range['start_date']))}}">
                                          <input type="hidden" id="hdenddate" value="{{date('d-m-Y',strtotime($date_range['end_date']))}}">
                                          <label class="control-label">Choose Date Ranges</label>
                                          <div class="controls">
                                             <div id="form-date-rangecustom" class="btn">
                                                <i class="icon-calendar"></i>
                                                &nbsp;<span>{{date('d-m-Y',strtotime($date_range['start_date'])).' - '. date('d-m-Y',strtotime($date_range['end_date']))}}</span> 
                                                <b class="caret"></b>
                                                <input type="hidden" id="hddaterange" name="hddaterange" value="{{$date_range['start_date'].','.$date_range['end_date']}}">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="row-fluid">
                                    <div class="span12">
                                       <input type="button" class="btn grey" value="ထြက္မည္">
                                       <input type="submit" class="btn green" value="သိမ္းမည္">
                                    </div>
                                 </div>
                              </form>
                           </div>
                           
                        </div>
                     </div>
                  </div>

                  @if(Session::has('message'))
                     {{Session::get('message')}}
                  @endif
                  <div class="row-fluid">
                     <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span5">
                                    <div id="action_form"> 
                                       <button class="btn green btn-block" data-reveal-id="credit_form">အရင္ေၾကြးေဟာင္းမ်ား ထည့္ရန္</button>
                                       <button class="btn blue btn-block" data-reveal-id="commission_form">ေကာ္မစ္ရွင္ ထည့္ရန္</button>
                                       <a href="/report/agentcommission/{{$response['id']}}?{{$myApp->access_token}}"><button class="btn purple btn-block">ခရီးစဥ္ အလုိက္ ေကာ္မစ္ရွင္မ်ား</button></a>
                                       <button class="btn green btn-block" data-reveal-id="deposit_form">စရံေငြထည့္ရန္</button>
                                       <a href="/report/agentcreditsales/{{$response['id']}}?{{$myApp->access_token}}"><button class="btn yellow btn-block">ေၾကြးစာရင္းမ်ားၾကည့္ရန္</button></a>
                                       <a href="/report/agentcreditsales/{{$response['id']}}?cash=1&{{$myApp->access_token}}"><button class="btn blue btn-block" >ေပးျပီးသားစာရင္းမ်ားၾကည့္ရန္</button></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                     </div>
                  </div>
               <!-- END PAGE -->
               
            </div>
         </div>
         <!-- END PAGE CONTAINER-->    
      </div>
      <!-- END PAGE --> 
   <script type="text/javascript" src="../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
   
   <script type="text/javascript" src="../../js/foundation.min.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-daterangepicker/date.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-daterangepicker/daterangepicker.js"></script> 

   <script>
      jQuery(document).ready(function() {  
         App.setPage("table_editable");
      });
   </script>
   <script>
       $(document).foundation();
   </script>

   <script type="text/javascript">
      $(function(){
            
            $('#form-date-rangecustom').daterangepicker({
            ranges: {
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
            },
            opens: 'right',
            // format: 'MM/dd/yyyy',
            format: 'dd-MM-yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: 0//-29
                // days: -29
            }),
            endDate: Date.today(),
            // minDate: '01/01/2012',
            // maxDate: '12/31/2014',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },

        function (start, end) {
            $('#seating-map-creator').html('<div class="loader"><p>Loading!.... Please Wait.</p></div>');
            $('#form-date-rangecustom span').html(start.toString('dd-MM-yyyy') + ' - ' + end.toString('dd-MM-yyyy'));
            $('#form-date-rangecustom #hddaterange').val(start.toString('yyyy-MM-dd') + ',' + end.toString('yyyy-MM-dd'));
            var trip_id=$('#trip_id').val();
            var var_date_range=$('#hddaterange').val();
            $.ajax({
               type:'GET',
               url:'/define-ownseat-drange/'+trip_id,
               data:{   date_range:var_date_range,
                     }
              }).done(function(result){
                  $('#seating-map-creator').html(result);
              });
        });
         var hdstartdate=$('#hdstartdate').val();
            var hdenddate=$('#hdenddate').val();
         $('#form-date-rangecustom span').html(hdstartdate + ' - ' + hdenddate);
      });
      
   </script>
@stop