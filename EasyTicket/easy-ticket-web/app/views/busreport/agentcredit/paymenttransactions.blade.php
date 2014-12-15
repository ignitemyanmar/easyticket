@extends('../admin')
@section('content')
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
                        @if(count($response)>0)
                        &nbsp;{{$response[0]['agent']}}    
                        @endif    
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        @if(count($response)>0)
                           <li><a href="/report/agentcredit/{{$response[0]['agent_id']}}">Back</a></li>
                        @endif 
                        
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
                              <h4><i class="icon-edit"></i>အေၾကြးစာရင္းမ်ား</h4>
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
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th class="span3">ၾကိဳတင္ေငြ သြင္းသည့္ေန႕</th>
                                       <th class="span2">အေၾကြးေပးသည့္ေန႕</th>
                                       <th>ေပးေငြ</th>
                                       <th>အေၾကြး</th>
                                       <th>လက္က်န္ေငြ</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          @foreach($response as $row)
                                             <tr>
                                             <?php 
                                                $amount=($row['price']-$row['commission']) * $row['total_ticket'];
                                             ?>
                                                <td>{{date('d/m/Y',strtotime($row['deposit_date']))}}</td>
                                                <td>{{date('d/m/Y',strtotime($row['pay_date']))}}</td>
                                                <td>{{ $row['payment']}} </td>
                                                <td>{{ $row['total_ticket_amt']+ $row['debit']}}</td>
                                                <td>{{$row['balance']}}</td>
                                             </tr>
                                          @endforeach
                                       @endif
                                    
                                    
                                 </tbody>
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
      <!-- END PAGE --> 
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
@stop