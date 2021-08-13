<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function index(){
        if($this->newsession->userdata('status_id')!='ST01'){
			redirect(base_url());
		}else{
			$content = $this->load->view("profile", $data, true);
			$data = array("content" => $content);
			$this->load->view("home/in", $data);
		}
	}

	public function account(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$data['phone'] = $this->input->post('phone');
			$data['email'] = $this->input->post('email');
			$data['name'] = $this->input->post('name');
			$data['login'] = $this->input->post('login');
			$data['password'] = $this->input->post('password');
			// print_r($data);die('-');
			echo "<script>";
			$this->db->trans_begin();
			$data['user_id'] = (double)$this->main_act->get_uraian("SELECT MAX(user_id) user_id FROM user_tm", "user_id");
			$data['user_id']++;
			$this->db->insert("user_tm", $data);
			// echo $this->db->last_query(); die('-');
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo "alert('Proses Gagal, Silahkan Periksa Kembali Isian Anda');\n";
				echo "history.back();\n";
				die();
			} else {
				$this->db->trans_commit();
                echo "alert('Proses berhasil');\n";
			}
            echo "location.href='".site_url("home")."';\n";
            echo "</script>";
		}else{
			$this->index();
		}
	}
}