<?php
class CityController extends BaseController
{
	public function getAddCity()
  	{   
	  	return View::make('city.add');
  	}

  	public function postAddCity()
  	{
      $name = Input::get('name');
      $checkcity=City::wherename($name)->first();
      if($checkcity){
        $message['status']=0; 
        $message['info']="Alerady exit.";
        return Redirect::to('/citylist')->with('message', $message);
      }
      $objcity = new City();
      $objcity->name=$name;
      $result = $objcity->save();
      return Redirect::to('/citylist');
  	}

  	public function showCityList()
  	{
      $objcity = City::paginate(12);
      $response = $objcity;
      $totalcount = City::count();
      return View::make('city.list',array('response'=>$response,'totalcount'=>$totalcount));
  	}

    public function getEditCity($id)
    {
      return View::make('city.edit')->with('city',City::find($id));
    }

    public function postEditCity($id)
    {
      $city = new City();
      $affectedRows= City::where('id','=',$id)->update(array('name'=>Input::get('name')));
      return Redirect::to('citylist');
    }

    public function getDeleteCity($id)
    {
      $checkcity=Trip::wherefrom($id)->orwhere('to','=',$id)->first();
      $message['status']=1; 
      $message['info']="Successfully delete city."; 
      if($checkcity){
        $message['status']=0; 
        $message['info']="This city can't delete.";
        return Redirect::to('citylist')->with('message',$message);
      }
      $affectedRows1 = City::where('id','=',$id)->delete();
      return Redirect::to('citylist')->with('message',$message);
    }

    public function postdelCity()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/citylist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = City::where('id','=',$recid)->delete();
      }
      return Redirect::to("/citylist");
    }
    
    public function postSearchCity()
    {
      $keyword = Input::get('keyword');
      $response=$city = City::where('name','LIKE','%'.$keyword.'%')->orderBy('id','DESC')->paginate(10)->orderBy('id','DESC')->paginate(10);
              
        $allCity = City::all();
        $totalcount = count($allCity);
        return View::make('city.list')->with('city',$city)->with('totalcount',$totalcount)->with('response',$response);
    }
    
}
