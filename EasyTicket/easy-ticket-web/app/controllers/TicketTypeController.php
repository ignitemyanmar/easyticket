<?php
class TicketTypeController extends BaseController
{
	public function getAddTicketType()
  	{   
	  	return View::make('tickettype.add');
  	}

  	public function postAddTicketType()
  	{
      $name = Input::get('name');
      $objtickettype = new TicketType();
      $objtickettype->name=$name;
      $result = $objtickettype->save();
      return Redirect::to('/tickettypelist');
  	}

  	public function showTicketTypeList()
  	{
      $objtickettype = TicketType::paginate(12);
      $response = $objtickettype;
      $totalcount = TicketType::count();
      return View::make('tickettype.list',array('response'=>$response,'totalcount'=>$totalcount));
  	}

    public function getEditTicketType($id)
    {
      return View::make('tickettype.edit')->with('tickettype',TicketType::find($id));
    }

    public function postEditTicketType($id)
    {
      $tickettype = new TicketType();
      $affectedRows= TicketType::where('id','=',$id)->update(array('name'=>Input::get('name')));
      return Redirect::to('tickettypelist');
    }

    public function getDeleteTicketType($id)
    {
      $affectedRows1 = TicketType::where('id','=',$id)->delete();
      return Redirect::to('tickettypelist');
    }

    public function postdelTicketType()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/tickettypelist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = TicketType::where('id','=',$recid)->delete();
      }
      return Redirect::to("/tickettypelist");
    }
    
    public function postSearchTicketType()
    {
      $keyword = Input::get('keyword');
      $response=$tickettype = TicketType::where('name','LIKE','%'.$keyword.'%')->orderBy('id','DESC')->paginate(10);
              
        $allTicketType = TicketType::all();
        $totalcount = count($allTicketType);
        return View::make('tickettype.list')->with('tickettype',$tickettype)->with('totalcount',$totalcount)->with('response',$response);
    }
    
}
