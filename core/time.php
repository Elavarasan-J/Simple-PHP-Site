<?php
/**
 * Time/Date related
 */
class Time {
	function __construct() {
		defined('TIMESTAMP') OR define("TIMESTAMP",time());
		$this->date_Y=date("Y");
		$this->date_m=date("m");
		$this->date_d=date("d");
		$this->date_mdY=date("m-d-Y");
		$this->date_Ymd=$this->sqlDate=date("Y-m-d");
		$this->sqlDateTime=date("Y-m-d H:i:s");

		//	America/New_York | Asia/Kuala_Lumpur | Asia/Calcutta
		date_default_timezone_set('Asia/Calcutta');
	}
	function split_time($time) {
		if($time!='') {
			$arr1=explode(' ',$time);
			$date_arr=explode('-',$arr1[0]);
			$time_arr=explode(':',$arr1[1]);
			return array_merge($date_arr,$time_arr);
		} else {
			return false;
		}
	}
	function merge_time($arr) {
		$time='';
		if(is_array($arr) && count($arr)>0) {
			$time=$arr[0].'-'.$arr[1].'-'.$arr[2].' '.$arr[3].':'.$arr[4].':'.$arr[5];
		}
		return $time;
	}
	function switch_date_format($date,$divider='-') {
		if($date!='') {
			$date_arr=explode($divider,$date);
			return $date_arr[2].$divider.$date_arr[1].$divider.$date_arr[0];
		} else {
			return false;
		}
	}
	function switch_datetime_format($datetime,$divider='-') {
		if($datetime!='') {
			$datetime_arr=explode(' ',$datetime);
			return $this->switch_date_format($datetime_arr[0],$divider).' '.$datetime_arr[1];
		} else {
			return false;
		}
	}
}
$timeObj = new Time;
?>