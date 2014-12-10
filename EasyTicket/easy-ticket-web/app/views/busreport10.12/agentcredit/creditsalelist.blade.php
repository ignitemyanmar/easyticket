@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../../css/reveal.css" />
   <link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" />
   <link rel="shortcut icon" href="favicon.ico" />
   <!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN STYLE CUSTOMIZER -->
                    
                     <!-- END BEGIN STYLE CUSTOMIZER -->    
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        &nbsp;{{$agent_name}}        
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
                     <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>
                              @if($parameter['cash']==1)
                                 ေပးျပီး စာရင္းမ်ား
                              @else
                                 အေၾကြးစာရင္းမ်ား
                              @endif
                              </h4>
                           </div>
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

                              <div class="row-fluid search-default">
                                 <div class="span7">
                                       <div class="btn-group" style="margin:11px;">
                                          <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                          </button>
                                          <ul class="dropdown-menu">
                                             <li><a href="#" class="print">Print</a></li>
                                             <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                          </ul>
                                       </div>
                                 </div>
                                 <div class="span5">
                                    <div class="btn-group pull-right" style="margin:11px;">
                                       <a href="/report/agentcreditsales/{{$parameter['agent_id']}}?cash={{$parameter['cash_status']}}">

                                          <button class="btn green">
                                          @if($parameter['cash_status']==1)
                                             &nbsp;ေပးျပီး စာရင္းမ်ား
                                          @else
                                             အေၾကြးစာရင္းမ်ား 
                                          @endif 
                                             <i class="m-icon-swapright m-icon-white"></i>
                                          </button>

                                       </a>
                                       <!-- <ul class="dropdown-menu">
                                          <li><a href="#" class="print">Print</a></li>
                                          <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                       </ul> -->
                                    </div>
                                    <!-- <form class="form-search" action="/report/dailycarandadvancesale/search">
                                       <div class="chat-form">
                                          <div class="input-cont">   
                                             <input placeholder="Choose Date..." class="m-wrap hasDatepicker" id="startdate" name="date" value="2014-12-04" type="text">
                                          </div>
                                          <button type="submit" class="btn green">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                       </div>
                                    </form> -->
                                 </div>
                              </div>
                              <form action="/report/agentcreditspayment" method="post" id="paymentform">
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       @if($parameter['cash_status']==2)
                                          <th>ေငြေပးေခ် သည့္ေန႕</th>
                                       @else
                                          <th>Select</th>
                                       @endif
                                       <th>ေဘာက္ခ်ာနံပါတ္</th>
                                       <th>ဝယ္ယူ သည့္ေန႕ရက္</th>
                                       <th>ခရီးစဥ္</th>
                                       <th>ကားထြက္ မည့္ေန႔ / အခ်ိန္</th>
                                       <th>ခုံအေရအတြက္</th>
                                       <th>ရွင္းႏႈန္း</th>
                                       <th>အေၾကြး / ေပးျပီး</th>
                                       <th>%ႏုတ္ျပီးစုစုေပါင္း</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          <?php $columns=9; ?>  
                                          @foreach($response as $key=>$value)
                                             <tr>
                                                <th colspan="{{$columns}}" style="background:#E4F6F5 !important;">{{$key}}</th>
                                             </tr>
                                             <?php $subtotal=0; ?>
                                             @foreach($value as $row)
                                                <tr>
                                                <?php 
                                                   $amount=($row['price']-$row['commission']) * $row['total_ticket'];
                                                ?>
                                                  
                                                   @if($parameter['cash_status']==2)
                                                      <td>{{$row['paydate']}}</td>
                                                   @else
                                                      <td>
                                                         <input type="checkbox" name="order_id[]" value="{{$row->id}}" class="order_id" data-amounts="{{$amount}}">
                                                      </td>
                                                   @endif
                                                   <td>{{ $row['id']}}</td>
                                                   <td>{{$row['orderdate']}}</td>
                                                   <td>{{ $row['trip']}} ({{$row['class']}})</td>
                                                   <td>{{ date('d/m/Y', strtotime($row['departure_date']))}} [{{$row['departure_time']}}]</td>
                                                   <td>{{$row['total_ticket']}}</td>
                                                   <td>{{$row['price']-$row['commission']}} ({{$row['commission']}})</td>
                                                   <td>@if($row['cash_credit']==2)<span class='badge badge-warning'>အေၾကြး</span> @else &nbsp; <span class='badge badge-success'>ေပးျပီး</span> @endif</td>
                                                   <td>{{($row['price']-$row['commission']) * $row['total_ticket']}}</td>
                                                </tr>
                                                <?php $subtotal +=($row['price']-$row['commission']) * $row['total_ticket']; ?>
                                             @endforeach
                                             <tr>
                                                <th colspan="{{$columns-1}}" style="background: #ddd !important;text-align:right !important;">Sub Total :</th>
                                                <th align="left" style="background: #ddd !important;">{{$subtotal}}</th>
                                             </tr>
                                          @endforeach
                                       @endif
                                    
                                    
                                 </tbody>
                              </table>
                                 <div class="row-fluid">
                                    <div class="span12">
                                       <div id="payment_form" class="reveal-modal small reveal-frame" data-reveal> 
                                          <h4 class="reveal-title">ေငြေပးေခ်ရန္</h4> 
                                          <div class="reveal-form-frame">
                                                <div class="row-fluid">
                                                   <div class="span12">
                                                      <p>ၾကိဳတင္ေငြပမာဏ =<b @if($agent_balance <0) class="required" @endif>{{$agent_balance}}</b></p><br>
                                                      <p>ေငြေပးေခ်ရန္ ပမာဏ = <b class="lbltopaytotal">0</b></p>
                                                   </div>
                                                </div>
                                                <div class="clear">&nbsp;</div>
                                                <div class="row-fluid Zawgyi-One">
                                                   <div class="span12">
                                                      <label class="Zawgyi-One"><input type="checkbox" value="1" id="chkpaywithdeposit" name="paywithdeposit">ၾကိဳတင္ေငြမွေပးမည္</label><br>
                                                      <input type="text" class="span9" name="paymentamount" id="txtpayamount" onblur="return setpaymentamt(this)" placeholder="ေငြေပးမည့္ပမာဏ ထည့္ရန္">
                                                   </div>
                                                </div>
                                                <div class="row-fluid">
                                                   <div class="span12 Zawgyi-One">
                                                      <input type="button" class="btn grey" value="ထြက္မည္">
                                                      <input type="submit" class="btn green" id="payment" value="ေပးမည္">
                                                   </div>
                                                </div>
                                          </div>
                                          
                                       </div>
                                    </div>
                                 </div>
                                 <input type="hidden" name="agent_id" value="{{$parameter['agent_id']}}">
                                 <input type="hidden" name="operator_id" value="{{$parameter['operator_id']}}">
                                 <input type="hidden" name="payment_amount" id="hdpayment" value="0">
                                 <input type="hidden" name="paywithdeposit" id="hdpaywithdeposit" value="0">
                                 <input type="button" class="btn green" data-reveal-id="payment_form" value="ေပးေခ်ရန္">
                              </form>
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
   <script type="text/javascript" src="../../js/foundation.min.js"></script>
   <script>
      $(document).foundation();
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
   <script type="text/javascript">
   $(function(){
         $('#hdpayment').val(0);
         $('#txtpayamount').val('');
         $('#hdpaywithdeposit').val(0);
         $('#chkpaywithdeposit').prop('checked', "");

         $('#payment').click(function(){
           document.getElementById('paymentform').submit();
         });

         $('#chkpaywithdeposit').click(function(){
            var checkchecked=$(this).prop('checked');
            if(checkchecked==false){
               $('#hdpaywithdeposit').val(0);
            }else{
               $('#hdpaywithdeposit').val(1);
            }
         });

         var topaytotal=0;
         $('.order_id').each(function(){
           var checkchecked=$(this).prop('checked');
            if(checkchecked==false){
               // $('.lbltopaytotal').html(0);
            }else{
               var amount=$(this).data('amounts');
               topaytotal +=amount;
            } 
         });
         if(topaytotal>0)
               $('.lbltopaytotal').html(topaytotal);
   });

   $('.order_id').click(function(){
         var topaytotal=0;
         $('.order_id').each(function(){
            var checkchecked=$(this).prop('checked');
            if(checkchecked==false){
               $('.lbltopaytotal').html(0);
            }else{
               var amount=$(this).data('amounts');
               topaytotal +=amount;
            } 
         });
         if(topaytotal>0)
            $('.lbltopaytotal').html(topaytotal);
         
      });
   function setpaymentamt(obj){
      var payamount=obj.value;
         $('#hdpayment').val(payamount);
   }
   </script>
@stop