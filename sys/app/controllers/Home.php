<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
	public function index(){
		if(!$this->newsession->userdata('logged_in')){
			$this->load->view("home/out");
		}else{
			$data['content'] = $this->load->view("welcome", $data, true);
			$this->load->view("home/in", $data);
		}
	}

	public function verify(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$user = $this->input->post('user');
			$key = $this->input->post('key');
			echo "<script>";
			$query = "SELECT A.* FROM user_tm A WHERE A.login = '$user' AND status_id = 'ST01'";
			if($this->main_act->get_result($query)){
				$row = $query->row_array();
				if($row['password']!=$key){
					echo "alert('Proses gagal, Password yang Anda masukkan salah');\n";
				}elseif($row['status_id']!='ST01'){
					echo "alert('Proses gagal, User Anda tidak aktif');\n";
				}else{
					$row['logged_in'] = TRUE;
					$this->newsession->set_userdata($row);
				}
			}else{
				echo "alert('Proses gagal, User ID yang Anda masukkan belum terdaftar');\n";
			}
			echo "location.href='".base_url()."';\n";
			echo "</script>";
			die();
		}else{
			$this->index();
		}
	}
	
	public function logout(){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
			die();
		}
		$this->newsession->sess_destroy();
		redirect(base_url());
	}
}