<div class="check-a">
  @if($jsoncloseseat)
     <?php $k=1;  $columns=$response['column'];?>
     @foreach($jsoncloseseat as $rows)
        @if($k%$columns == 1)
        <div class="row-fluid">
           <div class="span1 small-1">&nbsp;</div>
        @endif
           <div class="span2 small-2 ">
             @if($rows['status']==0)
              <div class="span2 small-2">&nbsp;</div>
             @else
              <div class="checkboxframe">
                    <label>
                        <span class=""></span>
                        <?php 
                          if($rows['status'] != 1){
                             $disabled="disabled";
                             $taken="taken";
                          }elseif($rows['operatorgroup_id']!=0){
                             $color=OperatorGroup::whereid($rows['operatorgroup_id'])->pluck('color');
                             $disabled=''; 
                             $taken="operator_".$color.' operatorseat_'.$color;
                          }else{
                             $disabled=''; 
                             $taken='available';  
                          }
                         ?>
                       <div style="opacity:.1;">
                          <input class="radios" type="checkbox" multiple="multiple" value="{{$rows['seat_no']}}" name="seats[]" {{ $disabled }} @if($rows['operatorgroup_id']!=0) checked @endif>
                       </div>
                        <div class="fit-a {{$taken}}" title="{{$rows['seat_no']}}" id="">{{$rows['seat_no']}}</div>
                    </label>
                </div>
             @endif
             
           </div>
        @if($k%$columns==0)
           <div class="span3 small-2">&nbsp;</div>
        </div>
        <div style="clear:both;height:0px;">&nbsp;</div>
         @endif
           <?php $k++;?>
     @endforeach
  @endif
</div>
{{HTML::script('../js/ownseat.js')}}
