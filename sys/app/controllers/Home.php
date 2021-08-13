<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index(){
		if(!$this->newsession->userdata('logged_in')){
			$this->load->helper('captcha');
			$captcha = array('img_path' => '././img/captcha/',
							 'img_url' => base_url().'img/captcha/',
							 'font_path' => '././font/segoeuib.ttf',
							 'img_width' => 90,
							 'img_height' => 30,
							 'expiration' => 10);
			$data = create_captcha($captcha);
			$this->newsession->set_userdata(array("key-log-".$data['time'] => $data['word']));
			$this->load->view("home/out", $data);
		}else{
			$content = $this->load->view("welcome", $data, true);
			$data = array("content" => $content, "menu" => "home");
			$this->load->view("home/in", $data);
		}
	}
	
	/* public function reset(){
		if($this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			if($_SERVER['REQUEST_METHOD']=='POST'){
				redirect(base_url()); die();
				$user = $this->input->post('user');
				$mail = $this->input->post('mail');
				$code = str_replace(' ', '', $this->input->post('code'));
				$salt = $this->input->post('salt');
				$security = $this->newsession->userdata("key-log-$salt");
				echo "<script>";
				if($security!=$code){
					echo "alert('Proses gagal, Autentikasi Keamanan tidak valid');\n";
					echo "location.href='".site_url("home/reset")."';\n";
				}else{
					$query = "SELECT * FROM t_user WHERE login = '$user'";
					if($this->main_act->get_result($query)){
						$row = $query->row_array();
						if($row['email']!=$mail){
							echo "alert('Proses gagal, alamat Email yang Anda masukkan salah');\n";
							echo "location.href='".site_url("home/reset")."';\n";
						}elseif($row['status']!='US01'){
							echo "alert('Proses gagal, User Anda tidak aktif');\n";
							echo "location.href='".site_url("home/reset")."';\n";
						}else{
							$enckey = md5($user.'Q=z+@'.$code);
							$this->db->simple_query("UPDATE t_user SET password = '$enckey' WHERE id = $row[id]");
							echo "alert('Proses berhasil, silahkan Login dengan menggunakan User ID $user dan Password $code');\n";
							echo "location.href='".base_url()."';\n";
						}
					}else{
						echo "alert('Proses gagal, User ID yang Anda masukkan belum terdaftar');\n";
						echo "location.href='".site_url("home/reset")."';\n";
					}
				}
				echo "</script>";
				die();
			}else{
				$this->load->helper('captcha');
				$captcha = array('img_path' => '././img/captcha/',
								 'img_url' => base_url().'img/captcha/',
								 'font_path' => '././font/segoeuib.ttf',
								 'img_width' => 90,
								 'img_height' => 30,
								 'expiration' => 10);
				$data = create_captcha($captcha);
				$this->newsession->set_userdata(array("key-log-".$data['time'] => $data['word']));
				$data["menu"] = "reset";
				$this->load->view("home/out", $data);
			}
		}
	} */
	
	/* public function password(){
		if(!$this->newsession->userdata('logged_in')){
			redirect(base_url());
		}else{
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$key = $this->input->post('key');
				$key1 = $this->input->post('key1');
				$key2 = $this->input->post('key2');
				echo "<script>";
				if($this->newsession->userdata('password')!=$key){
					echo "alert('Proses gagal, Password Lama yang Anda masukkan salah');\n";
					echo "location.href='".site_url("home/password")."';\n";
				}elseif($key1!=$key2){
					echo "alert('Proses gagal, Password Baru yang Anda masukkan tidak sesuai dengan Konfirmasi Password Baru');\n";
					echo "location.href='".site_url("home/password")."';\n";
				}else{
					$this->db->simple_query("UPDATE user_tm SET password = '$key1' WHERE user_id = ".$this->newsession->userdata('user_id'));
					echo "alert('Proses berhasil, silahkan Login kembali dengan menggunakan Password Baru Anda');\n";
					echo "location.href='".site_url("home/logout")."';\n";
				}
				echo "</script>";
				die();
			}else{
				$content = $this->load->view("password", $data, true);
				$data = array("content" => $content, "menu" => "password");
				$this->load->view("home/in", $data);
			}
		}
	} */
	
	public function verify(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$user = $this->input->post('login');
			$password = $this->input->post('password');
			// $code = str_replace(' ', '', $this->input->post('code'));
			// $salt = $this->input->post('salt');
			// $security = $this->newsession->userdata("key-log-$salt");
			echo "<script>";
			// if($security!=$code){
				// echo "alert('Proses gagal, Autentikasi Keamanan tidak valid');\n";
			// }else{
				$query = "SELECT A.`user_id`, A.`login`, A.`password`, A.`name`, A.`title`, A.`email`, A.`phone`, A.`status_id`, A.`login_date`, GROUP_CONCAT(B.`role_id`) role_id FROM user_tm A LEFT JOIN user_role_tm B ON A.user_id = B.`user_id` WHERE A.login = '$user' GROUP BY A.`user_id`";
				if($this->main_act->get_result($query)){
					$row = $query->row_array();
					// print_r($row); die();
					$enckey = md5($password);
					if($row['password']!=$enckey){
						echo "alert('Proses gagal, Password yang Anda masukkan salah');\n";
					}elseif($row['status_id']!='ST01'){
						echo "alert('Proses gagal, User Anda tidak aktif');\n";
					}else{
						$row["role_id"] = explode(",", $row["role_id"]);
						$this->db->simple_query("UPDATE user_tm SET login_date = NOW() WHERE user_id = $row[user_id]");
						$row['logged_in'] = TRUE;
						$this->newsession->set_userdata($row);
					}
				}else{
					echo "alert('Proses gagal, User ID yang Anda masukkan belum terdaftar');\n";
				}
			// }
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