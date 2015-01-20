<?php 
    $user_permissionmenus=UserPermission::whererole(Auth::user()->role)->get();
    $currentroute=Route::getCurrentRoute()->getPath();
    // $currentroute=substr($currentroute,0,3);

$menulist['ေန႔စဥ္ အေရာင္းစာရင္း']             ="<li
                                                    if(strpos($currentroute, 'daiadv')!==false)class='active'
                                                    >
                                                    <a href='/report/dailycarandadvancesale?operator_id=$operator_id'>
                                                        <i class='icon-th-list'></i> 
                                                        <span class='title'>ေန့စဥ်အေရာင်းစာရင်းများ </span>
                                                    </a>
                                                </li>";
                
$menulist['ကားခ်ဳပ္အေရာင္းစာရင္း']            ="<li 
                                                    if(strpos($currentroute, 'dai')!==false)class='active' 
                                                    >
                                                    <a href='/report/dailybydeparturedate'>
                                                        <i class='icon-th-list'></i> 
                                                        <span class='title'>ကားချုပ် စာရင်းများ </span>
                                                    </a>
                                                </li>";

$menulist['ခရီးစဥ္အလုိက္ အေရာင္းစာရင္း']      ="<li if($currentroute=='rep')class=''
                                                    >
                                                    <a href='/report/operator/trip/dateranges?operator_id=$operator_id&trips=1'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ခရီးစဥ်အလုိက်အေရာင်း စာရင်းများ</span>
                                                    </a>
                                                </li>";

$menulist['အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္း']="<li if($currentroute=='rep')class=''
                                                        >
                                                    <a href='/report/operator/trip/dateranges?operator_id=$operator_id&&&agent_id=All'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>အေရာင်းကုိယ်စားလှယ်နှင့် အေရာင်းစာရင်းများ</span>
                                                    </a>
                                                </li>";

$menulist['ၾကိဳတင္မွာယူထားေသာ စာရင္းမ်ား'] ="<li if($currentroute=='rep')class=''
                                                    >
                                                    <a href='/report/booking'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ြကိုတင်မှာယူေသာ စာရင်းများ</span>
                                                    </a>
                                                </li>";

$menulist['အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား']  ="<li if($currentroute=='') class=''
                                                    >
                                                    <a href='/report/bestseller/trip'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>အေရာင်းရဆုံး ခရီးစဥ် စာရင်းများ</span>
                                                    </a>
                                                </li>";

$menulist['အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား']  ="<li if($currentroute=='') class=''
                                                    >
                                                    <a href='/report/agentscredit'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>အေရာင်းကုိယ်စားလှယ် နှင့် အေြကွးစာရင်းများ</span>
                                                    </a>
                                                </li>";
                
$menulist['အေရာင္းကုိယ္စားလွယ္မ်ား']        ="<li if($currentroute=='age')class='has-sub '
                                                    else class='has-sub'
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>အေရာင်းကုိယ်စားလှယ်များ</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/agentgroup/create'>Agent Group Create</a></li>
                                                        <li ><a href='/agentgrouplist'>Agent Group List</a></li>
                                                        <li ><a href='/agents/create'>အေရာင်းကုိယ်စားလှယ် အသစ်ထည့်သွင်းြခင်း</a></li>
                                                        <li ><a href='/agentlist'>အေရာင်းကုိယ်စားလှယ်များ</a></li>
                                                    </ul>
                                                </li>";

$menulist['ေရာင္းျပီးလက္မွတ္မ်ား ဖ်က္ရန္']    ="<li if($currentroute=='ord')class=''
                                                    >
                                                    <a href='/orderlist'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ေရာင်းြပီးလက်မှတ်များ ဖျက်ရန်</span>
                                                    </a>
                                                </li>";

$menulist['ျမိဳ႕မ်ား']                          ="<li if($currentroute=='cit') class='has-sub '
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ြမို့များ</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/city/create'>ြမို့ အသစ်ထည့်သွင်းြခင်း</a></li>
                                                        <li ><a href='/citylist'>ြမို့များ</a></li>
                                                    </ul>
                                                </li>";

$menulist['ကားနံပါတ်']                         ="<li if($currentroute=='car')class='has-sub '
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ကားနံပါတ် သတ်မှတ်ရန်</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/carlist'>ကားနံပါတ် သတ်မှတ်ရန်</a></li>
                                                    </ul>
                                                </li>";

$menulist['ကားအမ်ိဳးအစားမ်ား']               ="<li if($currentroute=='bus') class='has-sub ' 
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ကားအမျိုးအစား</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/busclass/create'>ကားအမျိုးအစား အသစ်ထည့်သွင်းြခင်း</a></li>
                                                        <li ><a href='/busclasslist'>ကားအမျိုးအစားများ</a></li>
                                                    </ul>
                                                </li>";

$menulist['ခုံအေနအထားမ်ား']                   ="<li if($currentroute=='sea') class='has-sub '
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ခုံအေနအထား အစီအစဥ်</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/seatlayout/create'>ခုံအေနအထား အသစ်ထည့်သွင်းြခင်း</a></li>
                                                        <li ><a href='/seatlayoutlist'>ခုံအေနအထားများ</a></li>
                                                    </ul>
                                                </li>";

$menulist['ခရီးစဥ္မ်ား']                        ="<li if($currentroute=='tri') class='has-sub '
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ခရီးစဥ် အသစ်ထည့်မည်။</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/trip/create'>ခရီးစဥ် အသစ်ထည့်မည်။</a></li>
                                                        <li ><a href='/trip-list'>ခရီးစဥ်များ</a></li>
                                                    </ul>
                                                </li>";

$menulist['ခုံနံပါတ္ အစီအစဥ္']                  ="<li
                                                    if(strpos($currentroute,'sea') !==false) class='has-sub'
                                                    >
                                                    <a href='javascript:;'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>ခုံနံပါတ် အစီအစဥ်</span>
                                                    <span class='arrow '></span>
                                                    </a>
                                                    <ul class='sub'>
                                                        <li ><a href='/seatplans/create'>ခုံနံပါတ် သတ်မှတ်ြခင်း</a></li>
                                                        <li ><a href='/seatplanlist'>ခုံနံပါတ်များ</a></li>
                                                    </ul>
                                                </li>"; 

$menulist['လုပ္ပုိင္ခြင့္မ်ား']                 ="<li if($currentroute=='per') class=''
                                                    >
                                                    <a href='/permission'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>လုပ်ပုိင်ခွင့်သတ်မှတ်ရန်</span>
                                                    </a>
                                                </li>"; 

$menulist['User List']                          ="<li if($currentroute=='use') class=''
                                                    >
                                                    <a href='/user-list'>
                                                    <i class='icon-th-list'></i> 
                                                    <span class='title'>User List</span>
                                                    </a>
                                                </li>"; 

?>

@foreach($menulist as $key=>$allmenu)
    @if($user_permissionmenus)
        @foreach($user_permissionmenus as $rows)
            @if($rows->menu== $key)
                {{$menulist[$key]}}
            @endif
        @endforeach
    @endif
@endforeach
