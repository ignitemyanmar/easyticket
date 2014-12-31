<?php

class ItemController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
      $items= Item::orderBy('id', 'DESC')                
                ->paginate(16);
      if(count($items)>0){
        $i=0;
        foreach ($items as $row) {
          $items[$i]=$row;
          $items[$i]['shop']=Shop::whereid($row->shop_id)->pluck('name');
          $items[$i]['brand']=Brand::whereid($row->brand_id)->pluck('name');
          $items[$i]['category']=Category::whereid($row->category_id)->pluck('name');
          $items[$i]['subcategory']=SubCategory::whereid($row->subcategory_id)->pluck('name');
          $items[$i]['price']=ItemDetail::whereitem_id($row->id)->pluck('price');
          $items[$i]['item_code']=ItemDetail::whereitem_id($row->id)->pluck('item_code');
          $colorid=ItemDetail::whereitem_id($row->id)->groupBy('color_id')->lists('color_id');
          $colorlist=array();
          if($colorid){
            $colorlist=Color::wherein('id',$colorid)->lists('name');
          }
          $colors='';
          if($colorlist){
            $j=1;
            foreach ($colorlist as $color) {
              if(count($colorlist) == $j)
                $postfix='';
              else 
                $postfix=', ';
              $colors .=$color. $postfix;
              $j++;
            }
          }
          $items[$i]['colors']=$colors;
          
          $sizeid=ItemDetail::whereitem_id($row->id)->groupBy('size_id')->lists('size_id');
          $sizelist=array();
          if($sizeid)
          $sizelist=Size::wherein('id',$sizeid)->lists('name');
          $sizes='';
          if($sizelist){
            $j=1;
            foreach ($sizelist as $size) {
              if(count($sizelist) == $j)
                $postfix='';
              else 
                $postfix=', ';
              $sizes .=$size. $postfix;
              $j++;
            }
          }
          $items[$i]['sizes']=$sizes;

          $pricebyqtyrange=array();
          $objitempricebyqty=ItempriceByQty::whereitem_id($row->id)->get();
          if($objitempricebyqty){
            foreach ($objitempricebyqty as $value) {
              $objqtyranges=QtyrangeforPrice::whereid($value['priceqtyrange_id'])->first();
              if($objqtyranges){
                $temp['quantityrange']=$objqtyranges->startqty.'-'. $objqtyranges->endqty;
                $temp['price']=$value->price;
                $pricebyqtyrange[]=$temp;  
              }
            }
            
          }
          $items[$i]['priceByQtyrange']=$pricebyqtyrange;
          $i++;
        }
      }
        $count=count(Item::all());
        // return Response::json($items);
        $response['count']    =$count;
        return View::make('item.list', array('items'=> $items));
  }

	/**
	 * Show the form for creating a new resource.
   * 980px 450px  slider
	 * 420px 440px  large
   * 430px 225px  2colimg    
   * 240px 225px  gridimg sl below
   * 270px 190px  gridimg
	 * @return Response
	 */
	public function create()
	{
    		$shop=Shop::orderBy('name', 'asc')->get();    
    		$menu=Menu::orderBy('name', 'asc')->get();    
        $brand=Brand::orderBy('name', 'asc')->get();
        $color=Color::orderBy('name', 'asc')->get();
        $quantityrange=QtyrangeforPrice::orderBy('startqty', 'asc')->get();
        $resopnse['shop']=$shop;
        $resopnse['quantityrange']=$quantityrange;
        $resopnse['menu']=$menu;
        $resopnse['color']=$color;
        $resopnse['brand']=$brand;

        return View::make('item.add', array('response'=> $resopnse));
	}

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {       
        $colors=Input::get('color');
        $shop=Input::get('shop');
        $sizes=Input::get('size');
        $prices=Input::get('price');
        $qtys=Input::get('qty');
        $inputs=Input::all();

        // for check same image file name (if exit rename)
          $image_files=array();
          // $hd_filenames=Input::get('hidden_img');
          $files=Input::file('image_file');
          if(count($files)>0 ){
            $k=0;
            foreach($files as $file){
              $FinalFilename="";
              if($file !=null){
                  $OriginalFilename= $files[$k]->getClientOriginalName();
                  if($OriginalFilename <> 'defaultcolor.jpg'){
                    $FinalFilename=$OriginalFilename ;
                    // rename file if it already exists by prefixing an incrementing number
                    $FileCounter = 1;
                    $filename = pathinfo($OriginalFilename, PATHINFO_FILENAME);
                    $extension =  pathinfo($OriginalFilename, PATHINFO_EXTENSION);
                    while (file_exists( 'itemdetailphoto/php/files/thumbnail/'.$FinalFilename ))
                        $FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
                    $destinationPath = 'itemdetailphoto/php/files/temp/';
                    $files[$k]->move($destinationPath, $FinalFilename);
                    
                    Image::make('itemdetailphoto/php/files/temp/'.$FinalFilename)
                          ->resize(null, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save('itemdetailphoto/php/files/large/'.$FinalFilename);

                    Image::make('itemdetailphoto/php/files/temp/'.$FinalFilename)
                          ->resize(null, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save('itemdetailphoto/php/files/medium/'.$FinalFilename);

                    Image::make('itemdetailphoto/php/files/temp/'.$FinalFilename)
                          ->resize(null, 180, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save('itemdetailphoto/php/files/thumbnail/'.$FinalFilename);
                    unlink('itemdetailphoto/php/files/temp/'.$FinalFilename);
                  }else{
                    $FinalFilename=$OriginalFilename;
                  }
                  
              }
              $image_files[]=$FinalFilename;
              $k++;
            }
          }

          $itemcode=Input::get('itemcode');
          $oldprice=Input::get('oldprice');
          //group unique colors
          $j=0;
          $unique_color=array();
          foreach($colors as $val) {
              $same=0;
              if($j==0){
                $unique_color[]=$val;
              }else{
                foreach ($unique_color as $unicolor) {
                   if($val==$unicolor){
                    $same +=1;
                   }
                }
                if($same==0){
                  $unique_color[]=$val;
                }
              } 
            $j++;
          }

          //insert array for match color size image
          $matchcolorsize=array();
          $i=0;
          if(count($colors)>0){
            foreach ($colors as $color) {
              $exit_pair=0;
              $temp['itemcode']=$itemcode[$i];
              $temp['oldprice']=$oldprice[$i];
              $temp['color']   =$color;
              $temp['size']=$sizes[$i];
              $temp['price']=$prices[$i];
              $temp['qty']=$qtys[$i];
              if($i==0){
                $temp['image_file']=$image_files[$i];
                // $temp['image_file']='';
                $matchcolorsize[]=$temp;

              }else{
                foreach ($matchcolorsize as $colorsize) {
                 if($colorsize['color'] ==$color && $colorsize['size']== $sizes[$i]){
                  $exit_pair +=1;
                 }
                }
                if($exit_pair==0){
                  foreach ($unique_color as $key=>$uniq_color) {
                    if($uniq_color==$color){
                      $temp['image_file']=$image_files[$key];
                      // $temp['image_file']='';
                    }
                  }
                  $matchcolorsize[]=$temp;
                }
              }
              $i++;
            }
          }
        
        // return Response::json($matchcolorsize);
        // if(count($shops)> 0){
            $objitem=new Item();
            $objitem->name           =$inputs['it_name'];
            $objitem->name_mm        =$inputs['it_name_mm'];
            $objitem->search_key_mm  =$inputs['it_search_key_mm'];
            $objitem->shop_id        =$shop;
            $objitem->menu_id        =Input::get('menu');
            $objitem->category_id    =Input::get('category');
            $objitem->subcategory_id =Input::get('subcategory');
            $objitem->itemcategory_id=Input::get('itemcategory');
            $objitem->brand_id       =Input::get('brand');
            $objitem->model_no       =Input::get('model_no');
            $objitem->gender         =Input::get('gender');
            if(isset($inputs['gallery']) && count($inputs['gallery'])>0){
              $objitem->image        =$inputs['gallery'][0];
            }
            $objitem->description    =Input::get('description');
            $objitem->description_mm =Input::get('description_mm');
            $objitem->status         =Input::get('status');
            $objitem->discount       =Input::get('discount');
            $objitem->publish        =Input::get('publish') ? Input::get('publish') : 0;
            $objitem->timesale       =Input::get('timesale') ? Input::get('timesale') : 0;
            $objitem->freeget        =Input::get('freeget') ? Input::get('freeget') : 0;
            $objitem->save();



            $maxitemid=Item::max('id');

            $compareshop=Input::get('compareshop');
            $compareprice=Input::get('compareprice');
            if(count($compareshop)){
              $i=0;
              foreach ($compareshop as $arr_compareshop) {
                $check_exiting=ComparePrice::whereitem_id($maxitemid)->whereshop($arr_compareshop)->first();
                if($check_exiting){

                }else{
                  if($compareprice[$i]>0){
                      $objcompareprice=new ComparePrice();
                      $objcompareprice->item_id=$maxitemid;
                      $objcompareprice->shop=$arr_compareshop;
                      $objcompareprice->price=$compareprice[$i];
                      $objcompareprice->save(); 
                    }                   
                }
                $i++;
              }
            }
            if(isset($inputs['gallery']) && count($inputs['gallery'])>0){
              $l=0;
              foreach ($inputs['gallery'] as $galleryimg) {
                if($l>0){
                  $objitemimages=new ItemImages();
                  $objitemimages->item_id=$maxitemid;
                  $objitemimages->image=$galleryimg;
                  $objitemimages->save();
                }
                $l++;
              }
            }

            if(count($matchcolorsize)>0){
              $k=0;
              foreach ($matchcolorsize as $itemdt) {
                $objitemdetail =new ItemDetail();
                $objitemdetail->item_id    =$maxitemid;
                $objitemdetail->size_id    =$itemdt['size'];
                $objitemdetail->color_id   =Color::wherename($itemdt['color'])->pluck('id');
                $objitemdetail->image     =$itemdt['image_file'];
                $objitemdetail->qty       =$itemdt['qty'];
                $objitemdetail->price     =$itemdt['price'];
                $objitemdetail->old_price     =$itemdt['oldprice'];

                $prefix_category=Category::whereid(Input::get('category'))->pluck('itemcode_prefix');
                $prefix_subcategory=SubCategory::whereid(Input::get('subcategory'))->pluck('itemcode_prefix');
                $itemcode=$prefix_category.$prefix_subcategory; 
                $checkitemcode=ItemDetail::where('item_code','like',$itemcode.'%')->orderBy('id','desc')->pluck('item_code');
                if(!$checkitemcode){
                  $item_code=$itemcode.'000001';
                }else{
                  $item_code=++$checkitemcode;
                  /*$prefixlength=strlen($itemcode);
                  $item_code=substr($checkitemcode, $prefixlength);
                  $item_code=printf('%06s',++$item_code);*/

                }
                $objitemdetail->item_code     =$item_code; 
                $objitemdetail->save();
                $k++;
              }
            }


            $quantityrange=Input::get('quantityrange');
            $pricebyqty=Input::get('pricebyqty');
            
            //group unique qtyrange
            if(count($quantityrange) >0){
              $j=0;
              $unique_qtyrange=array();
              foreach ($quantityrange as $val) {
                  $same=0;
                  if($j==0){
                    $unique_qtyrange[]=$val;
                  }else{
                    foreach ($unique_qtyrange as $uniqty) {
                       if($val==$uniqty){
                        $same +=1;
                       }
                    }
                    if($same==0){
                      $unique_qtyrange[]=$val;
                    }
                  } 
                $j++;
              }

              $c=0;
              foreach ($unique_qtyrange as $qtyrange) {
                if($pricebyqty[$c] !='' && $pricebyqty > 0){
                  $objitempricebyqty= new ItempriceByQty();
                  $objitempricebyqty->item_id=$maxitemid;
                  $objitempricebyqty->priceqtyrange_id=$qtyrange;
                  $objitempricebyqty->price=$pricebyqty[$c];
                  $objitempricebyqty->save();
                }
                $c++;
              }
            }
        // }
        return Redirect::to('item');
  }

  public function getedit($id)
  {
    $objitem=Item::whereid($id)->first();
    return Response::json($objitem);
  }

  public function postupdate($id){
    $objitem=Item::find($id);
    $objitem->name=Input::get('name');
    $objitem->name_mm=Input::get('name_mm');
    $image=Input::get('image');
    if($image)
      $objitem->image=$image;
    $objitem->update();
    return Redirect::to('/');
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */

  public function edit($id)
  {
    $responsecombo=array();
    $objitem= Item::with(array('itemdetail','itemimages','itempricebyqtyrange','compareprice','shop','menu','category', 'subcategory','itemcategory'))->find($id);
    $shop=Shop::get(array('id','name','name_mm'));
    $menu=Menu::get(array('id','name','name_mm'));
    $menu_id=$objitem->menu_id;
    $category=Category::wheremenu_id($objitem->menu_id)->get(array('id','name','name_mm'));
    $subcategory=SubCategory::wherecategory_id($objitem->category_id)->get(array('id','name','name_mm'));
    $itemcategory=ItemCategory::wheresubcategory_id($objitem->subcategory_id)->get(array('id','name','name_mm'));
    $brand=Brand::wheremenu_id($menu_id)->get(array('id','name','name_mm'));
    $quantityrange=QtyrangeforPrice::orderBy('startqty', 'asc')->get();
    $color=Color::orderBy('name', 'asc')->get();
    $size=Size::wherecategory_id($objitem->category_id)->orderBy('name', 'asc')->get();

    $responsecombo['quantityrange']=$quantityrange;
    $responsecombo['color']=$color;
    $responsecombo['size']=$size;
    $responsecombo['shop']=$shop;
    $responsecombo['menu']=$menu;
    $responsecombo['category']=$category;
    $responsecombo['subcategory']=$subcategory;
    $responsecombo['itemcategory']=$itemcategory;
    $responsecombo['brand']=$brand;
    // return Response::json($responsecombo);
    // return Response::json($objitem);
    return View::make('item.edit', array('response'=> $objitem,'comboarray'=>$responsecombo));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {       
        $colors=Input::get('color');
        $shop=Input::get('shop');
        $sizes=Input::get('size');
        $prices=Input::get('price');
        $qtys=Input::get('qty');
        $inputs=Input::all();
        // return Response::json($sizes);

        // for check same image file name (if exit rename)
          $image_files=array();
          $itemcode=Input::get('itemcode');
          $oldprice=Input::get('oldprice');
          //group unique colors
          $j=0;
          $unique_color=array();
          if(count($colors)>0){
            foreach($colors as $val) 
            {
                $same=0;
                if($j==0){
                  $unique_color[]=$val;
                }else{
                  foreach ($unique_color as $unicolor) {
                     if($val==$unicolor){
                      $same +=1;
                     }
                  }
                  if($same==0){
                    $unique_color[]=$val;
                  }
                } 
              $j++;
            }
          }
          

          //insert array for match color size image
          $matchcolorsize=array();
          $i=0;
          if(count($colors)>0){
            foreach ($colors as $color) {
              $exit_pair=0;
              $temp['itemcode']=$itemcode[$i];
              $temp['oldprice']=$oldprice[$i];
              $temp['color']   =$color;
              $temp['size']=$sizes[$i];
              $temp['price']=$prices[$i];
              $temp['qty']=$qtys[$i];
              if($i==0){
                // $temp['image_file']='$image_files[$i]';
                $temp['image_file']='';
                $matchcolorsize[]=$temp;

              }else{
                foreach ($matchcolorsize as $colorsize) {
                 if($colorsize['color'] ==$color && $colorsize['size']== $sizes[$i]){
                  $exit_pair +=1;
                 }
                }
                if($exit_pair==0){
                  foreach ($unique_color as $key=>$uniq_color) {
                    if($uniq_color==$color){
                      // $temp['image_file']=$image_files[$key];
                      $temp['image_file']='';
                    }
                  }
                  $matchcolorsize[]=$temp;
                }
              }
              $i++;
            }
          }
        
        // return Response::json($matchcolorsize);
        // if(count($shops)> 0){
            $objitem=Item::find($id);
            $objitem->name           =$inputs['it_name'];
            $objitem->name_mm        =$inputs['it_name_mm'];
            $objitem->search_key_mm  =$inputs['it_search_key_mm'];
            $objitem->shop_id        =$shop;
            $objitem->menu_id        =$inputs['menu'];
            $objitem->category_id    =Input::get('category');
            $category_id             =Input::get('category');
            $objitem->subcategory_id =Input::get('subcategory');
            $objitem->itemcategory_id=Input::get('itemcategory');
            $objitem->brand_id       =Input::get('brand');
            $objitem->model_no       =Input::get('model_no');
            $objitem->gender         =Input::get('gender');
            if(isset($inputs['gallery']) && count(Input::get('gallery'))>0){
              $objitem->image        =$inputs['gallery'][0];
            }
            $objitem->description    =Input::get('description');
            $objitem->description_mm =Input::get('description_mm');
            $objitem->status         =$inputs['status'];
            $objitem->discount       =Input::get('discount');
            $objitem->publish        =Input::get('publish') ? Input::get('publish') : 0;
            $objitem->timesale        =Input::get('timesale') ? Input::get('timesale') : 0;
            $objitem->freeget        =Input::get('freeget') ? Input::get('freeget') : 0;
            $objitem->update();

            $maxitemid=$id;
            $compareshop=Input::get('compareshop');
            $compareprice=Input::get('compareprice');
            ComparePrice::whereitem_id($maxitemid)->delete();
            if(count($compareshop)){
              $i=0;
              foreach ($compareshop as $arr_compareshop) {
                $check_exiting=ComparePrice::whereitem_id($maxitemid)->whereshop($arr_compareshop)->first();
                if($check_exiting){

                }else{
                    $objcompareprice=new ComparePrice();
                    $objcompareprice->item_id=$maxitemid;
                    $objcompareprice->shop=$arr_compareshop;
                    $objcompareprice->price=$compareprice[$i];
                    $objcompareprice->save();                    
                }
                $i++;
              }
            }

            if(isset($inputs['gallery']) && count($inputs['gallery'])>0){
              ItemImages::whereitem_id($id)->delete();
              $l=0;
              foreach ($inputs['gallery'] as $galleryimg) {
                if($l>0){
                  $objitemimages=new ItemImages();
                  $objitemimages->item_id=$maxitemid;
                  $objitemimages->image=$galleryimg;
                  $objitemimages->save();
                }
                $l++;
              }
            }

            // ItemDetail::whereitem_id($id)->delete();
            if(count($matchcolorsize)>0){
              $k=0;
              foreach ($matchcolorsize as $itemdt){
                // ItemDetail::whereitem_id($id)->delete();
                $color_id       =Color::wherename($itemdt['color'])->pluck('id');
                $size_id=Size::wherecategory_id($category_id)->wherename($itemdt['size'])->pluck('id');
                $objitemdetail  =ItemDetail::whereitem_id($id)->wheresize_id($size_id)->wherecolor_id($color_id)->first();
                $new=false;
                if(!$objitemdetail){
                  $new=true;
                  $objitemdetail =new ItemDetail();
                }
                $objitemdetail->item_id    =$id;
                $objitemdetail->size_id    =Size::wherecategory_id($category_id)->wherename($itemdt['size'])->pluck('id');
                $objitemdetail->color_id   =$color_id;
                $objitemdetail->image     =$itemdt['image_file'];
                $objitemdetail->qty       =$itemdt['qty'];
                $objitemdetail->price     =$itemdt['price'];
                $objitemdetail->old_price     =$itemdt['oldprice'];

                // $objitemdetail->item_code     =$itemdt['itemcode'];
                $prefix_category=Category::whereid(Input::get('category'))->pluck('itemcode_prefix');
                $prefix_subcategory=SubCategory::whereid(Input::get('subcategory'))->pluck('itemcode_prefix');
                $itemcode=$prefix_category.$prefix_subcategory; 
                $checkitemcode=ItemDetail::where('item_code','like',$itemcode.'%')->orderBy('id','desc')->pluck('item_code');
                

                if(strlen($itemcode)>6){
                  $item_code=$itemcode;
                }else{
                    $checkitemcode=ItemDetail::where('item_code','like',$itemcode.'%')->orderBy('id','desc')->pluck('item_code');
                   /* if(!$checkitemcode){
                      $item_code=$itemcode.'000001';
                    }else{
                        $prefixlength=strlen($itemcode);
                      $item_code=substr($checkitemcode, $prefixlength);
                      $item_code=printf('%06s',++$item_code);
                    }*/
                     // $itemcode=$itemdt['itemcode']; 
                    if(!$checkitemcode){
                      $item_code=$itemcode.'000001';
                    }else{
                      $item_code=++$checkitemcode;
                      /*$prefixlength=strlen($itemcode);
                      $item_code=substr($checkitemcode, $prefixlength);
                      $item_code=printf('%06s',++$item_code);*/
                    }
                }
                if($new==true){
                  $objitemdetail->item_code     =$item_code; 
                  $objitemdetail->save();
                }else{
                  $objitemdetail->update();
                }
                $k++;
              }
            }


            $quantityrange=Input::get('quantityrange');
            $pricebyqty=Input::get('pricebyqty');
            
            //group unique qtyrange
            if(count($quantityrange) >0){
              $j=0;
              $unique_qtyrange=array();
              foreach ($quantityrange as $val) {
                  $same=0;
                  if($j==0){
                    $unique_qtyrange[]=$val;
                  }else{
                    foreach ($unique_qtyrange as $uniqty) {
                       if($val==$uniqty){
                        $same +=1;
                       }
                    }
                    if($same==0){
                      $unique_qtyrange[]=$val;
                    }
                  } 
                $j++;
              }

              $c=0;
              ItempriceByQty::whereitem_id($id)->delete();
              foreach ($unique_qtyrange as $qtyrange) {
                if($pricebyqty[$c] !='' && $pricebyqty > 0){
                  $objitempricebyqty= new ItempriceByQty();
                  $objitempricebyqty->item_id=$maxitemid;
                  $objitempricebyqty->priceqtyrange_id=$qtyrange;
                  $objitempricebyqty->price=$pricebyqty[$c];
                  $objitempricebyqty->save();
                }
                $c++;
              }
            }

          

        // }
        return Redirect::to('item');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $images=array();
    $images[]=Item::find($id)->pluck('image');
    $images[]=ItemDetail::whereitem_id($id)->lists('image');
    if(count($images)>0){
      foreach ($images as $image) {
        $filepath="itemphoto/php/files/";
        $Lfilepath="itemphoto/php/files/large/";
        $Mfilepath="itemphoto/php/files/medium/";
        $Tfilepath="itemphoto/php/files/thumbnail/";
        @unlink($filepath.$image);
        @unlink($Lfilepath.$image);
        @unlink($Mfilepath.$image);
        @unlink($Tfilepath.$image);
      }
    }
    Item::find($id)->delete();
    $response="Successfully delete one record.";
    return Redirect::to('item')->with('message', $response);
  }

  public function changepublish($id){
    $objitem=Item::find($id);
    try{
      $objitem->publish=1;
      $objitem->update();
      $response="Successfully publish one record.";
      return Redirect::to('item')->with('message',$response);
    }catch (Exception $e) {
      $response="Something went wrong. Please try again.";
      return Redirect::to('item')->with('message',$response);
    }
  }

  public function changeunpublish($id){
    $objitem=Item::find($id);
    try{
      $objitem->publish=0;
      $objitem->update();
      $response="Successfully unpublish one record.";
      return Redirect::to('item')->with('message',$response);
    }catch (Exception $e) {
      $response="Something went wrong. Please try again.";
      return Redirect::to('item')->with('message',$response);
    }
  }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return ajax call functions for combobox
	 */
	public function categorylist($menuid){
		$objcategory=array();
		$objcategory=Category::wheremenu_id($menuid)->orderBy('name','asc')->get();
		return View::make('item.ajax.category', array('response'=>$objcategory));
	}

	public function subcategorylist($catid){
		$objsubcategory=array();
		$objsubcategory=SubCategory::wherecategory_id($catid)->orderBy('name','asc')->get();
		return View::make('item.ajax.subcategory', array('response'=>$objsubcategory));
	}

	public function itemcategorylist($subcatid){
		$objitemcategory=array();
		$objitemcategory=ItemCategory::wheresubcategory_id($subcatid)->orderBy('name','asc')->get();
		return View::make('item.ajax.itemcategory', array('response'=>$objitemcategory));
	}
	public function itemsizelist($catid){
		$objitemsize=array();
		$objitemsize=ItemSize::wherecategory_id($catid)->orderBy('name','asc')->get();
		return View::make('item.ajax.itemsize', array('response'=>$objitemsize));
	}
	

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// List Item color size and price
    public function getItemDetail($id, $name){
      if($id){
        $temp=array();
        $objitem    =Item::whereid($id)->first();
        if($objitem){
          $temp['id']=$id;
          $temp['name']=$objitem->name;
          $temp['brand']            =Brand::whereid($objitem->brandid)->pluck('name');
          $temp['category']         =Category::whereid($objitem->categoryid)->pluck('name');
          $temp['subcategory']      =SubCategory::whereid($objitem->subcategoryid)->pluck('name');
          $temp['description']      =$objitem->description;
          $temp['arrival']          =$objitem->arrival;
          $temp['gender']           =$objitem->gender;
          $objitemlists             =ItemDetail::whereitem_id($id)->orderBy('color_id','asc')->get();
          $itemlist=array();
          if($objitemlists){
            foreach ($objitemlists as $detail) {
              $tmpdetail['id']        =$detail->id;
              $tmpdetail['color']     =Color::whereid($detail->color_id)->pluck('name');
              $tmpdetail['size']      =Size::whereid($detail->size_id)->pluck('name');
              $tmpdetail['qty']       =$detail->qty;
              $tmpdetail['price']     =$detail->price;
              $tmpdetail['image']     =$detail->image;
              $itemlist[]             =$tmpdetail;
            }
          }
          $temp['itemdata'] =$itemlist;
        }
        $response['item_lists']=$temp;
        // return Response::json($response);

        return View::make('item.itemlistbygroup', array('response' => $response));
      }
    }



    public function getPriceQtyupdate($id){
      $objitemdetail =ItemDetail::whereid($id)->first();
      if($objitemdetail){
        $objitemdetail->qty=Input::get('quantity');
        $objitemdetail->price=Input::get('price');
        $objitemdetail->update();
        return 'Update Success.';
      }
    }

    public function getPriceQtydelete($id){
      $check_exiting=ItemDetail::wheresize_id($id)->first();
      if($check_exiting){
        $message['status']=0;
        $message['info']="You can't delete this record. Has links.";
      }

      Size::find($id)->delete();
      $message['status']=1;
      $message['info']="Successfully delete one record.";
      return Redirect::to('/size')->with('message', $message);
    }

	
}