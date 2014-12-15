@extends('../admin')
@section('content')
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />
<style type="text/css">
   #tblExport_length select{width: 80px;}
   tr.group th,
   tr.group th:hover {
       background: #E4F6F5  !important;
   }
   tr.groupfooter th,
   tr.groupfooter th:hover {
       background: #ddd  !important;
   }
   .noti{color: red !important; font-weight: bold;}
   /*.group b{display: none !important;}
   .groupfooter span{display: none !important;}*/
   tfoot th{background: #E4F6F5  !important;}
</style> 
   <link rel="shortcut icon" href="favicon.ico" />
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
                  <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="row-fluid search-default">
                                 <form action="/report/agentscredits/search" method="get">
                                       <div class="span2">
                                          <div class="control-group">
                                             <label class="control-label">All Date</label>
                                             <div class="controls">
                                                <select name="cbodate" class="chosen span12" id='alldate'>
                                                   <option value="All" @if($search['datestatus']=="All") selected @endif>All</option>
                                                   <option value="Custom" @if($search['datestatus']=="Custom") selected @endif>Custom</option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span2">
                                          <div class="control-group" id="daterange">
                                             <label class="control-label" for="startdate">အစေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="startdate" data-date-format="dd-mm-yyyy" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" value="{{date('d-m-Y', strtotime($search['start_date']))}}">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span2">
                                          <div class="control-group" id="daterange1">
                                             <label class="control-label" for="enddate">အဆုံးေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="enddate" data-date-format="dd-mm-yyyy" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" value="{{date('d-m-Y', strtotime($search['end_date']))}}">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="from">Agent Group</label>
                                             <div class="controls">
                                                <select name="agentgroup" class="m-wrap span12 chosen" id="agentgroup">
                                                   <option value="All">All</option>
                                                   @if(isset($search['agentgroup']))
                                                      @foreach($search['agentgroup'] as $rows)
                                                         <option value="{{$rows['id']}}" @if($rows['id']==$search['agentgroup_id']) selected @endif>{{$rows['name']}}</option>
                                                      @endforeach
                                                   @endif
                                                </select>
                                             </div>
                                             
                                          </div>
                                       </div>
                                       <div class="span3 responsive">
                                          <div class="control-group">
                                             <label class="control-label" for="from">ဂိတ်ခွဲများ</label>
                                             <div class="controls" id="agent_id">
                                                <select name="agent_id" class="m-wrap span12 chosen">
                                                   <option value="All">All</option>
                                                   @if($search['agent'])
                                                      @foreach($search['agent'] as $row)
                                                         <option value="{{$row['id']}}" @if($row['id']==$search['agent_id']) selected @endif>{{$row['name']}}</option>
                                                      @endforeach
                                                   @endif
                                                </select>
                                             </div>
                                             <!-- <span id="loading" class="loader">&nbsp;</span> -->

                                          </div>
                                       </div>

                                       
                                       <div class="clear-fix">&nbsp;</div>

                                       <input type="hidden" value="{{$search['end_date'].' to '. $search['end_date']}}" id="report_date">
                                       <button type="submit" class="btn green pull-right">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                 </form>
                           </div>
                           <div class="clear-fix">&nbsp;</div>

                           <div class="portlet-body">
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message)
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message}}
                                 </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="tblExport">
                                 <thead>
                                    <tr>
                                       <th class="span4">အမည္</th>
                                       <th>Group Header</th>
                                       <th class="span3">Opening (Receivable)</th>
                                       <th>Receivable</th>
                                       <th>Receipt</th>
                                       <th class="span3">Closing Balance</th>
                                       <!-- <th><a class="btn small green blue-stripe" href="/report/agentcreditsales/group">အေသးစိတ္ All</a></th> -->
                                       <th><a class="btn small green blue-stripe" href="#">အေသးစိတ္ All</a></th>
                                    </tr>
                                 </thead>
                                       @if($response)
                                       <tbody>
                                          @foreach($response as $key=>$rows)
                                             <!-- <tr class="group">
                                                <th>{{$key}}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                             </tr> -->
                                             <?php  
                                                $topayamount =0;
                                                $topaycredit =0;
                                                $grandtotalcredit=0;
                                             ?>
                                             @foreach($rows as $row)
                                                <tr>
                                                   <td>{{$row['name']}}</td>
                                                   <td>{{$row['groupheader']}}</td>
                                                   <td>{{$row['opening_balance']}}</td>
                                                   <td>{{$row['receivable']}}</td>
                                                   <td>{{$row['receipt']}}</td>
                                                   <td><span @if($row['closing_balance'] < 0)  class="noti" @endif>{{str_replace('-',"",$row['closing_balance'])}}</span></td>
                                                   <td>
                                                      <a class="btn mini green-stripe" href="/report/agentcreditlist/group/{{$row['agentgroup_id']}}?agent_id={{$row['id']}}">အေသးစိတ္ၾကည့္မည္</a>
                                                   </td>
                                                </tr>
                                             @endforeach
                                          @endforeach
                                       </tbody>
                                       @endif
                                       
                                    
                              </table>
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
    
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  {
                        "targets": [ 1 ],
                         "visible": false,
                  },
              ],
              "order": [],
              "displayLength": 25,
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  var prevgroup=null;
                  api.column(1, {page:'current'} ).data().each( function ( group, i) {
                     if ( last !== group ) {
                        $(rows).eq( i ).before(
                              '<tr class="group"><th colspan="6">'+group+'</th></tr>'
                        );
                        
                        last = group;
                     }
                  } );

               }

          } );
          // Order by the grouping
          $('#tblExport tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 1 && currentOrder[1] === 'asc' ) {
                  table.order( [ 1, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 1, 'asc' ] ).draw();
              }
          } );


         var datestatus=$('#alldate').val();
         checkdatestatus(datestatus);
         
         $('#alldate').change(function(){
            var datestatus=$(this).val();
            checkdatestatus(datestatus);
            
         });

        
         
         $('chosen').chosen();
         $('#agentgroup').change(function(){
            var agentgroup_id=$(this).val();
            if(agentgroup_id=="All")
            {
               agentgroup_id=0;
            }
            var result='<select name="agent_id" class="m-wrap span12 chosen">';
                  result+='<option value="All">All</option>';
            $('#agent_id').html('');
            $('#agent_id').addClass('loader');
            $.get('/agentbranches/'+agentgroup_id,function(data){
               for(var i=0; i<data.length; i++){
                  result +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
               }
               result +='</select>';
               $('#agent_id').removeClass('loader');
               $('#agent_id').html(result);
               $('.chosen').chosen();
            });
         });
      } );

      function checkdatestatus(datestatus)
      {
         if(datestatus=="All"){
            $('#daterange').hide();
            $('#daterange1').hide();
         }else{
            $('#daterange').show();
            $('#daterange1').show();
         }
      }
   </script>

 @stop