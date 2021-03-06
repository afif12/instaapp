<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function index(){
        if($this->newsession->userdata('status_id')!='ST01'){
			redirect(base_url());
		}else{
            $query1 = "SELECT *, DATE_FORMAT(A.`date_created`, '%d %M %Y') `date`, (SELECT COUNT(sequence) sequence FROM post_tl WHERE post_id = A.`post_id`) jumlah FROM post_tm A LEFT JOIN user_tm B ON B.`user_id` = A.`user_id` WHERE A.user_id = ".$this->newsession->userdata('user_id');
			$res = $this->main_act->get_result($query1);
            if($res){
                $data["post"] = $query1;
            }
            $query = "SELECT * FROM user_tm A where A.user_id = ".$this->newsession->userdata('user_id');
			$result = $this->main_act->get_result($query);
			if($result){
				$data["data"] = $query->row_array();
			}else{
				redirect(site_url("profile"));
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
                // print_r($_FILES); die();
                $data['caption'] = $this->input->post('caption');
                $data['user_id'] = $this->newsession->userdata('user_id');
                
                $dir ='./upload/';
                $path = date('Y');
                if(!is_dir($dir.$path)) mkdir($dir.$path);
                $path .= "/".date('m');
                if(!is_dir($dir.$path)) mkdir($dir.$path);
                $path .= "/".date('d');
                if(!is_dir($dir.$path)) mkdir($dir.$path);
                $file = $_FILES["file-upload"];
                $file_type = strtolower(array_pop(explode(".", $file["name"])));
                $file_name = date('His').rand(100, 999).".$file_type";
                $config['allowed_types'] = 'gif|jpg|png';
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
                $content = $this->load->view("posting", $data, true);
                $data = array("content" => $content, "menu" => "reference");
                $this->load->view("home/in", $data);
            }
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
                echo "location.href='".site_url("profile/preview/".$id)."';\n";
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
}