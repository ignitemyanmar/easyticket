<?php
class BusClassController extends BaseController
{
	public function getAddBusClasses()
  	{   
      if(Auth::user()->role==9){
        $operator = Operator::all();
      }else{
        $operator = Operator::whereid($this->myGlob->operator_id)->get();
      }
	  	return View::make('busclass.add',array('operator'=>$operator));
  	}

  	public function postAddBusClasses()
  	{
      $name = Input::get('name');
      $operator = Input::get('operator');
      $check_exiting=Classes::wherename($name)->whereoperator_id($operator)->first();
      if($check_exiting){
        $message['status']=0;
        $message['info']="This record is already exit.";
        return Redirect::to('/busclasslist?'.$this->myGlob->access_token)->with('message',$message);
      }
      $message['status']=1;
      $message['info']="Successfully insert one record.";
      
      $objbusclass = new Classes();
      $objbusclass->name=$name;
      $objbusclass->operator_id=$operator;
      $result = $objbusclass->save();
      return Redirect::to('/busclasslist?'.$this->myGlob->access_token)->with('message',$message);
  	}

  	public function showBusClassList()
  	{
      if(Auth::user()->role==9){
        $objbusclass = Classes::all();
      }else{
        $objbusclass = Classes::whereoperator_id($this->myGlob->operator_id)->get();
      }
      $response = $objbusclass;
      $totalcount = Classes::count();
      return View::make('busclass.list',array('response'=>$response,'totalcount'=>$totalcount));
  	}

    public function getEditBusClass($id)
    {
                
        return \View::make('busclass.edit')->with('busclass',Classes::find($id));
    }

    public function postEditBusClass($id)
    {
        $operator = operator::all();
        $busclass = new Classes();
        $affectedRows = Classes::where('id','=',$id)->update(array('name'=>Input::get('name')?Input::get('name'):""));
        
        return Redirect::to('busclasslist?'.$this->myGlob->access_token);
    }

    public function getDeleteBusClass($id)
    {       
            $checke_trip=Trip::whereclass_id($id)->first();
            if($checke_trip){
              $message['status']=0;
              $message['info']="You can't delete this class.";
              return Redirect::to('/busclasslist?'.$this->myGlob->access_token)->with('message',$message);
            }

            $message['status']=1;
            $message['info']="Successfully delete one class.";
            $affectedRows1 = Classes::where('id', '=', $id)->delete();
            
            return Redirect::to('/busclasslist?'.$this->myGlob->access_token)->with('message',$message);
            
    }

    public function postdelBusClass()
    {           
           $todeleterecorts=Input::get('recordstoDelete');
           if(count($todeleterecorts) == 0){
            return Redirect::to('/busclasslist?'.$this->myGlob->access_token);
           }
           
            foreach ($todeleterecorts as $recid) {
                $result = Classes::where('id', '=', $recid)->delete();
            }
            return Redirect::to('/busclasslist?'.$this->myGlob->access_token);
    }

    public function postSearchBusClass()
    {
            $keyword =Input::get('keyword');            
            $response=$busclass = Classes::where('name','LIKE','%' . $keyword . '%')
                        ->orderBy('id', 'DESC')->paginate(10);
            $allBusclass = Classes::all();
            $totalcount = count($allBusclass);
            return View::make('busclass.list')->with('busclass', $busclass)->with('totalcount',$totalcount)->with('response',$response);
    
    }
    
}
