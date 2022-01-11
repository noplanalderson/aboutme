<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Agent Function
 * 
 * Detect User Agent or Browser
 *
 * @access	public
 * @return	string	
 * 
*/ 
if (! function_exists('ua'))
{
	function ua()
	{
		$CI =& get_instance();
		$CI->load->library('user_agent');

		if ($CI->agent->is_browser())
		{
		        $agent = $CI->agent->browser().' '.$CI->agent->version();
		}
		elseif ($CI->agent->is_robot())
		{
		        $agent = $CI->agent->robot();
		}
		elseif ($CI->agent->is_mobile())
		{
		        $agent = $CI->agent->mobile();
		}
		else
		{
		        $agent = 'Unidentified User Agent';
		}

		return ['ua' => $agent, 'platform' => $CI->agent->platform()]; // Platform info (Windows, Linux, Mac, etc.)
	}
}

/**
 * Get Geoip Function
 * 
 * Get user's geo ip data from API Service Provider
 *
 * @access	public
 * @param 	string 	$ip
 * @return	array	
 * 
*/ 
function get_geoip()
{
   $ip   = get_real_ip();
   $res  = @file_get_contents('http://ip-api.com/json/'.$ip);
   $res  = json_decode($res, true);

   if(!empty($res)) 
   {
      if($res['status'] === 'success')
      {
         return array(
            'ip_address' => $res['query'],
         	'country' => $res['country'],
            'country_id' => $res['countryCode']
         );
      }
      else
      {
         return array(
            'ip_address' => $res['query'],
            'country' => $res['message'],
            'country_id' => '??'
         );
      }
   }
   else
   {
      return array(
         'ip_address' => '??',
         'country' => '??',
         'country_id' => '??'
      );

   }
}

/**
 * Get Real IP Function
 * 
 * Get real ip address from user
 *
 * @access	public
 * @return	string	
 * 
*/ 
function get_real_ip()
{
   if(!empty($_SERVER['HTTP_CLIENT_IP']))
   {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
   }
   elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   }
   else
   {
      $ip = $_SERVER['REMOTE_ADDR'];
   }
   
   return $ip;
}

function visitorCounter()
{
   $CI =& get_instance();
   
   $CI->load->model('visitor_m');
   $CI->load->helper('cookie');

   $data_ip = get_geoip();

   if ($CI->input->cookie('healme_site_visitor', TRUE) == false) 
   {
      $cookie = array("name" => 'site_visitor', "value" => $data_ip['ip_address'], "expire" => 7200);
      $CI->input->set_cookie($cookie);
      $CI->visitor_m->addVisitor($data_ip['ip_address'], $data_ip['country_id'], $data_ip['country']);
   }
}