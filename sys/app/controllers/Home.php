<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
	public function index(){
		if(!$this->newsession->userdata('logged_in')){
			$this->load->view("home/out");
		}else{
			$query = "SELECT *, DATE_FORMAT(A.`date_created`, '%d %M %Y') `date`, (SELECT COUNT(sequence) sequence FROM post_tl WHERE post_id = A.`post_id`) jumlah FROM post_tm A LEFT JOIN user_tm B ON B.`user_id` = A.`user_id`";
			$res = $this->main_act->get_result($query);
            $data["post"] = $query;
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

    public function preview($id = ""){
        if($this->newsession->userdata('status_id')!='ST01'){
            redirect(base_url());
        }else{
            if($_SERVER['REQUEST_METHOD']=='POST'){
                // print_r($_FILES); die();
                $data['comment'] = $this->input->post('comment');
                $data['user_id'] = $this->newsession->userdata('user_id');
                echo "<script>";
                $this->db->trans_begin();
                $data['post_id'] = $id;
                $data['sequence'] = (double)$this->main_act->get_uraian("SELECT MAX(sequence) sequence FROM post_tl WHERE post_id = $id ", "sequence");
                $data['sequence']++;
                $this->db->insert("post_tl", $data);
                // echo $this->db->last_query(); die();
                if($this->db->trans_status()===FALSE){
                    $this->db->trans_rollback();
                    echo "alert('Proses gagal, silahkan hubungi Administrator');\n";
                }else{
                    $this->db->trans_commit();
                    echo "alert('Proses berhasil');\n";
                }
                echo "location.href='".site_url("home/preview/".$id)."';\n";
                echo "</script>";
                die();
            }else{
                $query = "SELECT *, DATE_FORMAT(A.`date_created`, '%d %M %Y') `date` FROM post_tm A LEFT JOIN user_tm B ON B.`user_id` = A.`user_id` WHERE A.post_id = $id";
                $res = $this->main_act->get_result($query);
                $data['data'] = $query->row_array();
                
                $query1 = "SELECT *, DATE_FORMAT(A.`date_created`, '%d %M %Y') `date` FROM post_tl A LEFT JOIN user_tm B ON B.`user_id` = A.`user_id` WHERE A.post_id = $id";
                $res = $this->main_act->get_result($query1);
                if($res){
                    $data['comment'] = $query1;
                }
                $data['post_id'] = $data['data']['post_id'];
                $content = $this->load->view("preview/profile", $data, true);
                $data = array("content" => $content, "menu" => "reference");
                $this->load->view("home/in", $data);
            }
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