<?php 
    
    function _GET($label='',$default='',$set_default=false){
        
        $value=$default;
        
        if(isset($_GET[$label])&&!empty($_GET[$label])){
            
            $value=$_GET[$label];
        }
        
        if($set_default===true&&(!isset($_GET[$label])||$_GET[$label]=='')){
            
            $_GET[$label]=$default;
        }

        return $value;		
    }
    
    function _POST($label='',$default='',$set_default=false){
        
        $value=$default;
        
        if(isset($_POST[$label])&&!empty($_POST[$label])){
            
            $value=$_POST[$label];
        }

        if($set_default===true&&(!isset($_POST[$label])||$_POST[$label]=='')){
            
            $_POST[$label]=$default;
        }		
        
        return $value;		
    }	
    
    function _SESSION($label='',$default='',$set_default=false){
        
        $value=$default;
        
        if(isset($_SESSION[$label])&&!empty($_SESSION[$label])){
            
            $value=$_SESSION[$label];
        }
        
        if($set_default===true&&(!isset($_SESSION[$label])||$_SESSION[$label]=='')){
            
            $_SESSION[$label]=$default;
        }	

        return $value;		
    }	


function callAPI($method, $url, $data){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: 111111111111111111111',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

function APIlog($return, $user) {
    $result = json_decode($return, true);
        if(!$result[success] == 1) {
            $data = $return;
            //echo $data;
            Logger::error("SEND TELEGRAM MESSAGE FAILED FOR USERID: ".$user); // LOGGER
            Logger::error($data); // LOGGER
        }
}