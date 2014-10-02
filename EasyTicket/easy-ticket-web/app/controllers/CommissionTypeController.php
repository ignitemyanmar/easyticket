<?php
class CommissionTypeController extends BaseController
{
	public function getAddcommissiontype()
  	{   
	  	return View::make('commissiontype.add');
  	}

  	public function postAddCommissiontype()
  	{
      $name = Input::get('name');
      $objcommissiontype = new CommissionType();
      $objcommissiontype->name=$name;
      $result = $objcommissiontype->save();
      return Redirect::to('/commissiontypelist');
  	}

  	public function showCommissiontypeList()
  	{
      $objcommissiontype = CommissionType::paginate(12);
      $response = $objcommissiontype;
      $totalcount = CommissionType::count();
      return View::make('commissiontype.list',array('response'=>$response,'totalcount'=>$totalcount));
  	}

    public function getEditCommissiontype($id)
    {
      return View::make('commissiontype.edit')->with('commissiontype',CommissionType::find($id));
    }

    public function postEditCommissiontype($id)
    {
      $commissiontype = new CommissionType();
      $affectedRows= CommissionType::where('id','=',$id)->update(array('name'=>Input::get('name')));
      return Redirect::to('commissiontypelist');
    }

    public function getDeleteCommissiontype($id)
    {
      $affectedRows1 = CommissionType::where('id','=',$id)->delete();
      return Redirect::to('commissiontypelist');
    }

    public function postdelCommissiontype()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/commissiontypelist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = CommissionType::where('id','=',$recid)->delete();
      }
      return Redirect::to("/commissiontypelist");
    }
    
    public function postSearchCommissiontype()
    {
      $keyword = Input::get('keyword');
      $response=$commissiontype = CommissionType::where('name','LIKE','%'.$keyword.'%')->orderBy('id','DESC')->paginate(10);
              
        $allcommissiontype = CommissionType::all();
        $totalcount = count($allcommissiontype);
        return View::make('commissiontype.list')->with('commissiontype',$commissiontype)->with('totalcount',$totalcount)->with('response',$response);
    }
    
}
