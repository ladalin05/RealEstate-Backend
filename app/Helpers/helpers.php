<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\UserManagement\Module;
use App\Models\Admin\Settings;
use App\Models\UserManagement\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\WebAds;
use App\Models\Favourite;
use App\Models\Admin\PostViews;

if (!function_exists('yesNo')) {
    function yesNo($instance)
    {
        return $instance ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-danger bg-opacity-10 text-danger">No</span>';
    }
}
if (!function_exists('badge')) {
    function badge($text, $status = 'success')
    {
        return '<span class="badge bg-' . $status . ' bg-opacity-10 text-' . $status . '">' . $text . '</span>';
    }
}
if (!function_exists('json')) {
    function json($data)
    {
        header('Content-Type: application/json charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
if (!function_exists('revoke_session')) {
    function revoke_session($user_id)
    {
        if(config('session.driver') == 'database') {
            DB::table('sessions')->where('user_id', $user_id)->delete();
        }
    }
}
if (!function_exists('can')) {
    function can($expression)
    {
        $expression = md5(trim($expression));
        $administrator = session('administrator');
        if ($administrator === true) {
            return true;
        }
        $permissions = session('permissions');
        $access = session('access');
        $expression = trim($expression);
        if (in_array($expression, array_keys($permissions))) {
            if (in_array($expression, array_keys($access))) {
                return true;
            }
            return false;
        }
        return true;
    }
}
if (!function_exists('date_period')) {
    function date_period($start_date, $end_date, $timezone = 'UTC')
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $timezone = new DateTimeZone($timezone);
        $begin = new DateTime($start_date, $timezone);
        $end = new DateTime($end_date, $timezone);
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($begin, $interval, $end);
        $monthsArray = [];
        $m = 0;
        $d = 0;
        $w = 0;
        $days = 0;
        $text = '';
        $text_kh = '';
        $daysBefore = 0;
        $daysAfter = 0;
        $data = [];
        foreach ($periods as $period) {
            if ($period->format('Y-m-t') < $end_date && $period->format('Y-m-01') > $start_date) {
                $monthsArray[$period->format('Y-m-t')] = $period->format('t');
            }
        }
        $first = min(array_keys($monthsArray));
        $last = max(array_keys($monthsArray));
        $first = date('Y-m-01', strtotime($first));
        $before = new DateTime($first, $timezone);
        $after = new DateTime($last, $timezone);
        $after->modify('+1 day');
        $m = count($monthsArray);
        $daysBefore = $begin->diff($before)->days;
        $daysAfter = $after->diff($end)->days;
        $d = $daysBefore + $daysAfter;
        $days = $d + array_sum($monthsArray);
        $w = (int)round($days / 7);
        foreach ($monthsArray as $key => $day) {
            $data[strtotime($key)] = date('F', strtotime($key)) . ': ' . ($day > 1 ? $day . ' days' : $day . ' day');
        }
        array_unshift($data, date('F', strtotime($start_date)) . ': ' . $daysBefore . ' days');
        array_push($data, date('F', strtotime($end_date)) . ': ' . $daysAfter . ' days');
        if ($m > 0) {
            $text .= $m . ($m > 1 ? ' Months ' : ' Month ');
            $text_kh .= $m . ' ខែ ';
        }
        if ($d > 0) {
            $text .= $d . ($d > 1 ? ' Days ' : ' Day ');
            $text_kh .= $d . ' ថ្ងៃ ';
        }
        return [
            'm' => $m,
            'd' => $d,
            'w' => $w,
            'days' => $days,
            'text' => trim($text),
            'text_kh' => trim($text_kh),
            'begin' => $start_date,
            'end' => $end_date,
            'timezone' => $timezone,
            'data' => $data,
        ];
    }
}
if (!function_exists('clipboard')) {
    function clipboard($instance)
    {
        return '<i class="ph ph-copy me-3 text-primary cursor-pointer" clipboard-text="' . $instance . '" onclick="copyToClipboard(event)"></i>';
    }
}


if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        if ($date) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return date('d-m-Y', $timestamp);
            }
        }
        return null;
    }
}

if (!function_exists('classActivePath')) {
    function classActivePath($paths)
    {
        $paths = is_array($paths) ? $paths : [$paths];

        foreach ($paths as $p) {
            if (Request::routeIs($p) || Request::is($p) || Request::is($p . '/*')) {
                return 'active';
            }
        }

        return '';
    }
}


if (! function_exists('number_format_short')) {
    function number_format_short( $n, $precision = 1 ) 
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }

        return $n_format . $suffix;
    }
}


if (!function_exists('post_views_count')) {
    function post_views_count($post_id,$post_type)
    {
        $view_count = PostViews::where('post_id', '=', $post_id)->where('post_type', '=', $post_type)->sum('post_views');

        return $view_count;
    }
}

if (! function_exists('getcong')) {
    function getcong($key)
    { 
       if(file_exists(base_path('/public/.lic')))
       { 
            $settings = Settings::findOrFail('1');              
            return $settings->$key;
       }
    }
}

if (! function_exists('getCurrencySymbols')) {
    function getCurrencySymbols($code)
    { 
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;',
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;',
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;',
            'BIF' => '&#70;&#66;&#117;',
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;',
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '',
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;',
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;',
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;',
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;',
            'GNF' => '&#70;&#71;',
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;',
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;',
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;',
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;',
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;',
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;',
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;',
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;',
            'MAD' => '&#1583;.&#1605;.',
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;',
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;',
            'MRO' => '&#85;&#77;',
            'MUR' => '&#8360;',
            'MVR' => '.&#1923;',
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;',
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;',
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;',
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;',
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;',
            'ZWL' => '&#90;&#36;',
        );

        // ✅ Fix: check if key exists and handle empty code
        if (empty($code) || !array_key_exists($code, $currency_symbols)) {
            return ''; // or return a default symbol like '&#36;'
        }

        return $currency_symbols[$code];
    }
}

if (! function_exists('get_web_banner')) {

    function get_web_banner($key)
    {
         
        $settings = WebAds::findOrFail('1');
        return $settings->$key;
    }
}


if (!function_exists('check_favourite')) {
    function check_favourite($post_type,$post_id,$user_id=null)
    {       
        if($user_id)
        {
             $fav_obj = Favourite::where('post_type', '=', $post_type)->where('post_id', '=', $post_id)->where('user_id', '=', $user_id)->first();

             if($fav_obj)
             {
                return true;
             }
             else
             {
                return false;
             }
        }
        else
        {
            return false;
        }
          
    }
}


if (!function_exists('post_views_count')) {
    function post_views_count($post_id,$post_type)
    {
        $view_count = PostViews::where('post_id', '=', $post_id)->where('post_type', '=', $post_type)->sum('post_views');

        return $view_count;
    }
}

if (!function_exists('jsonResponse')) {
	function jsonResponse($response)
	{
		return response()->json($response, 200);
	}
}