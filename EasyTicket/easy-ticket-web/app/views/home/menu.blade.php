<style type="text/css">
  .menu_cols ul ul.subcategory{display: none;}
  .menu_cols ul li:hover ul.subcategory{display: block;}
</style>
<?php 
  // $objmenus=Menu::with(array('categories'))->get();
  $objmenus=Menu::with(array('categories' => function($query) 
              {
                  // Notice the addition of the author_id field!
                  $query->addSelect(array('id','name','name_mm','image','priority', 'menu_id'))->orderBy('priority','asc');
              }))->get();
    if($objmenus){
      $i=0;
      foreach ($objmenus as $menu) {
        $objmenus[$i]=$menu;
        if(count($menu->categories)){
          $j=0;
          foreach ($menu->categories as $category) {
            // $objsubcategories=SubCategory::wherecategory_id($category->id)->get();
            $objsubcategories=SubCategory::wherecategory_id($category->id)->orderBy('priority','asc')->get();
            $subcategories=array();
            if($objsubcategories){
              foreach ($objsubcategories as $subcategory) {
                $temp['id']=$subcategory->id;
                $temp['name']=$subcategory->name;
                $temp['name_mm']=$subcategory->name_mm;
                $temp['image']=$subcategory->image;
                $subcategories[]=$temp;
              }
            }
            $objmenus[$i]->categories[$j]['subcategories']=$subcategories;
            $j++;
          }
        }
        $i++;
      }
    }
    $colors=array('light_green','light_green1','light_green2','light_green3','light_green4','light_green5','light_green6','light_green7','light_green8','light_green9','light_green10','light_green11','light_green12','light_green13','light_green14','light_green15');
    $randcolors=array();
    for($i=0; $i<count($objmenus); $i++){
      $key =array_rand($colors);
      $randcolors[]=$colors[$key];
    }
?>
  @if($objmenus)
    <ul class="dropdown submenu_border nav_menu" @if($currentroute=='/')style="display:block; clip:auto;" @else @endif> 
        <?php $i=0; ?>
        @foreach($objmenus as $menu)    
          <li class="has-dropdown">
            <a href="../../../list/{{$menu->id}}" class="{{$randcolors[$i]}}">
            <img class="icon_livingroom" src="../../../../menuphoto/php/files/thumbnail/{{$menu->image}}">
            {{$menu->name_mm}}
            <!-- ဧည့္ခန္းသံုး ပစၥည္းမ်ား  --></a>
            @if(count($menu->categories)>0)
            <div class="dropdown">
              <?php $cat_i=1; $count=count($menu->categories); ?>
              @foreach($menu->categories as $category)
                @if($cat_i==1 || $cat_i%3==1)
                <div class="row menu_cols">
                @endif
                  <div class="large-4 column left">
                    <ul>
                      <li><a href="../../../list/{{$menu->id}}/{{$category->id}}" style="font-size:14px;"><img style="width: 21px; height: 21px;" src="../../../categoryphoto/php/files/thumbnail/{{$category->image}}" alt=""/>&nbsp;{{$category->name_mm}}</a>
                        @if(count($category->subcategories)>0)
                        <ul class="row subcategory">
                          @foreach($category->subcategories as $subcategory)
                            <li><a href="../../../list/{{$menu->id}}/{{$category->id}}/{{$subcategory['id']}}">{{$subcategory['name_mm']}}</a></li>
                          @endforeach
                        </ul>
                        @endif
                      </li>
                      <div class="clear">&nbsp;</div>
                    </ul>
                  </div>
                @if($cat_i==$count || $cat_i%3==0)
                </div>
                @endif
                 
                <?php $cat_i++; ?>
              @endforeach
              
            </div>
            @endif
          </li>
        <?php  $i++ ?> 
        @endforeach
      
    </ul> 
  @endif