@extends('../admin')
@section('content')
{{HTML::style('../../css/upload.css')}}
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <div id="portlet-config" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h5>Image Upload for Relative Color</h5>
            </div>
            
            <?php 
               $onchange="fileSelect('preview','image_file','error','warnsize','hidden_img')"; 
            ?>
            <div class="modal-body">
               <form action="#" method="get" class="form-horizontal" method="post" enctype="multipart/form-data">
                  <div class="control-group">
                     <label class="control-label">Image</label>
                     <div class="controls">
                           <div class="span4 file-upload">
                              <span class="text-center">Upload</span>
                              <input type="file" name="image_file" id="image_file" onchange="{{$onchange}}"/>
                              <input type="hidden" name="hidden_img" value="defaultcolor.jpg" id="hidden_img" required>
                           </div>
                           <div class="span4">
                              <div id="error" class="error">You should select valid image files only!</div>
                              <div id="warnsize" class="warnsize">Your file is very big. We can\'t accept it. Please select more small file</div>
                              <div class="previewstyle">
                                 <img id="preview" class="preview" />
                              </div>
                          </div> 
                     </div>
                  </div>
                  <div class="form-actions clearfix">
                     <button type="submit" class="btn green button-submit">Submit</button>
                  </div>
               </form>
            </div>
         </div>
         <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     <div class="color-panel hidden-phone">
                        <div class="color-mode-icons icon-color"></div>
                        <div class="color-mode-icons icon-color-close"></div>
                        <div class="color-mode">
                           <p>THEME COLOR</p>
                           <ul class="inline">
                              <li class="color-black current color-default" data-style="default"></li>
                              <li class="color-blue" data-style="blue"></li>
                              <li class="color-brown" data-style="brown"></li>
                              <li class="color-purple" data-style="purple"></li>
                              <li class="color-white color-light" data-style="light"></li>
                           </ul>
                           <label class="hidden-phone">
                           <input type="checkbox" class="header" checked value="" />
                           <span class="color-mode-label">Fixed Header</span>
                           </label>                    
                        </div>
                     </div>
                     <!-- END BEGIN STYLE CUSTOMIZER -->   
                     <h3 class="page-title">
                        Item List by Main ItemID
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/item">Item List</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Item Color and Sizes</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="portlet box light-grey">
                  <div class="portlet-title">
                     <h4><i class="icon-user"></i>Item Color and Size List</h4>
                     <div class="actions">
                     @if($response['item_lists'])
                        <a href="/item-color-size/{{$response['item_lists']['id']}}/create" class="btn blue"><i class="icon-pencil"></i> Add</a>
                     @endif
                        <div class="btn-group">
                           <a class="btn green" href="#" data-toggle="dropdown">
                           <i class="icon-cogs"></i> Tools
                           <i class="icon-angle-down"></i>
                           </a>
                           <ul class="dropdown-menu pull-right">
                              <li><a href="#"><i class="icon-pencil"></i> Edit</a></li>
                              <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
                              <li><a href="#"><i class="icon-ban-circle"></i> Ban</a></li>
                              <li class="divider"></li>
                              <li><a href="#"><i class="i"></i> Make admin</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>

                  <div class="portlet-body">
                     <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                           <tr>
                              <th>Photo</th>
                              <th class="hidden-phone">Color</th>
                              <th>Size</th>
                              <th class="hidden-phone">Quantity</th>
                              <th class="hidden-phone">Price</th>
                              <th>Action</th>
                              <!-- <th></th> -->
                           </tr>
                        </thead>
                        <tbody>
                           @if($response['item_lists']['itemdata'])
                              @foreach($response['item_lists']['itemdata'] as $itemdata)
                                 <tr>
                                    <td><img src="../../itemdetailphoto/php/files/large/{{$itemdata['image']}}" alt="" style="max-height:60px;"></td>
                                    <td class="hidden-phone">{{ $itemdata['color']}}</td>
                                    <td>{{ $itemdata['size']}}</td>
                                    <?php 
                                       $loadingdiv  ="qty_prce".$itemdata['id'];
                                       $qtyid      ="qty".$itemdata['id'];
                                       $priceid    ="price".$itemdata['id'];
                                       $link       ="/item-qty-price-update/".$itemdata['id'];
                                       $deletelink ="/item-qty-price-delete/".$itemdata['id'];
                                    ?>
                                    <td class="hidden-phone span1"></div><input type="text" id="{{$qtyid}}" name="qty{{$itemdata['id']}}" value="{{ $itemdata['qty']}}"></td> 
                                    <td class="hidden-phone span1"><input type="text" id="{{$priceid}}" name="price{{$itemdata['id']}}" value="{{ $itemdata['price']}}"></td>
                                    <!-- <td><span class="label label-success">Edit</span></td> -->
                                    <td>
                                       <div class="btn mini green-stripe update" onClick="return updateinfo(this,'{{$loadingdiv}}','{{$qtyid}}','{{$priceid}}','{{$link}}')">Update</div>
                                       <a class="btn mini red-stripe" id="{{$itemdata['id']}}" onClick="return deletedetail(this,'{{$loadingdiv}}','{{$deletelink}}')">Delete</a>
                                       <!-- <a class="btn mini blue-stripe imagechange" id="{{$itemdata['id']}}" class="config" data-toggle="modal" href="#portlet-config">Change Image</a> -->
                                       <div id="{{$loadingdiv}}"></div>
                                    </td>
                                 </tr>
                              @endforeach
                           @endif

                        </tbody>
                     </table>
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
      <script type="text/javascript">

         function updateinfo(obj, loadingdiv, qty, price,link){
            // document.getElementById(loadingdiv).style.display='block';
            $('#'+loadingdiv).addClass('loader');
            var par_qty=document.getElementById(qty).value;
            var par_price=document.getElementById(price).value;
            $.ajax({
               type: "post",
               url: link,
               data: {quantity:par_qty, price:par_price}
             }).done(function( result ) {
               if(result=='Update Success.'){
                  $('#'+loadingdiv).removeClass('loader');
                  $('#'+loadingdiv).html('<div id="message" style="display:none;color:green;"><label class="control-label">Success</label></div>');
                  $('#message').fadeIn(100).delay(3000).fadeOut(1000);
               }else{
                  $('#'+loadingdiv).removeClass('loader');
               }
           });
         }

         function deletedetail(obj, loadingdiv,link){
            $('#'+loadingdiv).addClass('loader');
            var itemdtid=obj.id;
            $.ajax({
               type: "post",
               url: link,
               data: {id:itemdtid}
             }).done(function( result ) {
               if(result=='Have been deleted.'){
                  $(obj).parent().parent().remove();
               }
           });
         }
      </script>
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   {{HTML::script('../../../js/imageupload.js')}}

@stop