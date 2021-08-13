<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autofill extends CI_Controller {

	function Autofill(){
		parent::__construct();
	}

	public function index()
	{
		redirect(base_url());
	}

	function department(){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			$key = str_replace("'", "''", strtolower($_REQUEST['q']));
			$query = "SELECT department_id, GetDepartment(department_id, 2021) unit FROM department_tm WHERE version = 2021 AND GetDepartment(department_id, 2021) LIKE '%$key%'";
			if($this->main_act->get_result($query)){
				foreach($query->result_array() as $row){
					echo $row['unit']."|".$row['unit']."|".$row['department_id']."\n";
				}
			}
		}
	}

	function cbdepartment($code){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			$len = strlen($code);
			$query = "SELECT department_id, name, code_3 FROM department_tma WHERE code_3 LIKE '$code%' ORDER BY code_3";
			if($this->main_act->get_result($query)){
				foreach($query->result_array() as $row){
					$long = strlen($row['code_3']);
					$ret = str_repeat("&bull;", ($long-$len));
					$ret .= " ".$row['name'];
					$arr[$row['department_id']] = trim($ret);
					// print_r($row); echo "<hr>";
				}
			}
			// print_r($arr);
		}
	}
	
	function alldepartment(){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			$key = str_replace("'", "''", strtolower($_REQUEST['q']));
			$query = "SELECT department_id, GetDepartment(id, version) unit FROM department_tm WHERE GetDepartment(department_id, version) LIKE '%$key%'";
			if($this->main_act->get_result($query)){
				foreach($query->result_array() as $row){
					echo $row['unit']."|".$row['unit']."|".$row['department_id']."\n";
				}
			}
		}
	}
	
	function subdepartment($id){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			$key = str_replace("'", "''", strtolower($_REQUEST['q']));
			$sub = $this->main_act->get_uraian("SELECT code_3 FROM department_tm WHERE version = 2018 AND department_id = $id", "code_3");
			$query = "SELECT department_id, GetDepartment(department_id, 2018) unit FROM department_tm WHERE version = 2018 AND code_3 LIKE '$sub%' AND GetDepartment(department_id, 2018) LIKE '%$key%'";
			if($this->main_act->get_result($query)){
				foreach($query->result_array() as $row){
					echo $row['unit']."|".$row['unit']."|".$row['department_id']."\n";
				}
			}
		}
	}
}