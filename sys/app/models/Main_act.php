<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_act extends CI_Model{
	function get_result_array($query, $key, $value) {
		$data = $this->get_result($query);
		$result = "";
		if($data){
			foreach ($query->result_array() as $row) {
				$result[$row[$key]] = $row[$value];
			}
		}
		return $result;
	}

	public function get_uraian($query, $select, $db=""){
		if($db=="") $db = $this->db;
		$db->reconnect();
		$data = $db->query($query);
		if($data->num_rows() > 0){
			$row = $data->row();
			return $row->$select;
		}else{
			return "";
		}
		return 1;
	}
	
	public function get_result(&$query, $db=""){
		if($db=="") $db = $this->db;
		$db->reconnect();
		$data = $db->query($query);
		if($data){
			if($data->num_rows() > 0){
				$query = $data;
			}else{
				return false;
			}
		}else{
			return false;
		}
		return true;
	}
	
	public function get_combobox($query, $key, $value, $empty = FALSE, &$disable = "", $db=""){
		if($db=="") $db = $this->db;
		$db->reconnect();
		$combobox = array();
		$data = $db->query($query);
		if($empty) $combobox[""] = "&nbsp;";
		if($data->num_rows() > 0){
			$kodedis = "";
			$arrdis = array();
			foreach($data->result_array() as $row){
				if(is_array($disable)){
					if($kodedis==$row[$disable[0]]){
						if(!array_key_exists($row[$key], $combobox)) $combobox[$row[$key]] = str_replace("'", "\'", "&nbsp; &nbsp;&nbsp;".$row[$value]);
					}else{
						if(!array_key_exists($row[$disable[0]], $combobox)) $combobox[$row[$disable[0]]] = $row[$disable[1]];
						if(!array_key_exists($row[$key], $combobox)) $combobox[$row[$key]] = str_replace("'", "\'", "&nbsp; &nbsp;&nbsp;".$row[$value]);
					}
					$kodedis = $row[$disable[0]];
					if(!in_array($kodedis, $arrdis)) $arrdis[] = $kodedis;
				}else{
					$combobox[$row[$key]] = str_replace("'", "\'", $row[$value]);
				}
			}
			$disable = $arrdis;
		}
		return $combobox;
	}
	
	public function post_to_query($array, $except=""){
		$data = array();
		foreach($array as $a => $b){
			if(is_array($except)){
				if(!in_array($a, $except)) $data[$a] = $b;
			}else{
				$data[$a] = $b;
			}
		}
		return $data;
	}
	
	public function clean_sql($data){
		$data = str_replace("'", "''", $data);
		
		return $data;
	}
	
	public function send_mail($subject, $body, $trader_id){
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from("sireka@pom.go.id", "Sistem Registrasi Iklan BPOM");
		// $this->email->to("zona@edi-indonesia.co.id");
		$to = $this->get_uraian("SELECT email FROM t_user WHERE trader_id = $trader_id LIMIT 1", "email");
		if($to=="") return false;
		$to = str_replace("  ", " ", str_replace(";", ", ", $to));
		$this->email->to($to);
		$this->email->reply_to('sireka@pom.go.id', 'Badan POM');
		$this->email->bcc("sireka@pom.go.id, uqo_86@yahoo.com");
		$this->email->subject($subject);
		$this->email->message($body);
		$hasil = $this->email->send();
		// print_r($hasil); die();
		return $hasil;
	}
	
	public function send_json($nip, $passphrase, $x, $y, $width, $height, $source, $destination){
		$x = 22;
		$y = 22;
		$width = 520;
		$height = 520;
		$curl = curl_init();
		$passphrase = rawurlencode($passphrase);
		curl_setopt_array($curl, 
						  array(// CURLOPT_PORT => "8080", 
								// CURLOPT_URL => "http://172.16.1.242/api/sign/pdf?nik=$nip&passphrase=$passphrase&tampilan=invisible", 
								CURLOPT_URL => "http://172.16.1.152/api/sign/pdf?nik=$nip&passphrase=$passphrase&tampilan=visible&page=1&image=true&xAxis=$x&yAxis=$y&width=$width&height=$height&reason=Dokumen%20TTE&location=BPOM", 
								CURLOPT_RETURNTRANSFER => true, 
								CURLOPT_ENCODING => "", 
								CURLOPT_MAXREDIRS => 10, 
								CURLOPT_TIMEOUT => 30, 
								CURLOPT_SSL_VERIFYPEER => false, 
								CURLOPT_SSL_VERIFYHOST => false, 
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
								CURLOPT_CUSTOMREQUEST => "POST", 
								// CURLOPT_HTTPHEADER => array("Authorization:Basic ".base64_encode("admin:qwerty"), "cache-control:no-cache", ),
								// CURLOPT_HTTPHEADER => array("Authorization:Basic ".base64_encode("admin:qwerty"), "cache-control:no-cache", ),
								CURLOPT_HTTPHEADER => array("Authorization:Basic ".base64_encode("esign:qwerty"), "cache-control:no-cache", ),
								));
		$postfields["file"] = new CURLFile($source, mime_content_type($source), pathinfo($source)['basename']);
		$png = "/home/apps/img/ttd.png";
		$postfields["imageTTD"] = new CURLFile($png, mime_content_type($png), pathinfo($png)['basename']);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		// print_r($err); print_r($response); die("y");
		if($err){
			return $err;
		}else{
			if(substr($response, 0, 6)=='%PDF-1'){
				if(file_put_contents($destination, $response)) return "pdf";
				else return "save-error";
			}else{
				return $response;
			}
		}
	}
	
	public function set_user_log($user_id, $seq, $process_id, $action, $group, $data, $ip, $error, $sign_id = ""){
		$data = str_replace("'", "''", $data);
		$input = array("user_id" => $user_id, 
					   "seq" => $seq, 
					   "process_id" => $process_id, 
					   "action" => $action, 
					   "group" => $group, 
					   "data" => $data, 
					   "ip" => $ip, 
					   "error" => $error, );
		if($sign_id!="") $input["sign_id"] = $sign_id;
		$this->db->trans_begin();
		$this->db->insert("user_tl", $input);
		if($this->db->trans_status()===FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	public function get_ip($ip=""){
		if(isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'Unknown';
		if($ip!="") $ipaddress = $ip." / ".$ipaddress;
		return $ipaddress;
	}
	
	public function get_process_id(){
		$mt = microtime(true);
		$micro = sprintf("%03d",($mt - floor($mt)) * 1000);
		$id = date("ymdHis").$micro;
		if($this->db->simple_query("INSERT INTO process_tx (process_id) VALUES ($id)"))
			return $id;
		else
			return $this->get_process_id();
	}
	
	public function numerik($num){
		$num = (double)$num;
		if(intval($num) == $num){
			$nums = number_format($num,0,',','.');
		}else{
			$nums = number_format($num,4,',','.');
			$nums = rtrim($nums,0);
		}
		return $nums;
	}
}