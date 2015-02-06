<?php
class SeatLayoutController extends BaseController
{
	public function getAddSeatLayout()
  	{   
	  	$response=$seatlayout =SeatingLayout::all();
	  	$tickettype = TicketType::all();
	  	return View::make('seatlayout.add',array('seatlayout'=>$seatlayout,'tickettype'=>$tickettype,'response'=>$response));
  	}

  	public function postAddSeatLayout()
  	{
  		$tickettype = Input::get('tickettype');
  		$row  		  = Input::get('row');
  		$column 	  = Input::get('column');
  		$seatlist 	= Input::get('seats');
      $i=0;
      $seatliststring=array();
      $totalseat=$row * $column;
      for($j=1; $j<=$totalseat; $j++){
        $status=0;
        foreach($seatlist as $value)
        {
          if($value==$j)
          {
            $status =1;
          }
        } 
        if($status==0){
          $seatliststring[]=0;
        }else{
          $seatliststring[]=1;
        }
      }
      $string=json_encode($seatliststring);
      $substring = substr($string, 1);
      $substring = substr($substring, 0,-1);

      
      // dd($substring);

      // return Response::json($sub_str);

  		$seatlayout = new SeatingLayout();
  		$seatlayout->ticket_type_id 	= $tickettype;
  		$seatlayout->row 			        = $row;
  		$seatlayout->column 		      = $column;
  		$seatlayout->seat_list 		    = $substring;
  		$result=$seatlayout->save();
      	return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token);

  	}

  	public function showSeatLayoutList()
  	{
      $response   	= $obj_seatlayout = SeatingLayout::orderBy('id','desc')->get();
  		$allSeatLayout 	= SeatingLayout::all();
      	$totalCount 	= count($allSeatLayout);
  		$i=0;
       foreach ($response as $seatlayout)
       {
          $tickettypename = TicketType::where('id','=',$seatlayout['ticket_type_id'])->pluck('name');
        
            $response[$i]['id']              = $seatlayout['id'];
            $response[$i]['ticket_type_id']  = $tickettypename;
            $response[$i]['row']           	 = $seatlayout['row'];
            $response[$i]['column']          = $seatlayout['column'];
            $response[$i]['seatlist']        = $seatlayout['seatlist'];
            $i++;
        } 
		return View::make('seatlayout.list', array(
        'response'    	 =>  $response,
        'obj_seatlayout' =>  $obj_seatlayout,
        'totalCount'     =>  $totalCount
        ));

		// return View::make('seatlayout.list',array('response'=>$response,'totalCount'=>$totalCount));

  	}

    public function postSeatLayout()
    {
      $ticket_type_id = Input::get('tickettypeid');
      $row = Input::get('row');
      $column = Input::get('column');

      if(!$ticket_type_id || !$row || !$column )
      {
        $response['message']='Required fields are ticket_type_id, row and column';
        return Response::json($response);
      }
      if($row==0||$column==0)
      {
        $response['message'] = 'Row and Column Should Not be zero';
      }
      $seatlayout=array();
      $seatlayout['ticket_type_id'] = $ticket_type_id;
      $seatlayout['row'] = $row;
      $seatlayout['column'] = $column;

      return View::make('seatlayout.ajax.seat',array('seatlayout'=>$seatlayout));
    }

    public function getEditSeatLayout($id)
    {
      $obj_seatlayout=SeatingLayout::find($id);
      if($obj_seatlayout){
        $ticket_type_id = $obj_seatlayout->ticket_type_id;
        $row = $obj_seatlayout->row;
        $column = $obj_seatlayout->column;
        if($row==0||$column==0)
        {
          $response['message'] = 'Row and Column Should Not be zero';
        }
        $seatlayout=array();
        $seatlayout['ticket_type_id'] = $ticket_type_id;
        $seatlayout['row'] = $row;
        $seatlayout['column'] = $column;
      }


      
      // return View::make('seatlayout.ajax.seat',array('seatlayout'=>$seatlayout));


      $obj_seatlayout = SeatingLayout::find($id);
       if(count($obj_seatlayout)==0){
        return Redirect::to('seatlayoutlist');
       }
       $seatlayout['id'] = $id;
       $seatlayout['ticket_type_id'] = $obj_seatlayout->ticket_type_id;
       $seatlayout['row'] = $obj_seatlayout->row;
       $seatlayout['column'] = $obj_seatlayout->column;
       $seatlayout['seat_list'] = $obj_seatlayout->seat_list;
       
       $tickettype = TicketType::all();
       $response['seatlayout']=$seatlayout;
       $response['tickettype']=$tickettype;
       return View::make('seatlayout.edit',array('tickettype'=>$tickettype,'response'=>$response, 'seatlayout'=>$seatlayout));
    }

    public function postEditSeatLayout($id)
    {
      $row = Input::get('row');
      $column = Input::get('column');
      $seatlist   = Input::get('seats');
      $i=0;
      $seatliststring=array();
      $totalseat=$row * $column;
      for($j=1; $j<=$totalseat; $j++){
        $status=0;
        foreach($seatlist as $value)
        {
          if($value==$j)
          {
            $status =1;
          }
        } 
        if($status==0){
          $seatliststring[]=0;
        }else{
          $seatliststring[]=1;
        }
      }
      $string=json_encode($seatliststring);
      $substring = substr($string, 1);
      $substring = substr($substring, 0,-1);



      $affectedRow = SeatingLayout::where('id','=',$id)->update(array(
                                  'ticket_type_id'=>Input::get('tickettype'),
                                  'row'=>$row,
                                  'column'=> $column,
                                  'seat_list'=>$substring));
      return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token);
        
    }

    public function getDeleteSeatLayout($id)
    {
      $seatplan_ids=SeatingPlan::whereseat_layout_id($id)->lists('id');
      // Trip::where
      $checktrips=array();
      if($seatplan_ids)
        $checktrips=Trip::wherein('seat_plan_id', $seatplan_ids)->lists('id');
      if($checktrips){
        return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token)->with('message',"Can't delete this seat layout for has links with trips.");
      }
      SeatingLayout::where('id','=',$id)->delete();
      SeatingPlan::whereseat_layout_id($id)->delete();
      return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token)->with('message','Successfully delete one record.');
    }

    public function postdelSeatLayout()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token);
      }
      foreach($todeleterecorts as $recid)
      {
        $result = SeatingLayout::where('id','=',$recid)->delete();
      }
      return Redirect::to('/seatlayoutlist?'.$this->myGlob->access_token);
    }

    public function postSearchSeatLayout()
    {
      $keyword = Input::get('keyword');
      $response=$seatlayout = SeatingLayout::where('row','LIKE','%'.$keyword.'%')->orwhere('column','LIKE','%'.$keyword.'%')->
      orderBy('id','DESC')->paginate(10);
      $allseatlayout = SeatingLayout::all();
      $totalCount = count($allseatlayout);
      return View::make('seatlayout.list')->with('seatlayout', $seatlayout)->with('totalCount',$totalCount)->with('response',$response);
    }

    public function getdetail($seatlayout_id){
      $seatlist     = SeatingLayout::whereid($seatlayout_id)->first();
      $seatplan = array();
      $seatplan['ticket_type_id'] = $seatlist->ticket_type_id;
      $seatplan['seat_layout_id'] = $seatlist->id;
      $seatplan['row']      = $seatlist->row;
      $seatplan['column']     = $seatlist->column;
      $seatsformat        =explode(',',$seatlist->seat_list);
      $seatplan['seat_list']    = $seatsformat;
    }

}