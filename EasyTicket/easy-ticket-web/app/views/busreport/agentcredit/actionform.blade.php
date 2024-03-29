@extends('../admin')
@section('content')
   <link rel="stylesheet" type="text/css" href="../../assets/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" />
   <link rel="stylesheet" href="../../css/reveal.css" />
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
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <h4>"{{$response['name']}}"</h4>
                                       <input type="hidden" class="span9" name="agent_id" value="{{$response['id']}}">
                                       <input type="text" class="span9" name="deposit">
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

                  <!-- Commission form -->
                  <div class="row-fluid">
                     <div class="span12">
                        <div id="commission_form" class="reveal-modal small reveal-frame" data-reveal> 
                           <h4 class="reveal-title">ေကာ္မစ္ရွင္ ေၾကးထည့္သြင္းရန္</h4> 
                           <div class="reveal-form-frame">
                              <form action="/report/agentcommission" method="post">
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
                                 <div class="row-fluid">
                                    <div class="span4">
                                       <label><input type="radio" class="span4" value="2" name="commission_id">Percentage</label>
                                    </div>
                                    <div class="span4">
                                       <label><input type="radio" class="span4" value="1" name="commission_id">Fixed</label>
                                    </div>
                                 </div>

                                 <div class="clear">&nbsp;</div>
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <input type="text" class="span9" name="commission">
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
                                       <a href="/report/agentcommission/{{$response['id']}}"><button class="btn purple btn-block">ခရီးစဥ္ အလုိက္ ေကာ္မစ္ရွင္မ်ား</button></a>
                                       <button class="btn green btn-block" data-reveal-id="deposit_form">စရံေငြထည့္ရန္</button>
                                       <a href="/report/agentcreditsales/{{$response['id']}}"><button class="btn yellow btn-block">ေၾကြးစာရင္းမ်ားၾကည့္ရန္</button></a>
                                       <a href="/report/paymenttransaction/{{$response['id']}}"><button class="btn blue btn-block" >ေပးျပီးသားစာရင္းမ်ားၾကည့္ရန္</button></a>
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
   <script>
      jQuery(document).ready(function() {  
         App.setPage("table_editable");
      });
   </script>
   <script>
       $(document).foundation();
   </script>
@stop