<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class TrackInfo {
	function __construct() {
		$this->db = new DB;
		$this->timeObj = new Time;
		$this->adminUtilityObj = new AdminUtility;

		$this->trackTable=$this->db->TB_track;
	}

	/**
	 * Get all types of track array
	 *
	 * @param [type] $key	// ex: created|modified|trashed|restored|deleted
	 * @return array
	 */
	function getTrackArr($key) {
		global $sqlDateTime;
		return array(
			$key.'_ip' => IP,
			$key.'_by' => $this->adminUtilityObj->get_info('user_name'),
			$key.'_date' => $this->timeObj->sqlDateTime
		);
	}
	function saveTrack($id, $mainTable) {
		$track_where="primary_key_id=$id AND table_name='$mainTable'";
		$trackid=$this->db->getSingleRec($this->trackTable,$track_where,'track_id');
		if(isset($trackid['track_id']) && $trackid['track_id']>0) {
			$track=$this->getTrackArr('modified');
			$this->db->update($track,$track_where,$this->trackTable);
		} else {
			$track['table_name']=$mainTable;
			$track['primary_key_id']=$id;
			$track=array_merge($track,$this->getTrackArr('created'));
			$this->db->insert($track,$this->trackTable);
		}
	}
	function trashTrack($id, $mainTable) {
		$track_where="primary_key_id=$id AND table_name='$mainTable'";
		$trackid=$this->db->getSingleRec($this->trackTable,$track_where,'track_id');
		if(isset($trackid['track_id']) && $trackid['track_id']>0) {
			$track=$this->getTrackArr('trashed');
			$this->db->update($track,$track_where,$this->trackTable);
		}
	}
	function restoreTrack($id, $mainTable) {
		$track_where="primary_key_id=$id AND table_name='$mainTable'";
		$trackid=$this->db->getSingleRec($this->trackTable,$track_where,'track_id');
		if(isset($trackid['track_id']) && $trackid['track_id']>0) {
			$track=$this->getTrackArr('restored');
			$this->db->update($track,$track_where,$this->trackTable);
		}
	}
	function deleteTrack($id, $mainTable) {
		$track_where="primary_key_id=$id AND table_name='$mainTable'";
		$trackid=$this->db->getSingleRec($this->trackTable,$track_where,'track_id');
		if(isset($trackid['track_id']) && $trackid['track_id']>0) {
			$track=$this->getTrackArr('deleted');
			$this->db->update($track,$track_where,$this->trackTable);
		}
	}
	function trackInfo($tab,$where,$printsql=0){
		$modified='';
		$sql='select * from '.$tab.' where '.$where.' ';
		if($printsql) echo $sql;
		$rec=$this->db->query($sql);
		$res=$this->db->getrec();
		if(count($res)){
			extract($res[0]);
			if($modified_ip!='')
				$modified='<li>Last updated by '.$modified_by.' on <strong>'.$modified_date.'</strong> from <strong>'.$modified_ip.'</strong></li>';
			return '<p><strong>Activity:</strong></p>
				<ul>
					<li>Record Created by '.$created_by.' on <strong>'.$created_date.'</strong> from <strong>'.$created_ip.'</strong></li>
					'.$modified.'
				</ul>';
			
		} else return '';
	}
}
$trackInfoObj = new TrackInfo;
?>