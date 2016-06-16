<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function img_filename($length = NULL)
{
	$CI =& get_instance();

	// get datetime
	$date = gmdate('YmdHis',time()+60*60*7);

	// get user id
	$userdata = $CI->ion_auth->user()->row();
	$i = $userdata->user_id;

	$filename = $date . '-' . $i . '-' . random_string($length);

	// replace non letter or digits by -
	$filename = preg_replace('~[^\pL\d]+~u', '-', $filename);

	// transliterate
	$filename = iconv('utf-8', 'us-ascii//TRANSLIT', $filename);

	// remove unwanted characters
	$filename = preg_replace('~[^-\w]+~', '', $filename);

	// trim
	$filename = trim($filename, '-');

	// remove duplicate -
	$filename = preg_replace('~-+~', '-', $filename);

	// lowercase
	$filename = strtolower($filename);

	if (empty($filename))
	{
	  return 'n-a';
	}

	return $filename;
}

function random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}


if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);
        exit;
    }
}

function e($string)
{
    return htmlentities($string);
}

function fdate($format, $date)
{
    return date($format, strtotime($date));
}


function timezone_list() {
    static $timezones = null;

    if ($timezones === null) {
        $timezones = array();
        $offsets = array();
        $now = new DateTime();

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            $timezones[$timezone] = '(' . format_GMT_offset($offset) . ') ' . format_timezone_name($timezone);
        }

        array_multisort($offsets, $timezones);
    }

    return $timezones;
}

function format_GMT_offset($offset) {
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
}

function format_timezone_name($name) {
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    return $name;
}

function limit_to_numwords($string, $numwords)
{
    $excerpt = explode(' ', $string, $numwords + 1);
    if (count($excerpt) >= $numwords)
    {
        array_pop($excerpt);
    }
    $excerpt = implode(' ', $excerpt);
    return $excerpt;
}


function get_ol($array, $child = FALSE)
{
    $str = '';
    if (count($array)) {

        $str .= $child == FALSE ? '<ol class="dd-list">' : '<ol class="dd-list">';

        foreach ($array as $item) {
            $str .= '<li class="dd-item" data-id="'.$item['id'].'" data-parent="'.$item['parent_id'].'">';
            $str .= '<div class="dd-handle">'.$item['title'].'</div>';

            // Do you have any childern ?
            if (isset($item['children']) && count($item['children']))
            {
                $str .= get_ol($item['children'], TRUE);
            }
            $str .= '</li>' . PHP_EOL ;
        }
        $str .= '</ol>' . PHP_EOL ;
    }
    return $str;
}

function ordered_menu($array,$parent_id = 0)
{
  $temp_array = array();
  foreach($array as $element)
  {
    if($element['parent_id']==$parent_id)
    {
      $element['children'] = ordered_menu($array,$element['id']);
      $temp_array[] = $element;
    }
  }
  return $temp_array;
}


function time_elapsed_string($datetime, $full = false) {
    $CI =& get_instance();
    $today = time();
    $createdday = strtotime($datetime);
    $datediff   = abs($today - $createdday);
    $difftext   = "";
    $years      = floor($datediff / (365*60*60*24));
    $months     = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));
    $days       = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours      = floor($datediff/3600);
    $minutes    = floor($datediff/60);
    $seconds    = floor($datediff);

     //year checker
     if($difftext=="")
     {
       if($years>1)
        $difftext=$years." years ago";
       elseif($years==1)
        $difftext=$years." year ago";
     }
     //month checker
     if($difftext=="")
     {
        if($months>1)
        $difftext=$months." months ago";
        elseif($months==1)
        $difftext=$months." month ago";

     }
     //days checker
     if($difftext=="")
     {
        if($days>1)
        $difftext=$days." days ago";
        elseif($days==1)
        $difftext=$days." day ago";
     }
     //hour checker
     if($difftext=="")
     {
        if($hours>1)
        $difftext=$hours." hours ago";
        elseif($hours==1)
        $difftext=$hours." hour ago";
     }
     //minutes checker
     if($difftext=="")
     {
        if($minutes>1)
        $difftext=$minutes." minutes ago";
        elseif($minutes==1)
        $difftext=$minutes." minute ago";
     }
     //seconds checker
     if($difftext=="")
     {
        if($seconds>1)
        $difftext=$seconds." seconds ago";
        elseif($seconds==1)
        $difftext=$seconds." second ago";
     }
     return $difftext;
}
