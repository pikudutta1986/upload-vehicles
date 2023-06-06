<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUtil extends pjToolkit
{
	static public function uuid()
	{
		return chr(rand(65,90)) . chr(rand(65,90)) . time();
	}
	
	static public function html2txt($document)
	{
		$search = array('@<script[^>]*?>.*?</script>@si',
				'@<[\/\!]*?[^<>]*?>@si',
				'@<style[^>]*?>.*?</style>@siU',
				'@<![\s\S]*?--[ \t\n\r]*>@'
		);
		$text = preg_replace($search, '', $document);
		return $text;
	}
	static public function truncateDescription($string, $limit, $break=".", $pad="...")
	{
		if(strlen($string) <= $limit)
			return $string;
		if(false !== ($breakpoint = strpos($string, $break, $limit)))
		{
			if($breakpoint < strlen($string) - 1)
			{
				$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
		return $string;
	}
	
	static public function showMileage($opt, $km)
	{
		switch (strtolower($opt))
		{
			case 'miles':
				$miles = $km;
				return sprintf("%.0f miles", $miles);
				break;
			case 'km':
			default:
				return sprintf("%.0f km", $km);
				break;
		}
	}
	
	static public function showPower($opt, $power)
	{
		switch (strtolower($opt))
		{
			case 'hp':
				return sprintf("%.0f HP", $power);
				break;
			default:
				$kw = $power;
				return sprintf("%.0f kW", $kw);
				break;
		}
	}
	
	static public function getSortingUrl($url, $seo)
	{
		$sorting_url = $url;
		if($seo == 'No')
		{
			$sorting_url = str_replace('&sortby=listing_price', '', $sorting_url);
			$sorting_url = str_replace('&sortby=listing_mileage', '', $sorting_url);
			$sorting_url = str_replace('&sortby=listing_year', '', $sorting_url);
			$sorting_url = str_replace('&direction=asc', '', $sorting_url);
			$sorting_url = str_replace('&direction=desc', '', $sorting_url);
		}else{
			$sorting_url = str_replace('?sortby=listing_price', '', $sorting_url);
			$sorting_url = str_replace('?sortby=listing_mileage', '', $sorting_url);
			$sorting_url = str_replace('?sortby=listing_year', '', $sorting_url);
			$sorting_url = str_replace('?direction=asc', '', $sorting_url);
			$sorting_url = str_replace('?direction=desc', '', $sorting_url);
			$sorting_url = str_replace('&direction=asc', '', $sorting_url);
			$sorting_url = str_replace('&direction=desc', '', $sorting_url);
		}
		return $sorting_url;
	}
	
	static public function currPageURL($get) 
	{
 		$pageURL = 'http';
 		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") 
 		{
 			$pageURL .= "s";
 		}
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") 
 		{
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 		}
 		$params = parse_url($pageURL); 
 		
 		return $pageURL;
	}
	
	static public function getWeekRange($date, $week_start)
	{
		$week_arr = array(
				0=>'sunday',
				1=>'monday',
				2=>'tuesday',
				3=>'wednesday',
				4=>'thursday',
				5=>'friday',
				6=>'saturday'
		);
			
		$ts = strtotime($date);
		$start = (date('w', $ts) == 0) ? $ts : strtotime('last ' . $week_arr[$week_start], $ts);
		$week_start = ($week_start == 0 ? 6 : $week_start -1);
		return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next ' . $week_arr[$week_start], $start)));
	}
	
	static public function getMadeWhere($period, $week_start)
	{
		$where_str = '';
		switch ($period) {
			case 1:
				$where_str = "(DATE(t1.created) = CURDATE() OR DATE(t1.modified) = CURDATE())";
				break;
				;
			case 2:
				$where_str = "(DATE(t1.created) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) OR DATE(t1.modified) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)))";
				break;
				;
			case 3:
				list($start_week, $end_week) = pjUtil::getWeekRange(date('Y-m-d'), $week_start);
				$where_str = "((DATE(t1.created) BETWEEN '$start_week' AND '$end_week') OR (DATE(t1.modified) BETWEEN '$start_week' AND '$end_week'))";
				break;
				;
			case 4:
				list($start_week, $end_week) = pjUtil::getWeekRange(date('Y-m-d', strtotime("-7 days")), $week_start);
				$where_str = "((DATE(t1.created) BETWEEN '$start_week' AND '$end_week') OR (DATE(t1.modified) BETWEEN '$start_week' AND '$end_week'))";
				break;
				;
			case 5:
				$start_month = date('Y-m-01',strtotime('this month'));
				$end_month = date('Y-m-t',strtotime('this month'));
				$where_str = "((DATE(t1.created) BETWEEN '$start_month' AND '$end_month') OR (DATE(t1.modified) BETWEEN '$start_month' AND '$end_month'))";
				break;
				;
			case 6:
				$start_month = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y")));
				$end_month = date("Y-m-d", mktime(0, 0, 0, date("m"), 0, date("Y")));
				$where_str = "((DATE(t1.created) BETWEEN '$start_month' AND '$end_month') OR (DATE(t1.modified) BETWEEN '$start_month' AND '$end_month'))";
				break;
				;
		}
		return $where_str;
	}
	
	public static function getField($key, $return=false, $escape=false)
	{
		if (pjObject::getPlugin('pjWebsiteContent') !== NULL)
		{
			return pjWebsiteContentUtil::getField($key, $return, $escape);
		} else {
			return pjToolkit::getField($key, $return, $escape);
		}
	
	}
}
?>