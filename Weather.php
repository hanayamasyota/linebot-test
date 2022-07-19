<?php 
  function get_weather( $lat = 35.97274088130544 , $lon =136.18178082237833, $type = null ){ 

  

    $appid = "00df69bb65232bdb76c73774b4d36585"; 
  
    $url = "http://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $lon . "&units=metric&lang=ja&APPID=" . $appid; 
  
    $json = file_get_contents( $url ); 
  $json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
     
  
    $json_decode = json_decode( $json ); 
  
    
  
    //現在の天気 
  
    if( $type  === "weather" ): 
  
      $output = $json_decode->current->weather[0]->description; 
  
    
  
    //現在の天気アイコン 
  
    elseif( $type === "icon" ): 
  
      $output = "<img src='https://openweathermap.org/img/wn/" . $json_decode->current->weather[0]->icon . "@2x.png'>"; 
  
    
  
    //現在の気温 
  
    elseif( $type  === "temp" ): 
  
      $output = $json_decode->current->temp; 
  
    //type パラメータがないときは配列を出力 
  
    else: 
  
      $output = $json_decode; 
  
    
  
    endif; 
  
    
  
    return $output; 
  
  }
?> 