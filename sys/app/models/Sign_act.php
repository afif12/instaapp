<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign_act extends CI_Model{
	public function set($user_id, $password, $passphrase, $ip, $input){
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
			$error = "User ID atau Password tidak bisa diproses";
			$message = array("result" => "ERROR", "code" => "01", "message" => "User ID atau Password tidak bisa diproses", "process_id" => $process_id);
		}
		if($error!=""){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "Verifikasi User ID dan Password: $error", $ip, 1);
			return $message;
		}
		
		// 02 - Validasi Data JSON
		$error = "";
		$arrinput = json_decode($input, TRUE);
		if(count($arrinput)<10){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "User ID atau Password: Valid\nValidasi Data JSON: Format JSON tidak sesuai\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "02", "message" => "Format JSON tidak sesuai", "process_id" => $process_id);
			return $message;
		}
		if($arrinput["transaction_id"]=="") $error .= "transaction_id tidak valid; ";
		if($arrinput["nip"]=="") $error .= "nip tidak valid; ";
		if($arrinput["name"]=="") $error .= "name tidak valid; ";
		if($arrinput["title"]=="") $error .= "title tidak valid; ";
		if($arrinput["department_id"]=="") $error .= "department_id tidak valid; ";
		if($arrinput["pdf"]=="") $error .= "pdf tidak valid; ";
		if($error!=""){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "User ID atau Password: Valid\nValidasi Data JSON: $error\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "02", "message" => $error, "process_id" => $process_id);
			return $message;
		}
		
		// 03 - Validasi Data Unit Kerja
		if(trim("".$arrinput["sotk"])=="") $arrinput["sotk"] = "2018";
		$department = explode("|", $this->main_act->get_uraian("SELECT CONCAT(department_id, '|', name) name FROM department_tm WHERE status_id IN ('DE01', 'DE03') AND code_3 = '$arrinput[department_id]' AND `version` = $arrinput[sotk]", "name"));
		$department_id = $department[0];
		$department = $department[1];
		if($department_id=="" || $department==""){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "User ID atau Password: Valid\nValidasi Data JSON: Valid\nValidasi Data Unit Kerja: Kode Unit Kerja tidak ditemukan\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "03", "message" => "Kode Unit Kerja tidak ditemukan", "process_id" => $process_id);
			return $message;
		}
		
		// 04 - Validasi Data Penandatangan
		$seq++;
		$officer_id = $this->main_act->get_uraian("SELECT CONCAT(officer_id, '|', nik) officer_id FROM officer_tm WHERE status_id = 'ST01' AND nip = '$arrinput[nip]'", "officer_id");
		if($officer_id==""){
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "User ID atau Password: Valid\nValidasi Data JSON: Valid\nValidasi Data Unit Kerja: Valid\nValidasi Data Penandatangan: NIP Penandatangan ($arrinput[nip]) tidak aktif/tidak terdaftar\n\n$input", $ip, 1);
			$message = array("result" => "ERROR", "code" => "04", "message" => "NIP Penandatangan tidak aktif/tidak terdaftar", "process_id" => $process_id);
			return $message;
		}else{
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Verifikasi Data", "Sign", "User ID atau Password: Valid\nValidasi Data JSON: Valid\nValidasi Data Unit Kerja: Valid\nValidasi Data Penandatangan: Valid\n\n$input", $ip, 0);
		}
		$officer_id = explode("|", $officer_id);
		$nik = $officer_id[1];
		$officer_id = $officer_id[0];
		
		// 05 - Download File PDF
		$dir = "/home/pdf/";
		$path = date("Y/m");
		if(!is_dir($dir.$path)) mkdir($dir.$path);
		$path = date("Y/m/d");
		if(!is_dir($dir.$path)) mkdir($dir.$path);
		$file = $process_id.".pdf";
		$pdf = $process_id."-signed.pdf";
		if(file_exists($dir.$path."/".$file)){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Sign", "Path File PDF Tidak Valid", $ip, 1);
			$message = array("result" => "ERROR", "code" => "05", "message" => "Path file PDF tidak valid", "process_id" => $process_id);
			return $message;
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_URL, $arrinput["pdf"]);
		curl_setopt($curl, CURLOPT_REFERER, $arrinput["pdf"]);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$file_pdf = curl_exec($curl);
		curl_close($curl);
		// if(!file_put_contents($dir.$path."/".$file, fopen($arrinput["pdf"], 'r'))){
		// if(!file_put_contents($dir.$path."/".$file, file_get_contents($arrinput["pdf"]))){
		if(!file_put_contents($dir.$path."/".$file, $file_pdf)){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Sign", "Download File PDF $arrinput[pdf]: Gagal", $ip, 1);
			$message = array("result" => "ERROR", "code" => "05", "message" => "Download file PDF ($arrinput[pdf]) gagal", "process_id" => $process_id);
			return $message;
		}elseif(mime_content_type($dir.$path."/".$file)!="application/pdf"){
			$seq++;
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Sign", "Download File PDF $arrinput[pdf]: Format File bukan PDF", $ip, 1);
			$message = array("result" => "ERROR", "code" => "05", "message" => "Download file PDF ($arrinput[pdf]) gagal, format file bukan PDF", "process_id" => $process_id);
			return $message;
		}
		// 06 - Proses Data
		$seq++;
		$data = array("process_id" => $process_id, 
					  "transaction_id" => $arrinput["transaction_id"], 
					  "date_register" => $arrinput["date_register"], 
					  "date_release" => $arrinput["date_release"], 
					  "officer_id" => $officer_id, 
					  "officer_name" => $arrinput["name"], 
					  "officer_nip" => $arrinput["nip"], 
					  "officer_title" => $arrinput["title"], 
					  "department_id" => $department_id, 
					  "department_name" => $department, 
					  "application_id" => $application_id, 
					  "application_name" => $application, 
					  "document" => $arrinput["document"], 
					  "notes" => $arrinput["notes"], 
					  "file_pdf" => "$path/$file", 
					  "user_id" => $user_id, 
					  );
		$this->db->trans_begin();
		$this->db->insert("sign_tx", $data);
		$sign_id = $this->db->insert_id();
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Sign", "Download File PDF $arrinput[pdf]: Berhasil - $path/$file\nProses Data: Gagal\n\n".json_encode($data), $ip, 1);
			$message = array("result" => "ERROR", "code" => "06", "message" => "Proses Data gagal", "process_id" => $process_id);
			return $message;
		}else{
			$this->db->trans_commit();
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses Data", "Sign", "Download File PDF $arrinput[pdf]: Berhasil - $path/$file\nProses Data: Berhasil - ID $sign_id\n\n".json_encode($data), $ip, 0, $sign_id);
			$query = "SELECT date_request, DATE_ADD(date_request, INTERVAL 15 MINUTE) date_expired FROM sign_tx WHERE sign_id = $sign_id";
			$this->main_act->get_result($query);
			$date = $query->row_array();
			$date_process = $date["date_request"];
			$date_expired = $date["date_expired"];
		}
		
		// 07 - Proses eSign Client
		$seq++;
		$result = $this->main_act->send_json($nik, $passphrase, $arrinput["x"], $arrinput["y"], $arrinput["width"], $arrinput["height"], $dir.$path."/".$file, $dir.$path."/".$pdf);
		$this->db->trans_begin();
		if($result!="pdf"){
			if(strpos($result, "Passphrase anda salah")!==FALSE){
				$message = "Passphrase tidak valid";
			}elseif($result=="save-error"){
				$message = "Generate file PDF TTE gagal";
			}else{
				$message = "eSign Client gagal - $result";
			}
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses eSign Client", "Sign", $result, $ip, 1);
			$this->db->where("sign_id", $sign_id);
			$this->db->update("sign_tx", array("date_response" => "NOW()", "response" => $result, "status_id" => "SI03"));
			if($this->db->trans_status()===FALSE){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();
			}
			$message = array("result" => "ERROR", "code" => "07", "message" => $message, "process_id" => $process_id);
		}else{
			$this->db->where("sign_id", $sign_id);
			$this->db->update("sign_tx", array("date_response" => "NOW()", "response" => $path."/".$pdf, "status_id" => "SI02"));
			$this->db->insert("sign_file_tx", array("sign_id" => $sign_id, "date_created" => $date_process));
			if($this->db->trans_status()===FALSE){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();
			}
			$url = site_url("download/pdf/$sign_id/".str_replace(" ", "", str_replace(":", "", str_replace("-", "", $date_process))).".pdf");
			$message = array("result" => "OK", "code" => "07", "message" => "Proses berhasil", "process_id" => $process_id, "date_process" => $date_process, "pdf" => $url, "date_expired" => $date_expired);
			$this->main_act->set_user_log($user_id, $seq, $process_id, "Proses eSign Client", "Sign", str_replace(trim('\ '), '', json_encode($message)), $ip, 0);
		}
		return $message;
	}
}