<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download_act extends CI_Model{
	public function get($user_id, $password, $ip, $input){
		// 01 - Verifikasi User ID dan Password
		$process_id = $this->main_act->get_process_id();
		$error = "";
		$seq = 0;
		$query = "SELECT A.*, B.name `application` FROM user_tm A LEFT JOIN application_tm B ON B.application_id = A.application_id WHERE A.login = '$user_id'";
		if($this->main_act->get_result($query)){
			$data = $query->row_array();
			$user_id = $data["user_id"];
			$application_id = $data["application_id"];
			$application = $data["application"];
			if($password!=hash("sha256", $data["password"].date("Ymd"))){
				$error = "Password salah - ($password / ".hash("sha256", $data["password"].date("Ymd")).")";
				$message = array("result" => "ERROR", "code" => "01", "message" => "User ID atau Password salah", "process_id" => $process_id);
			}
			if($data["status_id"]!="ST01"){
				$error = "Status User tidak aktif";
				$message = array("result" => "ERROR", "code" => "01", "message" => "Status User tidak aktif", "process_id" => $process_id);
			}
		}else{
			$user_id = 0;
			$message = array("result" => "ERROR", "code" => "01", "message" => "User ID atau Password tidak bisa diproses", "process_id" => $process_id);
		}
		if($error!=""){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Download", "Verifikasi User ID dan Password: $error", $ip, 1);
			return $message;
		}
		
		// 02 - Validasi Data JSON
		$error = "";
		$arrinput = json_decode($input, TRUE);
		if(count($arrinput)<>2){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Download", "Verifikasi User ID dan Password: Valid\nValidasi Data JSON: Format JSON tidak sesuai\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "02", "message" => "Format JSON tidak sesuai", "process_id" => $process_id);
			return $message;
		}
		if($arrinput["transaction_id"]=="") $error .= "transaction_id tidak valid; ";
		if($arrinput["process_id"]=="") $error .= "process_id tidak valid; ";
		if($error!=""){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Download", "Verifikasi User ID dan Password: Valid\nValidasi Data JSON: $error\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "02", "message" => $error, "process_id" => $process_id);
			return $message;
		}
		
		// 03 - Proses Data
		$seq++;
		$query = "SELECT sign_id, response, NOW() date_process, DATE_ADD(NOW(), INTERVAL 15 MINUTE) date_expired, date_register, officer_nip, officer_name, officer_title, date_release, document, department_name FROM sign_tx WHERE user_id = $user_id AND process_id = $arrinput[process_id] AND transaction_id = '$arrinput[transaction_id]'";
		if(!$this->main_act->get_result($query)){
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Download", "Proses Data: Transaksi tidak ditemukan\n\n".json_encode($arrinput), $ip, 1);
			$message = array("result" => "ERROR", "code" => "03", "message" => "Transaksi tidak ditemukan ($arrinput[process_id] / $arrinput[transaction_id])", "process_id" => $process_id);
			return $message;
		}
		$data = $query->row_array();
		$sign_id = $data["sign_id"];
		$date_process = $data["date_process"];
		$date_expired = $data["date_expired"];
		$transaction_id = $arrinput["transaction_id"];
		$date_register = $data["date_register"];
		$nip = $data["officer_nip"];
		$name = $data["officer_name"];
		$title = $data["officer_title"];
		$date_release = $data["date_release"];
		$document = $data["document"];
		$department = $data["department_name"];
		$this->db->trans_begin();
		$this->db->insert("sign_file_tx", array("sign_id" => $sign_id, "date_created" => $date_process));
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Download", "Proses Data: Transaksi gagal\n\n".json_encode($arrinput)."\n\n".json_encode($data), $ip, 1, $sign_id);
			$message = array("result" => "ERROR", "code" => "03", "message" => "Proses Data gagal", "process_id" => $process_id);
			return $message;
		}
		$this->db->trans_commit();
		$url = site_url("download/pdf/$sign_id/".str_replace(" ", "", str_replace(":", "", str_replace("-", "", $date_process))).".pdf");
		$message = array("result" => "OK", "code" => "03", "message" => "Proses berhasil", "process_id" => $process_id, "date_process" => $date_process, "transaction_id" => $transaction_id, "date_register" => $date_register, "nip" => $nip, "name" => $name, "title" => $title, "date_release" => $date_release, "document" => $document, "department" => $department, "pdf" => $url, "date_expired" => $date_expired);
		$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Download", "Proses Data: Berhasil - ".str_replace(trim('\ '), '', json_encode($message))."\n\n".json_encode($arrinput), $ip, 0, $sign_id);
		return $message;
	}
}