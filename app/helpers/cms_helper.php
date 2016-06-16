<?php

function e($string)
{
    return htmlentities($string);
}

function get_subnavigation($array)
{
    $CI =& get_instance();
    $str = '';
        if (count($array)) {
        $str .= '<ul class="nav nav-tabs nav-subnav">'  . PHP_EOL ;
            foreach ($array as $item) {
                $active = $CI->uri->segment(1) == $item['slug'] ? TRUE : FALSE;
                $str .= $active ? '<li role="presentation" class="active">': '<li role="presentation">';
                $str .= '<a href="'. site_url(e($item['slug'])).'"><span>'.e($item['title']).'</span></a>' . PHP_EOL;
                $str .= '</li>' . PHP_EOL ;
            }
        $str .= '</ul>' . PHP_EOL ;
    }
    return $str;
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

function fdate($format, $date)
{
    return date($format, strtotime($date));
}

function cmsdate($tanggal,$type)
{
  if ($type == 1){
    $tahun  = substr($tanggal, 0, 4);
    $bulan  = substr($tanggal, 5, 2);
    $tgl  = substr($tanggal, 8, 2);

    if ($bulan=='01'){ $bulan1='Jan'; }
    if ($bulan=='02'){ $bulan1='Feb'; }
    if ($bulan=='03'){ $bulan1='Mar'; }
    if ($bulan=='04'){ $bulan1='Apr'; }
    if ($bulan=='05'){ $bulan1='Mei'; }
    if ($bulan=='06'){ $bulan1='Jun'; }
    if ($bulan=='07'){ $bulan1='Jul'; }
    if ($bulan=='08'){ $bulan1='Agu'; }
    if ($bulan=='09'){ $bulan1='Sep'; }
    if ($bulan=='10'){ $bulan1='Okt'; }
    if ($bulan=='11'){ $bulan1='Nov'; }
    if ($bulan=='12'){ $bulan1='Des'; }

    $tgl = "$tgl $bulan1 $tahun";
    return $tgl;
  }
  elseif($type == 2)
  {
    $tahun     = substr("$tanggal", 0, 4);
    $bulan     = substr("$tanggal", 5, 2);
    $tgl       = substr("$tanggal", 8, 2);
    $jam       = substr("$tanggal", 11, 2);
    $mnt       = substr("$tanggal", 14, 2);
    if ($bulan =="01"){ $bulan1="Januari"; }
    if ($bulan =="02"){ $bulan1="Februari"; }
    if ($bulan =="03"){ $bulan1="Maret"; }
    if ($bulan =="04"){ $bulan1="April"; }
    if ($bulan =="05"){ $bulan1="Mei"; }
    if ($bulan =="06"){ $bulan1="Juni"; }
    if ($bulan =="07"){ $bulan1="Juli"; }
    if ($bulan =="08"){ $bulan1="Agustus"; }
    if ($bulan =="09"){ $bulan1="September"; }
    if ($bulan =="10"){ $bulan1="Oktober"; }
    if ($bulan =="11"){ $bulan1="November"; }
    if ($bulan =="12"){ $bulan1="Desember"; }

    $time = mktime(0,0,0,$bulan,$tgl,$tahun);
    $hari = getdate($time);
    $array_hari = array("Monday" => "Senin", "Tuesday" => "Selasa", "Wednesday" => "Rabu", "Thursday" => "Kamis",
              "Friday" => "Jum'at", "Saturday" => "Sabtu", "Sunday" => "Minggu");
    $hari = $array_hari[$hari['weekday']];
    $tgl  = "$hari, $tgl $bulan1 $tahun $jam:$mnt WIB ";
    return $tgl;
  }
  elseif ($type == 3) {
     // Wednesday, 13 November 2013 10:45:28 AM
    return date("l, j F Y h:i:s A",strtotime($tanggal));
  }
  elseif($type == 4)
  {
    // Wednesday, 13 November 2013
    return date("l, j F Y", strtotime($tanggal));
  }
  elseif($type == 5)
  {
    // Wednesday, 13 November 2013
    return date("Y/m/d", strtotime($tanggal));
  }
  elseif($type == 6)
  {
    $tahun     = substr("$tanggal", 0, 4);
    $bulan     = substr("$tanggal", 5, 2);
    $tgl       = substr("$tanggal", 8, 2);
    $jam       = substr("$tanggal", 11, 2);
    $mnt       = substr("$tanggal", 14, 2);
    if ($bulan =="01"){ $bulan1="Januari"; }
    if ($bulan =="02"){ $bulan1="Februari"; }
    if ($bulan =="03"){ $bulan1="Maret"; }
    if ($bulan =="04"){ $bulan1="April"; }
    if ($bulan =="05"){ $bulan1="Mei"; }
    if ($bulan =="06"){ $bulan1="Juni"; }
    if ($bulan =="07"){ $bulan1="Juli"; }
    if ($bulan =="08"){ $bulan1="Agustus"; }
    if ($bulan =="09"){ $bulan1="September"; }
    if ($bulan =="10"){ $bulan1="Oktober"; }
    if ($bulan =="11"){ $bulan1="November"; }
    if ($bulan =="12"){ $bulan1="Desember"; }

    $time = mktime(0,0,0,$bulan,$tgl,$tahun);
    $hari = getdate($time);
    $array_hari = array("Monday" => "Senin", "Tuesday" => "Selasa", "Wednesday" => "Rabu", "Thursday" => "Kamis",
              "Friday" => "Jum'at", "Saturday" => "Sabtu", "Sunday" => "Minggu");
    $hari = $array_hari[$hari['weekday']];
    $tgl  = "$hari, $tgl $bulan1 $tahun";
    return $tgl;
  }
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


function get_navigation($array, $child = FALSE)
{
    $CI =& get_instance();
    $sub = '';

        if (count($array)) {

        $str .= $child == FALSE ? '<ul class="nav navbar-nav">'  . PHP_EOL : '<ul class="dropdown-menu">'  . PHP_EOL;

        foreach ($array as $item) {
        $active = $CI->uri->segment(1) == $item['slug'] ? TRUE : FALSE;
        if(isset($item['children']) && count($item['children']))
        {
            if ($item['parent_id'] == '0')
            {
                $str .= $active ? '<li class="dropdown open">': '<li class="dropdown">';
                $str .= '<a href="'. site_url(e($item['slug'])).'" class="dropdown-toggle" data-toggle="dropdown"><span>'.e($item['title']).'</span> <b class="caret white-caret"></b></a>' . PHP_EOL;
            }
            else
            {
                $str .= $active ? '<li class="dropdown dropdown-submenu open">': '<li class="dropdown dropdown-submenu">';
                $str .= '<a href="'. site_url(e($item['slug'])).'" class="dropdown-toggle" data-toggle="dropdown"><span>'.e($item['title']).'</span></a>' . PHP_EOL;
            }

            $str .= get_navigation($item['children'],TRUE);
        }
        else
        {
            $str .= $active ? '<li class="active">': '<li>';
            $str .= '<a href="'. site_url(e($item['slug'])).'"><span>'.e($item['title']).'</span></a>' . PHP_EOL;
        }
            $str .= '</li>' . PHP_EOL ;
        }
        $str .= '</ul>' . PHP_EOL ;
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
