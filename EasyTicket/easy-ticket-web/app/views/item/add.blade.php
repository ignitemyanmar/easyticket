@extends('../admin')
@section('content')
{{HTML::style('../../css/font.css')}}
<style type="text/css">
.reveal-modal-bg{position:fixed;height:100%;width:100%;background:#000;background:rgba(0,0,0,0.45);z-index:98;display:none;top:0;left:0}.reveal-modal{visibility:hidden;display:none;position:absolute;left:50%;z-index:99;height:auto;margin-left:-40%;width:80%;background-color:#fff;padding:1.25rem;border:solid 1px #666;-webkit-box-shadow:0 0 10px rgba(0,0,0,0.4);box-shadow:0 0 10px rgba(0,0,0,0.4);top:50px}.reveal-modal .column,.reveal-modal .columns{min-width:0}.reveal-modal>:first-child{margin-top:0}.reveal-modal>:last-child{margin-bottom:0}.reveal-modal .close-reveal-modal{font-size:1.375rem;line-height:1;position:absolute;top:0.5rem;right:0.6875rem;color:#aaa;font-weight:bold;cursor:pointer}@media only screen and (min-width: 40.063em){.reveal-modal{padding:1.875rem;top:6.25rem}.reveal-modal.tiny{margin-left:-15%;width:30%}.reveal-modal.small{margin-left:-20%;width:40%}.reveal-modal.medium{margin-left:-30%;width:60%}.reveal-modal.large{margin-left:-35%;width:70%}.reveal-modal.xlarge{margin-left:-47.5%;width:95%}}@media print{.reveal-modal{background:#fff !important}}
.reveal-modal{z-index: 999;} 
input[class*="span"].m-wrap {float: none;height: 31px !important;}
.reveal-modal input[class*="span"].m-wrap{max-width: 70% !important; height: 21px !important;}
   .select2-container {min-width: 80px;}
   .select2-container .select2-choice{background: transparent;}
   .orange{background: #ECA22E;}
   select{-webkit-appearance: none;
             padding: 0; 
         }
   .btn{color: #fff !important;}
   .cke_editable_themed{min-height: 350px !important;}
   input, select, select, textarea{font-family: "Zawgyi-One" !important;}
   .toggle-button label{z-index: 9;}
</style>
<!-- {{HTML::style('../../../css/foundation.min.css')}} -->
{{HTML::style('../../css/upload.css')}}
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
{{HTML::style('../../assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css')}}

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
                              <i class="icon-reorder"></i> Item Create - <span class="step-title">Step 1 of 2</span>
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
                                          <li class="span2">
                                             <a href="#tab2" data-toggle="tab" class="step">
                                             <span class="number">2</span>
                                             <span class="desc" style="position: absolute;margin-top: 13px;"><i class="icon-ok"></i>Upload Image for color</span>   
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
                                             <input type="text" class="span6 m-wrap" name="it_name"  placeholder="Item Name" />
                                          </div>
                                       </div>
                                       <div class="control-group">
                                          <label class="control-label">Name_MM</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="it_name_mm"  placeholder="Item Name (Myanmar)" />
                                          </div>
                                       </div>
                                       <div class="control-group">
                                          <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="it_search_key_mm" />
                                             <p class="help-block">ဥပမာ. ပနဆန (ပန္နဆုိးနစ္)</p>
                                          </div>
                                        </div>

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="shopform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Shop</h3>
                                                   <hr>
                                                   <form id="update-form" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                      <div class="control-group">
                                                         <label class="control-label">Shop</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_shop"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                      </div>
                                                      <div class="control-group">
                                                         <label class="control-label">Shop (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_shopmm"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       
                                                       <div class="control-group">
                                                         <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="search_key_mm" id="pu_shopsmm" type="text">
                                                            <p class="help-block">ဥပမာ.  ပနဆန (ပန္နဆုိးနစ္)</p>
                                                         </div>
                                                       </div>

                                                      <div class="clearfix">&nbsp;</div>
                                                      <div class="control-group">
                                                         <label class="control-label">Image</label>
                                                         <div class="controls images-frame">
                                                            <div class="gallery-input">
                                                               <ul>
                                                                  <div class="gallery_container1">

                                                                  </div>
                                                                  <div class="script"></div>
                                                                  <div class="upload1">
                                                                  <!-- <li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter" style="width:70%"></span></div></li> -->
                                                                     <span>+</span>
                                                                     <input id="shopupload" data-url="../shopphoto/php/index.php" type="file">
                                                                  </div>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="large-12 columns responsive">
                                                         <span class="label label-important">NOTE!</span>
                                                         <span><small>Maximum width and height is <b>200px</b> and minimun width and height is <b>30px</b>.</small></span>
                                                      </div>
                                                      <div class="clearfix">&nbsp;</div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewshoploading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='shopadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span7">
                                             <div class="control-group">
                                                <label class="control-label">Shop</label>
                                                <div class="controls">
                                                   <div id="shopframe">
                                                      <select name="shop" id='shop' class="chosen span12">
                                                         @if($response['shop'])
                                                            @foreach($response['shop'] as $shop)
                                                               <option value="{{$shop->id}}">{{$shop->name}} ( {{$shop->name_mm}} )</option>
                                                            @endforeach
                                                         @endif
                                                      </select>
                                                   </div>
                                                   <div id='shoploading' class="loader" style="display:none">&nbsp;</div>
                                                   <span class="help-inline"></span>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span5">
                                             @if(Auth::user()->role==8) <botton data-reveal-id="shopform" id="23" class="addshop btn yellow"><i class="icon-plus"></i> Add New</botton>@endif
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
                                       
                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="categoryform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Category</h3>
                                                   <hr>
                                                   <!-- <form id="update-form" name ="update-form" class="horizontal-form" action ='' method= "post"> -->
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                      
                                                       <div class="control-group">
                                                         <label class="control-label">Category</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_category"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">Category (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_categorymm"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       
                                                       <div class="control-group">
                                                         <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="search_key_mm" id="pu_categorysmm"  type="text">
                                                            <p class="help-block">ဥပမာ.  ပနဆန (ပန္နဆုိးနစ္)</p>
                                                         </div>
                                                       </div>

                                                      <div class="clearfix">&nbsp;</div>
                                                      <div class="control-group">
                                                         <label class="control-label">Image</label>
                                                         <div class="controls images-frame">
                                                            <div class="gallery-input">
                                                               <ul>
                                                                  <div class="gallery_container1">
                                                                  </div>
                                                                  <div class="script"></div>
                                                                  <div class="upload1">
                                                                     <span>+</span>
                                                                     <input id="categoryupload" data-url="../categoryphoto/php/index.php" type="file">
                                                                  </div>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="large-12 columns responsive">
                                                         <span class="label label-important">NOTE!</span>
                                                         <span><small>Maximum width and height is <b>200px</b> and minimun width and height is <b>30px</b>.</small></span>
                                                      </div>
                                                      <div class="clearfix">&nbsp;</div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='categoryadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span7">
                                             <div class="control-group">
                                                <label class="control-label">Category</label>
                                                <div class="controls">
                                                   <div id='categoryloading' class="loader" style="display:none">&nbsp;</div>
                                                   <div id="categoryframe">
                                                      <select class="span12 chosen" data-placeholder="Choose a Category" tabindex="3" name="category" id="category" required>
                                                      </select>
                                                      <input type="hidden" name="hdcategoryid" value="0">
                                                   </div>

                                                </div>
                                             </div>
                                          </div>
                                          <div class="span5">
                                             <botton data-reveal-id="categoryform" id="23" class="addcategory btn yellow"><i class="icon-plus"></i> Add New</botton>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="subcategoryform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New SubCategory</h3>
                                                   <hr>
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="control-group">
                                                         <label class="control-label">SubCategory</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_subcategory"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">SubCategory (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_subcategorymm" type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       
                                                       <div class="control-group">
                                                         <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="search_key_mm" id="pu_subcategorysmm" type="text">
                                                            <p class="help-block">ဥပမာ.  ပနဆန (ပန္နဆုိးနစ္)</p>
                                                         </div>
                                                       </div>

                                                      <div class="clearfix">&nbsp;</div>
                                                      <div class="control-group">
                                                         <label class="control-label">Image</label>
                                                         <div class="controls images-frame">
                                                            <div class="gallery-input">
                                                               <ul>
                                                                  <div class="gallery_container1">
                                                                  </div>
                                                                  <div class="script"></div>
                                                                  <div class="upload1">
                                                                     <span>+</span>
                                                                     <input id="subcategoryupload" data-url="../subcategoryphoto/php/index.php" type="file">
                                                                  </div>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="large-12 columns responsive">
                                                         <span class="label label-important">NOTE!</span>
                                                         <span><small>Maximum width and height is <b>200px</b> and minimun width and height is <b>30px</b>.</small></span>
                                                      </div>
                                                      <div class="clearfix">&nbsp;</div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewsubcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='subcategoryadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span7">
                                             <div class="control-group">
                                                <label class="control-label">Subcategory</label>
                                                <div class="controls">
                                                   <div id='subcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                                   <div id="subcategoryframe">
                                                      <select class="span12 chosen" data-placeholder="Choose a Subcategory" tabindex="4" name="subcategory" id="subcategory">
                                                         <option value=""></option>
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="span5">
                                             <botton data-reveal-id="subcategoryform" id="23" class="addcategory btn yellow"><i class="icon-plus"></i> Add New</botton>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="itemcategoryform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New ItemCategory</h3>
                                                   <hr>
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="control-group">
                                                         <label class="control-label">ItemCategory</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_itemcategory"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">ItemCategory (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_itemcategorymm"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       
                                                       <div class="control-group">
                                                         <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="search_key_mm" id="pu_itemcategorysmm"  type="text">
                                                            <p class="help-block">ဥပမာ.  ပနဆန (ပန္နဆုိးနစ္)</p>
                                                         </div>
                                                       </div>

                                                      <div class="clearfix">&nbsp;</div>
                                                      <div class="control-group">
                                                         <label class="control-label">Image</label>
                                                         <div class="controls images-frame">
                                                            <div class="gallery-input">
                                                               <ul>
                                                                  <div class="gallery_container1">
                                                                  </div>
                                                                  <div class="script"></div>
                                                                  <div class="upload1">
                                                                     <span>+</span>
                                                                     <input id="itemcategoryupload" data-url="../itemcategoryphoto/php/index.php" type="file">
                                                                  </div>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="large-12 columns responsive">
                                                         <span class="label label-important">NOTE!</span>
                                                         <span><small>Maximum width and height is <b>200px</b> and minimun width and height is <b>30px</b>.</small></span>
                                                      </div>
                                                      <div class="clearfix">&nbsp;</div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewitemcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='itemcategoryadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span7">
                                             <div class="control-group">
                                                <label class="control-label">Item category</label>
                                                <div class="controls">
                                                   <div id='itemcategoryloading' class="loader" style="display:none">&nbsp;</div>
                                                   <div id="itemcategoryframe">
                                                      <select class="span12 chosen" data-placeholder="Choose a Itemcategory" tabindex="4" name="itemcategory">
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span5">
                                             <botton data-reveal-id="itemcategoryform" id="23" class="addcategory btn yellow"><i class="icon-plus"></i> Add New</botton>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="update_form" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Brand</h3>
                                                   <hr>
                                                   <!-- <form id="update-form" name ="update-form" class="horizontal-form" action ='' method= "post"> -->
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="clear">&nbsp;</div>
                                                       <div class="control-group">
                                                         <label class="control-label">Brand</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_brand" type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">Brand (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_brandmm"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       
                                                       <div class="control-group">
                                                         <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ အတုိေကာက္</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="search_key_mm" id="pu_brandsmm"  type="text">
                                                            <p class="help-block">ဥပမာ.  ပနဆန (ပန္နဆုိးနစ္)</p>
                                                         </div>
                                                       </div>

                                                      <div class="clearfix">&nbsp;</div>
                                                      <div class="control-group">
                                                         <label class="control-label">Image</label>
                                                         <div class="controls images-frame">
                                                            <div class="gallery-input">
                                                               <ul>
                                                                  <div class="gallery_container1">
                                                                  </div>
                                                                  <div class="script"></div>
                                                                  <div class="upload1">
                                                                     <span>+</span>
                                                                     <input id="brandupload" data-url="../brandphoto/php/index.php" type="file">
                                                                  </div>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="large-12 columns responsive">
                                                         <span class="label label-important">NOTE!</span>
                                                         <span><small>Maximum width and height is <b>200px</b> and minimun width and height is <b>30px</b>.</small></span>
                                                      </div>
                                                      <div class="clearfix">&nbsp;</div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewbrandloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='brandadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span7">
                                             <div class="control-group">
                                                <label class="control-label">Brand</label>
                                                <div class="controls">
                                                   <div id="brandframe">
                                                      <select class="span12 chosen" data-placeholder="Choose a Brand" tabindex="1" name="brand" id="brand" required>
                                                         <option value=""></option>
                                                         @if($response['brand'])
                                                            @foreach($response['brand'] as $brand)
                                                               <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                            @endforeach
                                                         @endif
                                                      </select>
                                                   </div>
                                                   <div id='brandloading' class="loader" style="display:none">&nbsp;</div>

                                                </div>
                                             </div>
                                          </div>
                                          <div class="span5">
                                             <botton data-reveal-id="update_form" id="23" class="addshop btn yellow"><i class="icon-plus"></i> Add New</botton>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Model No</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="model_no" placeholder="Model No" />
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Gender</label>
                                          <div class="controls">
                                                <select name="gender" id='gender' class="chosen">
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
                                                   <span>Maximun images is 5. Maximun image size is (980px X 860px ) minimun size is (250px X 250px).</span>
                                                </div>
                                          </div>
                                       </div>
                                       
                                       <div class="control-group">
                                          <label class="control-label">Description</label>
                                          <div class="controls">
                                             <textarea class="span12 ckeditor m-wrap" rows="6" name="description"></textarea>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Description_mm</label>
                                          <div class="controls">
                                             <textarea class="span12 ckeditor m-wrap" rows="6" name="description_mm"></textarea>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Status</label>
                                          <div class="controls">
                                             <select name="status" class="chosen span6">
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

                                        <div class="control-group">
                                          <label class="control-label">Discount</label>
                                          <div class="controls">
                                             <input type="text" class="span6 m-wrap" name="discount" placeholder="discount %" />
                                          </div>
                                       </div>


                                       <div class="row-fluid">
                                          <div class="span2">&nbsp;</div>
                                          <div class="span5">
                                             <botton data-reveal-id="qtyrangeform" class="addcategory btn yellow"><i class="icon-plus"></i> Add Qty Range</botton>
                                          </div>
                                          <div class="span5">&nbsp;</div>
                                       </div>

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="qtyrangeform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Qty Range</h3>
                                                   <hr>
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="control-group">
                                                         <label class="control-label">Start Qty</label>
                                                         <div class="controls">
                                                            <input class="span3 m-wrap" name="name" id="start_qty" onKeyPress="return isNumberKey(event);" type="text" maxlength="6">
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">End Qty</label>
                                                         <div class="controls">
                                                            <input class="span3 m-wrap" name="end_qty" id="end_qty" onKeyPress="return isNumberKey(event);" type="text" maxlength="6">
                                                         </div>
                                                       </div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewqtyrangeloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='qtyrangeadd'>Submit</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span2 info">&nbsp;</div>
                                          <div class="span10  alert alert-info">Price By Item Quantity</div>
                                       </div>

                                       <div id="quantityrangeinfo">
                                          <div class="row-fluid qtyranges">
                                             <div class="span12">
                                                  <div class="span2 push-left">&nbsp;</div>
                                                  <div class="span4 push-left">
                                                      <!-- <div class="row-fluid"> -->
                                                         <label>Quantity Range</label>
                                                         <div id="qtyrangeframe" class="span12">
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
                                                         </div>
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
                                                         <span class="btn orange removerow" onclick="return removeqtyrangerow(this)" disable='true'>Remove</span>
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
                                       <div class="row-fluid">
                                          <div class="span2 info">&nbsp;</div>
                                          <div class="span10"><hr></div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span2 info">&nbsp;</div>
                                          <div class="span10  alert alert-info">Compare price by Shop</div>
                                       </div>

                                       <div id="comparepriceinfo">
                                          <div class="row-fluid comparepricediv">
                                             <div class="span12">
                                                  <div class="span2 push-left">&nbsp;</div>
                                                   <div class="span4 push-left">
                                                      <!-- <div class="row-fluid push-left"> -->
                                                         <label>Shop</label>
                                                         <input type="text" class="m-wrap span8" placeholder="Shop Name"  name="compareshop[]" value="" />
                                                      <!-- </div> -->
                                                   </div>
                                                   <div class="span4 push-left">
                                                      <!-- <div class="row-fluid push-left"> -->
                                                         <label>Price</label>
                                                         <input type="text" class="m-wrap span6" placeholder="Price"  name="compareprice[]" value="" onKeyPress="return isNumberKey(event);" />
                                                      <!-- </div> -->
                                                   </div>
                                                   <div class="span2">
                                                      <div class="row-fluid push-left"><br>
                                                         <span class="btn orange removerow" onclick="return removecompareprice(this)" disable>Remove</span>
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
                                             <button id='addRow' class="btn green btncompareprice">Add Next</button>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>
                                       <div class="row-fluid">
                                          <div class="span2 info">&nbsp;</div>
                                          <div class="span10"><hr></div>
                                       </div> 

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="sizeform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Size</h3>
                                                   <hr>
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="control-group">
                                                         <label class="control-label">Size</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_size"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">Size (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_sizemm" type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewsizeloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='sizeadd'>Submit</button>
                                                         <button type="button" class="btn">Cancel</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row">
                                          <div class="large-12 columns">
                                             <div id="colorform" class="reveal-modal small" data-reveal> 
                                                   <h3><i class="icon-plus"></i>Add New Color</h3>
                                                   <hr>
                                                   <form id="update-form1" class="horizontal-form" action="" method="post" enctype="multipart/form-data">
                                                       <div class="control-group">
                                                         <label class="control-label">Color</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name" id="pu_color"  type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                       <div class="control-group">
                                                         <label class="control-label">Color (Myanmar)</label>
                                                         <div class="controls">
                                                            <input class="span6 m-wrap" name="name_mm" id="pu_colormm" type="text">
                                                            <!-- <span class="help-inline">Some hint here</span> -->
                                                         </div>
                                                       </div>
                                                     
                                                       <div class="form-actions">
                                                         <div id='addnewcolorloading' class="loader" style="display:none">&nbsp;</div>
                                                         <button type="botton" class="btn blue" id='coloradd'>Submit</button>
                                                       </div>
                                                   </form>
                                                 <a class="close-reveal-modal">&#215;</a> 
                                              </div>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span2">&nbsp;</div>
                                          <div class="span5">
                                             <botton data-reveal-id="sizeform" class="addcategory btn yellow"><i class="icon-plus"></i> Add Size</botton>
                                             <botton data-reveal-id="colorform" class="addcategory btn yellow"><i class="icon-plus"></i> Add Color</botton>
                                          </div>
                                          <div class="span5">&nbsp;</div>
                                       </div>

                                       <div class="row-fluid">
                                          <div class="span2 info">&nbsp;</div>
                                          <div class="span10  alert alert-info">Define Color size, size and price.</div>
                                       </div>
                                       <div id="itemlistinfo">
                                          <div class="row-fluid items">
                                             <div class="span2">&nbsp;</div>
                                             <div class="span10">
                                                <!-- <div class="control-group"> -->
                                                   <!-- <div class="span2 push-left">
                                                      <label>Item Code Prefix</label>
                                                      <input type="text" class="m-wrap span12 qty" placeholder="Item code"  name="itemcode[]"  value="" />
                                                  </div> -->
                                                  <div class="span3 push-left">
                                                      <div class="row-fluid">
                                                         <label>Color</label>
                                                         <div id="itemcolorframe" class="span12">
                                                            <select class="span12 color" data-placeholder="Choose a Color" tabindex="3" name="color[]">
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
                                                  </div>
                                                  <div class="span3 push-left">
                                                      <div class="row-fluid">
                                                         <label>Size</label>
                                                         <div id='itemsizeloading' class="loader" style="display:none">&nbsp;</div>
                                                         <div id="itemsizeframe" class="span12">
                                                            <select name="size[]" class="m-wrap span12 size">
                                                            </select>
                                                         </div>
                                                      </div>
                                                  </div>
                                                  <div class="span1 push-left">
                                                      <label>Quantity</label>
                                                      <input type="text" class="m-wrap span12 qty" placeholder="Qty"  name="qty[]"  value="" onKeyPress="return isNumberKey(event);"/>
                                                  </div>
                                                  <div class="span2 push-left">
                                                      <label>Price</label>
                                                      <input type="text" class="m-wrap span12 itemprice" placeholder="Price"  name="price[]" value="" onKeyPress="return isNumberKey(event);" />
                                                   </div>
                                                   <div class="span2 push-left">
                                                      <label>Old Price</label>
                                                      <input type="text" class="m-wrap span12 oldprice" placeholder="OldPrice"  name="oldprice[]" value="" onKeyPress="return isNumberKey(event);" />
                                                   </div>
                                                   <div class="span1 push-left">
                                                      <label>&nbsp;</label>
                                                         <span class="btn orange removerows" onclick="return removerow(this)" disable>X</span>
                                                   </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>
                                       <div class="row-fluid">
                                          <div class="span2">&nbsp;</div>
                                          <div class="span10">
                                             <button id='addRow' class="btn green btnadd">Add Next</button>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>

                                       <div class="control-group">
                                          <label class="control-label">Qty Control (%)</label>
                                          <div class="controls">
                                             <input type="number" name="qty_per_control">
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>

                                       <div class="control-group">
                                          <label class="control-label">Time Sale</label>
                                          <div class="controls">
                                             <select name="timesale" class="chosen span3">
                                                <option value="0">Select Morning/Evening</option>
                                                <option value="1">Morning</option>
                                                <option value="2">Evening</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>

                                       <div class="control-group">
                                          <label class="control-label">Free Get</label>
                                          <div class="controls">
                                             <div class="freeget-toggle-button">
                                                <input type="checkbox" name="freeget" value="1" class="toggle"/>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label">Service</label>
                                          <div class="controls">
                                             <textarea class="span12 ckeditor m-wrap" rows="6" name="service"></textarea>
                                          </div>
                                       </div>

                                       <div class="row-fluid">
                                          <hr>
                                       </div>

                                    </div>

                                    <div class="tab-pane" id="tab2">
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
   {{HTML::script('../../assets/bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}
   {{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}
   {{HTML::script('../../assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js')}}
   {{HTML::script('../../assets/ckeditor/ckeditor.js')}}
   {{HTML::script('../../src/jquery-ui.js')}}
   {{HTML::script('../../src/jquery.fileupload.js')}}
   {{HTML::script('../../js/admin/itemupload1.js')}}
   <script type="text/javascript">
      $(function(){
         var menu_id=$('#menu').val();
          loadcategories(menu_id);
          loadbrands(menu_id);
          // loadcategories(menu_id);
          var cat_id=$('#category').val();
          $('.chosen1').chosen();
          // loadsize();
          if(cat_id){
            loadsubcategory(cat_id);
          }

         $('.freeget-toggle-button').toggleButtons({
               width: 160,
               label: {
                   enabled: "Yes",
                   disabled: "No"
               }
         });

      });
   </script>
   {{HTML::script('../../js/imageupload.js')}}
   {{HTML::script('../../../js/foundation.min.js')}}
   <script type="text/javascript">
     $(document).foundation(); 
   </script>
   {{HTML::script('../../../js/itempopup.js')}} 
@stop