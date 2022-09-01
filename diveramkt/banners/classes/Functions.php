<?php namespace Diveramkt\Banners\Classes;

use Request;

class Functions
{

  public static function prep_url($url) {
    $url=trim($url);
    if(strpos("[".$url."]", "#") && !strpos("[".$url."]", "/#")) $url=str_replace('#', '/#', $url);
    if(!strpos("[".$url."]", ".") && (!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://"))) $url=url($url);
    if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
    // if(Request::server('HTTPS') == 'on'){
    //   $url='https://'.$url;
    // }else{
      $url='http://'.$url;
    // }
    }
    return $url;
  }

  public static function target($link){
    // $url = 'http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://' . Request::server('HTTP_HOST');
    $link=str_replace('//www.','//',$link); $url=str_replace('//www.','//',url('/'));
    if(!strpos("[".$link."/]", $url)) return 'target=_blank';
    else return 'target=_parent';
  }

  public static function whats_link($tel, $msg=false){
    if(isset($_SERVER['HTTP_USER_AGENT'])){
      $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
      $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
      $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
      $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
      $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

      $extra=''; if(!strpos("[".$tel."]", "+")) $extra='55';

      if ($iphone || $android || $palmpre || $ipod || $berry == true) {
        $link='https://api.whatsapp.com/send?phone='.$extra;
      } else {
        $link='https://web.whatsapp.com/send?phone='.$extra;
      }
      $link=$link.preg_replace("/[^0-9]/", "", $tel);
      if($msg) $link.='&text='.$msg;
      return $link;
    }else return $tel;
  }

  public static function phone_link($string, $cod=''){
    $link='';
    $link.=$cod.preg_replace("/[^0-9]/", "", $string);
    if(!strpos("[".$string."]", "+")) $link='+55'.$link;
    else $link='+'.$link;
    return 'tel:'.$link;
  }

}
