@extends('../admin')
@section('content')
{{HTML::style('../../src/select2.css')}}
{{HTML::style('../../css/upload.css')}}
<style type="text/css">
   .select2-container {min-width: 50%;}
   ..select2-container .select2-choice{background: transparent;}
</style>
<link rel="stylesheet" type="text/css" href="../../assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
<link rel="stylesheet" type="text/css" href="../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />

<!-- BEGIN PAGE -->  
   <div class="page-content">
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
                        Item Create
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/itemlist">Item List</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Item Create</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="span12">
                     <div class="portlet box blue" id="form_wizard_1">
                        <div class="portlet-title">
                           <h4>
                              <i class="icon-reorder"></i> Item Create - <span class="step-title">Step 1 of 3</span>
                           </h4>
                           
                        </div>
                        <div class="portlet-body form">
                           <form action="/item" class="form-horizontal" method="post" enctype="multipart/form-data">
                              <div class="form-wizard">
                                 <div class="navbar steps">
                                    <div class="navbar-inner">
                                       <ul class="row-fluid">
                                          <li class="span3">
                                             <a href="#tab1" data-toggle="tab" class="step active">
                                             <span class="number">1</span>
                                             <span class="desc"><i class="icon-ok"></i>Item Info</span>   
                                             </a>
                                          </li>
                                          <li class="span3">
                                             <a href="#tab2" data-toggle="tab" class="step">
                                             <span class="number">2</span>
                                             <span class="desc"><i class="icon-ok"></i>Define Size, Color and  Price</span>   
                                             </a>
                                          </li>
                                          <li class="span3">
                                             <a href="#tab3" data-toggle="tab" class="step">
                                             <span class="number">3</span>
                                             <span class="desc"><i class="icon-ok"></i>Upload Image for color</span>   
                                             </a>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div id="bar" class="progress progress-success progress-striped">
                                    <div class="bar"></div>
                                 </div>
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">
                                       <h3 class="block">Item information</h3>
                                       <div class="control-group">
                                          <label class="control-label">Name</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="name" required placeholder="Item Name" />
                                          </div>
                                       </div>
                                       <div class="control-group">
                                          <label class="control-label">Name_MM</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="name_mm" required placeholder="Item Name" />
                                          </div>
                                       </div>
                                       <div class="control-group">
                                          <label class="control-label">ြမန်မာစာ ြဖင့်ၡာေဖွရန် (အတုိေကာက်)</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="search_key_mm" required/>
                                             <p class="help-block">ဥပမာ.  ပနဆန (ပန်နဆုိးနစ်)</p>
                                          </div>
                                        </div>
                                       <div class="control-group">
                                          <label class="control-label">Shop</label>
                                          <div class="controls">
                                             <select name="shop[]" id='shop' class="chosen" multiple>
                                                @if($response['shop'])
                                                   @foreach($response['shop'] as $shop)
                                                      <option value="{{$shop->id}}">{{$shop->name}} ( {{$shop->name_mm}} )</option>
                                                   @endforeach
                                                @endif
                                             </select>
                                             <span class="help-inline">You can Select Multi shops</span>
                                          </div>
                                       </div>
                                       <div class="control-group">
                                          <label class="control-label">Menu</label>
                                          <div class="controls">
                                             <select class="span6 chosen" data-placeholder="Choose a Menu" tabindex="2" name="menu" id="menu">
                                                @if($response['menu'])
                                                   <?php $i=0; ?>
                                                   @foreach($response['menu'] as $menu)
                                                      <option value="{{$menu->id}}" @if($i==0) selected @else @endif>{{$menu->name. '( '. $menu->name_mm .' )'}}</option>
                                                   <?php $i++; ?>
                                                   @endforeach
                                                @endif
                                             </select>
                                          </div>
                                       </div>
                                       

                                       <div class="control-group">
                                          <label class="control-label">Category</label>
                                          <div class="controls">
                                             <div id='categoryloading' class="loader" style="display:none">&nbsp;</div>
                                             <div id="categoryframe">
                                                <select class="span6 chosen" data-placeholder="Choose a Category" tabindex="3" name="category" id="category">
                                                </select>
                                                <input type="hidden" name="hdcategoryid" value="0">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Subcategory</label>
                                          <div class="controls">
                                             <div id='subcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                             <div id="subcategoryframe">
                                                <select class="span6 chosen" data-placeholder="Choose a Subcategory" tabindex="4" name="subcategory" id="subcategory">
                                                   <option value=""></option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Item category</label>
                                          <div class="controls">
                                             <div id='itemcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                             <div id="itemcategoryframe">
                                                <select class="span6 chosen" data-placeholder="Choose a Itemcategory" tabindex="4" name="itemcategory">
                                                </select>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Brand</label>
                                          <div class="controls">
                                             <select class="span6 chosen" data-placeholder="Choose a Brand" tabindex="1" name="brand" id="brand">
                                                <option value=""></option>
                                                @if($response['brand'])
                                                   @foreach($response['brand'] as $brand)
                                                      <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                   @endforeach
                                                @endif
                                             </select>
                                          </div>
                                       </div>

                                       
                                       <div class="control-group">
                                          <label class="control-label">Gender</label>
                                          <div class="controls">
                                                <select name="gender" id='gender'>
                                                   <option value="male">Male</option>
                                                   <option value="Female">Female</option>
                                                   <option value="Both">Both</option>
                                                </select>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Image </label>
                                          <div class="controls images-frame">
                                                <div class="gallery-input">
                                                   <!-- <ul>
                                                      <div class="gallery_container1">
                                                      </div>
                                                      <div class="script"></div>
                                                      <div class="upload1">
                                                         <span>+</span>
                                                         <input type="file" id="gallery_upload1" data-url="../itemphoto/php/index.php">
                                                      </div>
                                                   </ul> -->
                                                   <ul>
                                                      <div class="gallery_container">
                                                      </div>
                                                      <div class="script"></div>
                                                      <div class="upload">
                                                         <span>+</span>
                                                         <input type="file" id="gallery_upload" data-url="../itemphoto/php/index.php">
                                                      </div>
                                                   </ul>
                                                </div>
                                                <div class="span12 responsive">
                                                   <span class="label label-important">NOTE!</span>
                                                   <span>Maximun images is 5.</span>
                                                </div>
                                          </div>
                                       </div>
                                       
                                       <div class="control-group">
                                          <label class="control-label">Description</label>
                                          <div class="controls">
                                             <textarea class="span12 wysihtml5 m-wrap" rows="6" name="description"></textarea>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Description_mm</label>
                                          <div class="controls">
                                             <textarea class="span12 wysihtml5 m-wrap" rows="6" name="description_mm"></textarea>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Status</label>
                                          <div class="controls">
                                             <select name="status">
                                                <option value="normal">Normal</option>
                                                <option value="new-arrival">New Arrival</option>
                                                <option value="featured">Featured</option>
                                             </select>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Publish</label>
                                          <div class="controls">
                                             <div class="basic-toggle-button">
                                                <input type="checkbox" name="publish" value="1" class="toggle" checked="checked"/>
                                             </div>
                                          </div>
                                       </div>

                                       <div id="quantityrangeinfo">
                                          <div class="row-fluid qtyranges">
                                             <div class="span12">
                                                  <div class="span2 push-left">&nbsp;</div>
                                                  <div class="span4 push-left">
                                                      <!-- <div class="row-fluid"> -->
                                                         <label>Quantity Ragne</label>
                                                         <select class="span8 qtyrange" data-placeholder="Choose a Quantity Range" tabindex="3" name="quantityrange[]">
                                                            @if(count($response['quantityrange'])>0)
                                                               <?php $i=0; ?>
                                                               @foreach($response['quantityrange'] as $quantityrange)
                                                                  <option value="{{$quantityrange['id']}}" @if($i==0) selected @else @endif>{{$quantityrange['startqty'].'-'. $quantityrange['endqty']}}</option>
                                                                  <?php $i++; ?>
                                                               @endforeach
                                                            @else
                                                               <option value=""></option>
                                                            @endif
                                                         </select>
                                                      <!-- </div> -->
                                                  </div>
                                                   <div class="span4 push-left">
                                                      <!-- <div class="row-fluid push-left"> -->
                                                         <label>Price</label>
                                                         <input type="text" class="m-wrap span6 price" placeholder="Price"  name="pricebyqty[]" value="0" onKeyPress="return isNumberKey(event);" />
                                                      <!-- </div> -->
                                                   </div>
                                                   <div class="span2">
                                                      <div class="row-fluid push-left"><br>
                                                         <span class="btn red removerows" onclick="return removeqtyrangerow(this)" disable>Remove</span>
                                                      </div>
                                                   </div>
                                                <!-- </div> -->
                                             </div>
                                          </div>
                                       </div>
                                       <br>
                                       <div class="row-fluid">
                                          <div class="span2">&nbsp;</div>
                                          <div class="span10">
                                             <button id='addRow' class="btn green btnaddqtyrange">Add Next</button>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>

                                    </div>
                                    <div class="tab-pane" id="tab2">
                                       <h3 class="block">Define Color, Size and Price.</h3>
                                       <hr>
                                       <div class="row-fluid">
                                          <div class="span12">
                                             <div class="control-group">
                                                <!-- <label class="control-label">Item Code</label> -->
                                                <div class="controls span6">
                                                   <input type="text" class="span6 m-wrap" placeholder="Item Code"  name="itemcode" />
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <div id="itemlistinfo">
                                          <div class="row-fluid items">
                                             <div class="span12">
                                                <!-- <div class="control-group"> -->
                                                  <div class="span3 push-left">
                                                      <div class="row-fluid">
                                                         <label>Color</label>
                                                         <select class="span8 color" data-placeholder="Choose a Color" tabindex="3" name="color[]">
                                                            @if(count($response['color'])>0)
                                                               <?php $i=0; ?>
                                                               @foreach($response['color'] as $color)
                                                                  <option value="{{$color['name']}}" @if($i==0) selected @else @endif>{{$color['name']}}</option>
                                                                  <?php $i++; ?>
                                                               @endforeach
                                                            @else
                                                               <option value=""></option>
                                                            @endif
                                                         </select>
                                                      </div>
                                                  </div>
                                                  <div class="span3 push-left">
                                                      <label>Size</label>
                                                      <span id='itemsizeloading' class="loader" style="display:none">&nbsp;</span>
                                                      <div id="itemsizeframe" class="span11">
                                                         <select name="size[]" class="m-wrap span8 size">
                                                         </select>
                                                      </div>
                                                  </div>
                                                  <div class="span2 push-left">
                                                      <label>Quantity</label>
                                                      <input type="text" class="m-wrap span6 qty" placeholder="Quantity"  name="qty[]"  value="0" onKeyPress="return isNumberKey(event);"/>
                                                  </div>
                                                  <div class="span2 push-left">
                                                      <label>Price</label>
                                                      <input type="text" class="m-wrap span12 price" placeholder="Price"  name="price[]" value="0" onKeyPress="return isNumberKey(event);" />
                                                   </div>
                                                   <div class="span2 push-left">
                                                      <label>&nbsp;</label>
                                                      <span class="btn red removerows" onclick="return removerow(this)" disable>Remove</span>
                                                   </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row-fluid">
                                          <div class="span12">
                                             <button id='addRow' class="btn green btnadd">Add Next</button>
                                          </div>
                                       </div>
                                       <div class="row-fluid">
                                          <hr>
                                       </div>
                                    </div>
                                    <div class="tab-pane" id="tab3">
                                       <h3 class="block">Upload Image for Color</h3>
                                       <hr>
                                          <div class="row-fluid">
                                             <div class="span12">
                                                <div class="control-group">
                                                   <div class="images">
                                                      <!-- will append image upload frame from ajax function-->
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       <div class="row-fluid">
                                          <hr>
                                       </div>
                                    </div>

                                 </div>


                                 

                                 <div class="form-actions clearfix">
                                    <a href="javascript:;" class="btn button-previous">
                                    <i class="m-icon-swapleft"></i> Back 
                                    </a>
                                    <a href="javascript:;" class="btn blue button-next">
                                    Continue <i class="m-icon-swapright m-icon-white"></i>
                                    </a>
                                    <!-- <a href="javascript:;" class="btn green button-submit">
                                    Submit <i class="m-icon-swapright m-icon-white"></i>
                                    </a> -->
                                    <!-- <b class="btn green button-submit"> -->
                                    <button type="submit" class="btn green button-submit">Submit</button>
                                    <!-- </b> -->
                                 </div>
                              </div>
                              <div id='arrayinfo'></div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <!-- {{HTML::script('../../src/select2.min.js')}} -->
   <script src="../../assets/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
   <script type="text/javascript" src="../../assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
   <script type="text/javascript" src="../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
   {{HTML::script('../../src/jquery-ui.js')}}
   {{HTML::script('../../src/select2.min.js')}}
   {{HTML::script('../../src/jquery.fileupload.js')}}
   {{HTML::script('../../js/admin/itemupload.js')}}
   <!-- {{HTML::script('../../js/imageupload.js')}} -->
@stop