<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function index(){
        if($this->newsession->userdata('status_id')!='ST01'){
			redirect(base_url());
		}else{
            $query = "SELECT * FROM user_tm A where A.user_id = ".$this->newsession->userdata('user_id');
			$result = $this->main_act->get_result($query);
			if($result){
				$data["data"] = $query->row_array();
			}else{
				redirect(site_url("it/apps"));
				die();
			}
			$content = $this->load->view("profile", $data, true);
			$data = array("content" => $content);
			$this->load->view("home/in", $data);
		}
	}

	public function posting(){
    if($this->newsession->userdata('status_id')!='ST01'){
        redirect(base_url());
    }else{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			// print_r($_POST); die();
            $data['caption'] = $this->input->post('caption');
            $data['user_id'] = $this->newsession->userdata('user_id');
            
            $dir = "/home/apps/temp/file/";
            $path = date('Y');
            if(!is_dir($dir.$path)) mkdir($dir.$path);
            $path .= "/".date('m');
            if(!is_dir($dir.$path)) mkdir($dir.$path);
            $path .= "/".date('d');
            if(!is_dir($dir.$path)) mkdir($dir.$path);
            $file = $_FILES["file-upload"];
            $file_type = strtolower(array_pop(explode(".", $file["name"])));
            $file_name = date('His').rand(100, 999).".$file_type";
            $config['allowed_types'] = '*';
            $config['upload_path'] = $dir.$path;
            $config['file_name'] = $file_name;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("file-upload")){
            }else{
                $ret = array('upload_data' => $this->upload->data());
                if(!is_readable($ret['upload_data']['full_path'])){
                }else{
                    $data["upload_file"] = "$path/$file_name";
                }
            }

            // print_r($data['file_pdf']);
            // die('-');
            echo "<script>";
            $this->db->trans_begin();
            if($id!=""){
                $this->db->where("post_id", $id);
                $this->db->update("post_tm", $data);
            }else{
                $data['post_id'] = (double)$this->main_act->get_uraian("SELECT MAX(post_id) post_id FROM post_tm", "post_id");
                $data['post_id']++;
                $post_id = $data['post_id'];
                $this->db->insert("post_tm", $data);
            }
            // echo $this->db->last_query(); die();
            if($this->db->trans_status()===FALSE){
                $this->db->trans_rollback();
                echo "alert('Proses gagal, silahkan hubungi Administrator');\n";
            }else{
                $this->db->trans_commit();
                echo "alert('Proses berhasil');\n";
            }
            echo "location.href='".site_url("profile")."';\n";
            echo "</script>";
            die();
        }else{
            $content = $this->load->view("apps/sign", $data, true);
            $data = array("content" => $content, "menu" => "reference");
            $this->load->view("home/in", $data);
        }
	}
}