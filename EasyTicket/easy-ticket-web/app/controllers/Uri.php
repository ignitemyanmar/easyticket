<?php 

class Uri
{

  public $ip = null;
  public $status = 0;

  function __construct($ip_address)
  {
    if($ip_address)
      $this->ip = $ip_address;
    else
      return "Error: define ip address.";
  }

  public function post($url, $param = array()){

    $curl = curl_init( $this->ip.$url );
    curl_setopt( $curl, CURLOPT_POST, true );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, $param );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
    
    $response = curl_exec( $curl );

    $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);

    return $response;

  }

  public function get($url, $param = array()){
    $curl = curl_init( $this->ip.$url.'?'.$param );
    
    $response = curl_exec( $curl );

    $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);

    return $response;
  }

  private function array2param($array = array()){
    $param = null;
    foreach ($array as $key => $value) {
      $param .= $key.'='.$value.'&';
    }
    return $param;
  }

  public function status(){
    $this->status;
  }

}