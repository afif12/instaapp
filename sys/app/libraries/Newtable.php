<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR);
class Newtable {
	var $rows				= array();
	var $columns			= array();
	var $hiderows			= array();
	var $keys				= array();
	var $proses				= array();
	var $keycari			= array();
	var $heading			= array();
	var $width				= array();
	var $menu_width			= "";
	var $auto_heading		= TRUE;
	var $show_chk			= TRUE;
	var $use_where			= FALSE;
	var $caption			= NULL;	
	var $template 			= NULL;
	var $newline			= "";
	var $lang				= "ID";
	var $empty_cells		= "&nbsp;";
	var $actions			= "";
	var $detils				= "";
	var $baris				= "10";
	var $db 				= "";
	var $hal 				= "AUTO";
	var $uri				= "";
	var $query_total		= "";
	var $js_file			= "";
	var $show_search		= TRUE;
	var $show_process		= TRUE;
	var $use_ajax			= FALSE;
	var $orderby			= 1;
	var $sortby				= "ASC";
	var $anyhide			= FALSE;
	
	function Newtable()
	{
		$this->hiderows[] = 'HAL';
	}
	
	function language($lang)
	{
		$this->lang = $lang;
	}
	
	function width($row)
	{
		$this->width = $row;
		return;
	}
	
	function menu_width($row)
	{
		$this->menu_width = $row;
		return;
	}
	
	function js_file($file)
	{
		$this->js_file = $file;
		return;
	}
	
	function query_total($file)
	{
		$this->query_total = $file;
		return;
	}
	
	function show_search($show)
	{
		$this->show_search = $show;
		return;
	}
	
	function show_process($show)
	{
		$this->show_process = $show;
		return;
	}
	
	function show_chk($show)
	{
		$this->show_chk = $show;
		return;
	}
	
	function use_ajax($use)
	{
		$this->use_ajax = $use;
		return;
	}
	
	function use_where($use)
	{
		$this->use_where = $use;
		return;
	}
	
	function columns($col)
	{
		$this->columns = $col;
		return;
	}
	
	function orderby($order)
	{
		$this->orderby = $order;
		return;
	}
	
	function sortby($sort)
	{
		$this->sortby = $sort;
		return;
	}
	
	function topage($to)
	{
		$this->hal = (int)$to;
		return;
	}
	
	function cidb($db)
	{
		$this->db = $db;
		return;
	}
	
	function rowcount($row)
	{
		$this->baris = $row;
		return;
	}
	
	function ciuri($uri)
	{
		$this->uri = $uri;
		return;
	}
	
	function action($act)
	{
		$this->actions = $act;
		return;
	}
	
	function detail($act)
	{
		$this->detils = $act;
		return;
	}
	
	function hiddens($row)
	{
		if ( ! is_array($row))
		{
			$row = array($row);
		}
		foreach ( $row as $a )
		{
			if ( ! in_array($a, $this->hiderows) ) $this->hiderows[] = $a;
		}
		return;
	}
	
	function keys($row)
	{
		if ( ! is_array($row))
		{
			$row = array($row);
		}
		foreach ( $row as $a )
		{
			if ( ! in_array($a, $this->keys) ) $this->keys[] = $a;
		}
		return;
	}
	
	function menu($row)
	{
		if ( ! is_array($row))
		{
			return FALSE;
		}
		$this->proses = $row;
		return;
	}
	
	function search($row)
	{
		if ( ! is_array($row))
		{
			return FALSE;
		}
		$this->keycari = $row;
		return;
	}
	
	function set_template($template)
	{
		if ( ! is_array($template)) return FALSE;
		$this->template = $template;
	}
	
	function set_heading()
	{
		$args = func_get_args();
		$this->heading = (is_array($args[0])) ? $args[0] : $args;
	}
	
	function make_columns($array = array(), $col_limit = 0)
	{
		if ( ! is_array($array) OR count($array) == 0) return FALSE;
		$this->auto_heading = FALSE;
		if ($col_limit == 0) return $array;
		$new = array();
		while(count($array) > 0)
		{	
			$temp = array_splice($array, 0, $col_limit);
			if (count($temp) < $col_limit)
			{
				for ($i = count($temp); $i < $col_limit; $i++)
				{
					$temp[] = '&nbsp;';
				}
			}
			$new[] = $temp;
		}
		return $new;
	}

	function set_empty($value)
	{
		$this->empty_cells = $value;
	}
	
	function add_row()
	{
		$args = func_get_args();
		$this->rows[] = (is_array($args[0])) ? $args[0] : $args;
	}

	function set_caption($caption)
	{
		$this->caption = $caption;
	}	

	function generate($table_data = NULL)
	{
		if ( ! is_null($table_data))
		{
			if (is_object($table_data))
			{
				$this->_set_from_object($table_data);
			}
			elseif (is_array($table_data))
			{
				$set_heading = (count($this->heading) == 0 AND $this->auto_heading == FALSE) ? FALSE : TRUE;
				$this->_set_from_array($table_data, $set_heading);
			}
			elseif ($table_data!="")
			{
				if ( $this->db == "" || !is_array($this->uri) ) return 'Missing required params (db & uri)';
				if ( ($this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv') && !is_array($this->columns) )  return 'Missing required params (columns)';
				$kunci = "";
				$terkunci = "";
				$cari = "";
				$tercari = "";
				if($key = array_search('search', $this->uri))
				{
					$arrkunci = explode("|", $this->uri[$key+1]);
					$arrcari = explode("|", $this->uri[$key+2]);
					$and = "";
					foreach($arrkunci as $z => $kunci){
						if ( array_key_exists($kunci, $this->keycari))
						{
							//print_r($this->keycari);
							$terkunci = $this->keycari[$kunci];
							$isselectobj = count($terkunci);
							$terkunci = $terkunci[0];
							$cari = urldecode($arrcari[$z]);
							if ($cari != "")
							{
								if ($isselectobj>2)
								{
									$tercari .= "$and $terkunci = '$cari'";
								}
								else
								{
									$cari = str_replace("'", "''", $cari);
									if(substr($terkunci, 0, 4)=="{IN}"){
										$terkunci = substr($terkunci, 4);
										$tercari .= "$and ".str_replace("{LIKE}", "LIKE '%$cari%'", $terkunci);
									}else{
										$tercari .= "$and $terkunci LIKE '%$cari%'";
									}
								}
								$and = "AND";
							}
						}
					}
				}
				if ( $this->baris != "ALL")
				{
					if ( $key = array_search('row', $this->uri) ) $this->baris = (int)$this->uri[$key+1];
					if ( $this->baris<1 ) $this->baris = 10;
					//if ( $this->baris > 100) $this->baris = 100;
				}	
				if ($tercari!="")
				{
					if ( $this->use_where )
					{
						$table_data .= " WHERE $tercari";
						$query_total .= " WHERE $tercari";
					}
					else
					{
						$ada = strpos(strtolower($table_data), "where");
						if ( $ada === false ){
							$table_data .= " WHERE $tercari";
							$query_total .= " WHERE $tercari";
						}else{
							$table_data .= " AND $tercari";
							$query_total .= " AND $tercari";
						}
					}
				}
				#echo $table_data;
				$total_record = 0;
				// echo "<!-- SELECT COUNT(*) AS JML FROM ($table_data) AS TBL"; die();
				if($this->query_total!="")
					$table_count = $this->db->query($this->query_total);
				else
					$table_count = $this->db->query("SELECT COUNT(*) AS JML FROM ($table_data) AS TBL");
				if ( $table_count )
				{
					$table_count = $table_count->row();
					$total_record = $table_count = $table_count->JML;
				}
				else
				{
					$total_record = 0;
				}
				#print($total_record); die();
				if ($key = array_search('order', $this->uri))
				{
					$this->orderby = (int)$this->uri[$key+1];
					$this->sortby = $this->uri[$key+2];
					#if ($this->show_chk) $orderby = $this->columns[$this->orderby-1];
					#else $orderby = $this->columns[$this->orderby];
					if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
					{
						$orderby = $this->columns[$this->orderby-1];
						if ( is_array($orderby) ) $orderby = $orderby[0];
					}
					else
					{
						$orderby = $this->columns[$this->orderby-1];
						// echo $orderby; die();
						// $orderby = $this->orderby;
					}
				}
				else
				{
					if ( is_numeric($this->orderby) )
					{
						if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
						{
							$orderby = $this->columns[$this->orderby-1];
							if ( is_array($orderby) ) $orderby = $orderby[0];
						}
						else
						{
							$orderby = $this->columns[$this->orderby-1];
							// $orderby = $this->orderby;
						}
					}
					else
					{
						$orderby = $this->orderby;
					}
				}
				if ( $this->baris != "ALL")
				{
					$table_count = ceil($table_count / $this->baris);
					if ( $this->hal == "AUTO" ) if ( $key = array_search('page', $this->uri) ) $this->hal = (int)$this->uri[$key+1];
					if ( $this->hal < 1 ) $this->hal = 1;
					if ( $this->hal > $table_count ) $this->hal = $table_count;
					if ( $this->hal==1 )
					{
						if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
						{
							$dari = $this->hal;
							$sampai = $this->baris;
						}
						else
						{
							$dari = 0;
							$sampai = $this->baris;
						}
					}
					else
					{
						if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
						{
							$dari = ($this->hal * $this->baris) - $this->baris + 1;
							$sampai = $this->hal * $this->baris;
						}else{
							$dari = $this->hal>0?($this->hal-1) * $this->baris:0;
							$sampai = $this->baris;
						}
					}
					if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
						$table_data = "SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY $orderby $this->sortby) AS HAL, ".substr($table_data, 6)." ) AS TBLTMP WHERE HAL >= $dari AND HAL <= $sampai";
					else
						$table_data = "$table_data ORDER BY $orderby $this->sortby LIMIT $dari, $sampai";
				}
				else
				{
					$table_data = $table_data." ORDER BY $orderby $this->sortby";
				}
				// echo $table_data; die();
				#die($table_data);
				$table_data = $this->db->query($table_data);
				$this->_set_from_object($table_data);
				#print_r($table_data); die();
			}
		}
	
		if (count($this->heading) == 0 AND count($this->rows) == 0)
		{
			return '<i>Undefined Table Data</i>';
		}
		$ajax = array_search('row', $this->uri);
		if($ajax){
			if($this->uri[$ajax-1]=="ajax"){
				if($this->use_ajax==TRUE) $ajax = 1;
				else $ajax = 0;
			}else{
				$ajax = 0;
			}
		}else{
			$ajax = 0;
		}
		$this->_compile_template();
		if($ajax==0)
		{
			$out = '<div id="divtabelajax"><div id="blank">&nbsp;</div>';
		}
		else
		{
			$out = $this->js_file.'<div id="blank">&nbsp;</div>';
		}
		$out .= '<form id="tb_form" action="'.$this->actions.'">';
		$arrsubhome = array();
		if (count($this->proses) > 0)
		{
			$prost = false;
			foreach($this->proses as $prosa => $prosb){
				if (count($prosb)>3)
				{
					if (!$prost)
					{
						$prost = true;
						$out .= '<span id="tb_process">';
					}
					if($prosb[3]=='home' && $prosb[0]=='GET' && $prosb[2]=='0')
						$arrsubhome[$prosa] = $prosb;
					else
						$out .= '<a href="javascript:void(0)" title="'.$prosa.'" onclick="do_post($(this));" act="'.$prosb[1].'" met="'.$prosb[0].'"><img src="'.base_url().'img/'.$prosb[3].'">'.$prosa.'</a> &nbsp;';
				}
			}
			if ($prost) $out .= '</span>';
		}
		$out .= $this->template['table_open'];
		$out .= '<thead>';
		if ($this->show_search)
		{
			if($this->lang=="EN")
				$out .= '<tr class="head"><td class="pad12" style="padding-left:0" colspan="'.count($this->heading).'"><span><span class="objmobile">Add Filter By &nbsp;</span><select id="tb_keycari" title="Select Category">';
			else
				$out .= '<tr class="head"><td class="pad12" style="padding-left:0" colspan="'.count($this->heading).'"><span><span class="onmobile hidden">Cari &nbsp;</span><span class="objmobile">Filter Berdasarkan &nbsp;</span><select id="tb_keycari" title="Pilih Kategori">';
			foreach ($this->keycari as $a => $b)
			{
				$out .= '<option value="';
				$out .= $a;
				$out .= '"';
				if (count($b)>2)
				{
					if ($b[2][0]=="STRING") $out .= ' cb="'.implode(";", $b[2][1]).'"';
					else if ($b[2][0]=="ARRAY") $out .= ' cb="'.implode(";", array_keys($b[2][1])).'" urcb="'.implode(";", array_values($b[2][1])).'"';
				}
				$out .= '>';
				$out .= $b[1];
				$out .= '</option>';
			}
			if($this->lang=="EN")
				$out .= '</select>&nbsp; <span class="objmobile">Contains &nbsp;</span><input type="text" class="tb_text" id="tb_cari" title="Type &amp; Press Enter To Search" value="" placeholder="..." /></span> <div id="labelload" class="right">Loading..</div>';
			else
				$out .= '</select>&nbsp; <span class="objmobile">Dengan Kata Kunci &nbsp;</span><input type="text" class="tb_text" id="tb_cari" title="Ketik Kata Kunci &amp; Tekan Enter Untuk Mencari" value="" placeholder="..." /></span> <div id="labelload" class="right objmobile">Loading..</div>';
			$arrkey = $this->keycari;
			if(count($arrkunci)>0) $out .= '<div class="lineheight">';
			$ikunci = 0;
			foreach ($arrkunci as $keya => $keyb)
			{
				$arrfound = $arrkey[$keyb];
				if (count($arrfound)>2)
				{
					if ($arrfound[2][0]=="ARRAY")
					{
						$katakunci = $arrfound[2][1];
						$katakunci = $katakunci[$arrcari[$keya]];
					}
					else
					{
						$katakunci = $arrcari[$keya];
					}
				}
				else
				{
					$katakunci = $arrcari[$keya];
				}
				$katakunci = urldecode($katakunci);
				if($this->lang=="EN")
				{
					if($arrcari[$keya]!="") $out .= '<input type="hidden" class="tb_keycariadv" id="tb_keycari'.$keyb.'-'.$ikunci.'" value="'.$arrcari[$keya].'"><br><span class="filter" onclick="remove_filter($(\'#tb_keycari'.$keyb.'-'.$ikunci.'\'));" title="Remove Filter">'.$arrfound[1].' Contains <span class="red">'.$katakunci.'</span></span>';
				}
				else
				{
					if($arrcari[$keya]!="") $out .= '<input type="hidden" class="tb_keycariadv" id="tb_keycari'.$keyb.'-'.$ikunci.'" value="'.$arrcari[$keya].'"><br><span class="filter" onclick="remove_filter($(\'#tb_keycari'.$keyb.'-'.$ikunci.'\'));" title="Hapus Filter">'.$arrfound[1].' Dengan Kata Kunci <span class="red">'.$katakunci.'</span></span>';
				}
				$ikunci++;
			}
			if(count($arrkunci)>0) $out .= '</div>';
			$out .= '</td></tr>';
		}
		// if (count($this->proses) > 0 && $this->show_chk && $this->show_process)
		if (count($this->proses) > 0 && $this->show_process)
		{
			$out .= '<tr class="filter"><td class="pad12" colspan="'.count($this->heading).'">';
			if(count($arrsubhome)>0) $out .= '<span class="align-left navt-top">';
			foreach ($arrsubhome as $a => $b)
			{
				//$out .= '<input type="button" class="tb_text btnsubmenu" title="'.$a.'" act="'.$b[1].'" value="'.$a.'"> &nbsp;';
				$out .= '<a href="javascript:void(0);" class="btnsubmenu" title="'.$a.'" act="'.$b[1].'">'.$a.'</a> &nbsp;';
			}
			if($this->show_chk)
			{
				if($this->lang=="EN")
				{
					if(count($arrsubhome)>0) $out .= '<span class="objmobile">Or &nbsp;</span></span>';
					$out .= '<select id="tb_menu" class="align-left vmobile" title="Choose An Action For Selected Records"><option url="">Choose An Action</option>';
				}
				else
				{
					if(count($arrsubhome)>0) $out .= '<span class="objmobile">Atau &nbsp;</span></span>';
					$out .= '<select id="tb_menu" class="align-left vmobile" title="Pilih Proses Untuk Data Terpilih"><option url="">Pilih Proses</option>';
				}
				foreach ($this->proses as $a => $b)
				{
					if(!array_key_exists($a, $arrsubhome)){
						$out .= '<option class="optmenu" met="';
						$out .= $b[0];
						$out .= '" jml="';
						$out .= $b[2];
						$out .= '" url="';
						$out .= $b[1];
						$out .= '">';
						$out .= "&nbsp; &nbsp;$a";
						$out .= '</option>';
					}
				}
				$out .= '</select></td></tr>';
			}
			else
			{
				$out .= '</td></tr>';
			}
		}
		if ($this->caption)
		{
			$out .= '<caption>' . $this->caption . '</caption>';
		}
		if (count($this->heading) > 0)
		{
			// if ($this->baris=='ALL') $out .= '<tr class="head">';
			// else $out .= '<tr>';
			$out .= '<tr>';
			foreach($this->heading as $z => $heading)
			{
				if ( $this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv' )
				{
					if( !$this->show_chk ) $z--;
				}
				else
				{
					if( !$this->show_chk ) $z++;
				}
				#echo "$z => $heading<hr>";
				if ( ! in_array($heading, $this->hiderows))
				{
					if ( (($this->db->dbdriver=='mssql' || $this->db->dbdriver=='sqlsrv') && $z < 0 && $this->show_chk) || ($z==0 && $this->show_chk) ){
						$out .= '<th width="13">';
						$out .= $heading;
						$out .= '</th>';
					}else{
						if ( array_key_exists($heading, $this->width) ) $out .= '<th width="'.$this->width[$heading].'">';
						else $out .= "<th>";
						if ( $this->baris != "ALL")
						{
							if ( $z==$this->orderby ){
								if ( $this->sortby=="ASC" )
								{
									if($this->lang=="EN")
									{
										$out .= "<span class=\"order\" title=\"Sort By ".$heading." (Z-A)\" orderby=\"$z\" sortby=\"DESC\">$heading</span>";
									}
									else
									{
										$out .= "<span class=\"order\" title=\"Urut Berdasarkan ".$heading." (Z-A)\" orderby=\"$z\" sortby=\"DESC\">$heading</span>";
									}
								}
								else
								{
									if($this->lang=="EN")
									{
										$out .= "<span class=\"order\" title=\"Sort By ".$heading." (A-Z)\" orderby=\"$z\" sortby=\"ASC\">$heading</span>";
									}
									else
									{
										$out .= "<span class=\"order\" title=\"Urut Berdasarkan ".$heading." (A-Z)\" orderby=\"$z\" sortby=\"ASC\">$heading</span>";
									}
								}
							}else{
								if($this->lang=="EN")
								{
									$out .= "<span class=\"order\" title=\"Sort By ".$heading." (A-Z)\" orderby=\"$z\" sortby=\"ASC\">$heading</span>";
								}
								else
								{
									$out .= "<span class=\"order\" title=\"Urut Berdasarkan ".$heading." (A-Z)\" orderby=\"$z\" sortby=\"ASC\">$heading</span>";
								}
							}
						}
						else
						{
							$out .= "<span class=\"order\">$heading</span>";
						}
						$out .= '</th>';
					}
				}
				else
				{
					$this->anyhide = TRUE;
				}
			}
			$out .= $this->template['heading_row_end'];
			#$out .= $this->newline;
		}
		$out .= '</thead>';
		if (count($this->rows) > 0)
		{
			$out .= '<tbody>';
			$i = 1;
			foreach($this->rows as $row)
			{
				if ( ! is_array($row))
				{
					break;
				}
				
				$keyz = "";
				$koma = "";
				foreach ($this->keys as $a)
				{
					$keyz .= $koma.$row[$a];
					$koma = "|";
				}
				$name = (fmod($i++, 2)) ? '' : 'alt_';
				//$out .= $this->template['row_'.$name.'start'];
				if($i%2==0) $cls = 'alt-row';
				else $cls = "main-row";
				if ($this->detils=="")
				{
					$out .= '<tr class="'.$cls.'" urldetil="">';
				}
				else
				{
					if ($this->show_chk)
						$out .= '<tr class="'.$cls.'" urldetil="/'.$keyz.'">';
					else
						$out .= '<tr class="'.$cls.'" urldetil="/'.$keyz.'">';
				}
				$out .= $this->newline;
				if ($this->show_chk) $out .= '<td rowspan="2" class="pad12"><input type="checkbox" name="tb_chk[]" class="tb_chk" value="'.$keyz.'"/></td>';
				$seq = -1;
				foreach($row as $rowz => $cell)
				{
					if ( !in_array($rowz, $this->hiderows))
					{
						if ($this->baris=='ALL' || !$this->show_chk) $out .= '<td class="pad12">';
						else $out .= "<td>";
						if ($cell === "")
						{
							$out .= $this->empty_cells;
						}
						else
						{
							$cell = str_replace(chr(10), '<br>', $cell);
							$cell = str_replace('\/', '/', $cell);
							$cell = str_replace('","', '", "', $cell);
							$url_col = $this->columns[$seq];
							if ( is_array($url_col) )
							{
								$new_url_col = $url_col[1];
								$url_col = explode("{", $new_url_col);
								foreach($url_col as $x){
									$temp_url_col = explode("}", $x);
									$temp_url_col = $temp_url_col[0];
									$new_url_col = str_replace("{".$temp_url_col."}", $row[$temp_url_col], $new_url_col);
								}
								$out .= '<a href="'.$new_url_col.'">'.$cell.'</a>';
							}
							else
							{
								$out .= $cell;
							}
						}
						$out .= $this->template['cell_'.$name.'end'];
					}
					$seq++;
				}
				$out .= $this->template['row_'.$name.'end'];
				#$out .= $this->newline;
				#if (count($this->proses) > 0 && $this->show_chk)
				if ($this->detils!="" || (count($this->proses) > 0 && $this->show_chk))
				{
					if($this->anyhide) $out .= '<tr class="'.$cls.' tdmenu"><td colspan="'.(count($this->heading)-1).'">';
					else $out .= '<tr class="'.$cls.' tdmenu"><td colspan="'.(count($this->heading)).'">';
					if ($this->detils!="")
					{
						if($this->lang=="EN")
						{
							#if ($this->detils!="") $out .= '<a href="javascript:void(0);" title="Detail" class="sub-menu" act="detail"><img src="'.base_url().'img/sub-detail.png">Detail</a> &nbsp;<span class="tb_process"></span>';
							$out .= '<a href="javascript:void(0);" title="Detail" class="sub-menu" act="detail"><img src="'.base_url().'img/sub-detail.png">Detail</a> &nbsp;<span class="tb_process"></span>';
						}
						else
						{
							#if ($this->detils!="") $out .= '<a href="javascript:void(0);" title="Detil" class="sub-menu" act="detail"><img src="'.base_url().'img/sub-detail.png">Detil</a> &nbsp;<span class="tb_process"></span>';
							$out .= '<a href="javascript:void(0);" title="Detil" class="sub-menu" act="detail"><img src="'.base_url().'img/sub-detail.png">Detil</a> &nbsp;<span class="tb_process"></span>';
						}
					}else{
						$out .= '<span class="tb_process"></span>&nbsp;';
					}
					$out .= ' </td></tr>';
				}
			}
			$out .= '</tbody>';
		}
		else
		{
			if($tercari!="")
			{
				if($this->lang=="EN")
				{
					$out .= '<tr><td class="pad12" colspan="'.count($this->heading).'"><center><span class="red">Record Not Found</span></center></td></tr>';
				}
				else
				{
					$out .= '<tr><td class="pad12" colspan="'.count($this->heading).'"><center><span class="red">Data Tidak Ditemukan</span></center></td></tr>';
				}
			}
			else
			{
				if($this->lang=="EN")
				{
					$out .= '<tr><td class="pad12" colspan="'.count($this->heading).'"><center><span class="red">No Records Yet</span></center></td></tr>';
				}
				else
				{
					$out .= '<tr><td class="pad12" colspan="'.count($this->heading).'"><center><span class="red">Belum Ada Data</span></center></td></tr>';
				}
			}
		}
		if (count($this->rows) > 0)
		{
			$out .= '<tfoot>';
			if ( $this->baris != "ALL")
			{
				$datast = ($this->hal - 1);
				if ( $datast<1 ) $datast = 1;
				else $datast = $datast * $this->baris + 1;
				$dataen = $datast + $this->baris - 1;
				if ( $total_record < $dataen ) $dataen = $total_record;
				if ( $total_record==0 ) $datast = 0;
				$out .= '<tr class="title"><td class="pad12" colspan="'.count($this->heading).'"><span class="navt objmobile">';
				if($total_record>=10){
					if($this->lang=="EN")
					{
						if($this->baris==10)
							$out .= '<a href="javascript:void(0);" class="current per" title="View 10 Records Per Page">10</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="View 10 Records Per Page">10</a>';
					}
					else
					{
						if($this->baris==10)
							$out .= '<a href="javascript:void(0);" class="current per" title="Tampilkan 10 Data Per Halaman">10</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="Tampilkan 10 Data Per Halaman">10</a>';
					}
				}
				if($total_record>=20){
					if($this->lang=="EN")
					{
						if($this->baris==20)
							$out .= '<a href="javascript:void(0);" class="current per" title="View 20 Records Per Page">20</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="View 20 Records Per Page">20</a>';
					}
					else
					{
						if($this->baris==20)
							$out .= '<a href="javascript:void(0);" class="current per" title="Tampilkan 20 Data Per Halaman">20</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="Tampilkan 20 Data Per Halaman">20</a>';
					}
				}
				if($total_record>=50){
					if($this->lang=="EN")
					{
						if($this->baris==50)
							$out .= '<a href="javascript:void(0);" class="current per" title="View 50 Records Per Page">50</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="View 50 Records Per Page">50</a>';
					}
					else
					{
						if($this->baris==50)
							$out .= '<a href="javascript:void(0);" class="current per" title="Tampilkan 50 Data Per Halaman">50</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="Tampilkan 50 Data Per Halaman">50</a>';
					}
				}
				if($total_record>=100){
					if($this->lang=="EN")
					{
						if($this->baris==100)
							$out .= '<a href="javascript:void(0);" class="current per" title="View 100 Records Per Page">100</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="View 100 Records Per Page">100</a>';
					}
					else
					{
						if($this->baris==100)
							$out .= '<a href="javascript:void(0);" class="current per" title="Tampilkan 100 Data Per Halaman">100</a>';
						else
							$out .= '<a href="javascript:void(0);" class="per" title="Tampilkan 100 Data Per Halaman">100</a>';
					}
				}
				if($this->lang=="EN")
				{
					if($total_record!=10 && $total_record!=20 && $total_record!=50 && $total_record!=100){
						if($total_record<=100){
							if($this->baris==$total_record){
								$out .= '<a href="javascript:void(0);" class="current per" title="View '.$total_record.' Records Per Page">'.$total_record.'</a>';
							}else{
								$out .= '<a href="javascript:void(0);" class="per" title="View '.$total_record.' Records Per Page">'.$total_record.'</a>';
							}
						}else{
							$out .= '<a href="javascript:void(0);" class="disable" title="Total Records">'.$total_record.'</a>';
						}
					}
					$out .= 'Records Per Page</span><span class="right navt mobile-left">Page &nbsp;';
					if($this->hal==1)
						$out .= '<a href="javascript:void(0);" class="page current" title="Go To Page 1">1</a>';
					else
						$out .= '<a href="javascript:void(0);" class="page" title="Go To Page 1">1</a>';
				}
				else
				{
					if($total_record!=10 && $total_record!=20 && $total_record!=50 && $total_record!=100){
						if($total_record<=100){
							if($this->baris==$total_record || $total_record<10){
								$out .= '<a href="javascript:void(0);" class="current per" title="Tampilkan '.$total_record.' Data Per Halaman">'.$total_record.'</a>';
							}else{
								$out .= '<a href="javascript:void(0);" class="per" title="Tampilkan '.$total_record.' Data Per Halaman">'.$total_record.'</a>';
							}
						}else{
							$out .= '<a href="javascript:void(0);" class="disable" title="Total Data">'.$total_record.'</a>';
						}
					}
					$out .= 'Data Per Halaman</span><span class="right navt mobile-left">Halaman &nbsp;';
					if($this->hal==1)
						$out .= '<a href="javascript:void(0);" class="page current" title="Ke Halaman 1">1</a>';
					else
						$out .= '<a href="javascript:void(0);" class="page" title="Ke Halaman 1">1</a>';
				}
				if($this->hal>=6){
					$out .= '&hellip; ';
					$minnav = $this->hal-2;
					$maxnav = $this->hal+2;
				}else{
					$minnav = 0;
					$maxnav = 0;
				}
				$countnav = 1;
				for($halnav=2;$halnav<$table_count;$halnav++){
					if(($minnav==0 && $maxnav==0) || ($halnav>=$minnav && $halnav<=$maxnav)){
						if($this->lang=="EN")
						{
							if($this->hal==$halnav)
								$out .= '<a href="javascript:void(0);" class="page current" title="Go To Page '.$halnav.'">'.$halnav.'</a>';
							else
								$out .= '<a href="javascript:void(0);" class="page" title="Go To Page '.$halnav.'">'.$halnav.'</a>';
						}
						else
						{
							if($this->hal==$halnav)
								$out .= '<a href="javascript:void(0);" class="page current" title="Ke Halaman '.$halnav.'">'.$halnav.'</a>';
							else
								$out .= '<a href="javascript:void(0);" class="page" title="Ke Halaman '.$halnav.'">'.$halnav.'</a>';
						}
						$countnav++;
					}
					if($countnav==6) break;
				}
				if($table_count>7 && $this->hal<($table_count-3)) $out .= '&hellip; ';
				if($table_count>1)
				{
					if($this->lang=="EN")
					{
						if($this->hal==$table_count)
							$out .= '<a href="javascript:void(0);" class="page current" title="Go To Page '.$table_count.'">'.$table_count.'</a>';
						else
							$out .= '<a href="javascript:void(0);" class="page" title="Go To Page '.$table_count.'">'.$table_count.'</a>';
					}
					else
					{
						if($this->hal==$table_count)
							$out .= '<a href="javascript:void(0);" class="page current" title="Ke Halaman '.$table_count.'">'.$table_count.'</a>';
						else
							$out .= '<a href="javascript:void(0);" class="page" title="Ke Halaman '.$table_count.'">'.$table_count.'</a>';
					}
				}
				$out .= '<span id="tb_total">'.$table_count.'</span></span></td></tr>';
			}
			else
			{
				if($this->lang=="EN")
					$out .= '<tr class="title"><td class="pad12" colspan="'.count($this->heading).'"><span class="navt"><a href="javascript:void(0);" class="disable" title="Total Records">'.$total_record.'</a>Records</span></td></tr>';
				else
					$out .= '<tr class="title"><td class="pad12" colspan="'.count($this->heading).'"><span class="navt"><a href="javascript:void(0);" class="disable" title="Total Data">'.$total_record.'</a>Data</span></td></tr>';
			}
			/*if ($this->show_search)
			{
				if($this->lang=="EN")
					$out .= '<tr class="filter"><td class="pad12" colspan="'.count($this->heading).'"><span>Add Filter By &nbsp;<select id="tb_keycari" title="Select Category">';
				else
					$out .= '<tr class="filter"><td class="pad12" colspan="'.count($this->heading).'"><span>Filter Berdasarkan &nbsp;<select id="tb_keycari" title="Pilih Kategori">';
				foreach ($this->keycari as $a => $b)
				{
					$out .= '<option value="';
					$out .= $a;
					$out .= '"';
					if (count($b)>2)
					{
						if ($b[2][0]=="STRING") $out .= ' cb="'.implode(";", $b[2][1]).'"';
						else if ($b[2][0]=="ARRAY") $out .= ' cb="'.implode(";", array_keys($b[2][1])).'" urcb="'.implode(";", array_values($b[2][1])).'"';
					}
					$out .= '>';
					$out .= $b[1];
					$out .= '</option>';
				}
				if($this->lang=="EN")
					$out .= '</select>&nbsp; Contains &nbsp;<input type="text" class="tb_text" id="tb_cari" title="Type &amp; Press Enter To Search" value="" placeholder="..." /></span> <div id="labelload" class="right">Loading..</div>';
				else
					$out .= '</select>&nbsp; Dengan Kata Kunci &nbsp;<input type="text" class="tb_text" id="tb_cari" title="Ketik Kata Kunci &amp; Tekan Enter Untuk Mencari" value="" placeholder="..." /></span> <div id="labelload" class="right">Loading..</div>';
				$arrkey = $this->keycari;
				if(count($arrkunci)>0) $out .= '<div class="lineheight">';
				foreach ($arrkunci as $keya => $keyb)
				{
					$arrfound = $arrkey[$keyb];
					if($this->lang=="EN")
					{
						if($arrcari[$keya]!="") $out .= '<input type="hidden" class="tb_keycariadv" id="tb_keycari'.$keyb.'" value="'.$arrcari[$keya].'"><br><span class="filter" onclick="remove_filter($(\'#tb_keycari'.$keyb.'\'));" title="Remove Filter">'.$arrfound[1].' Contains <span class="red">'.$arrcari[$keya].'</span></span>';
					}
					else
					{
						if($arrcari[$keya]!="") $out .= '<input type="hidden" class="tb_keycariadv" id="tb_keycari'.$keyb.'" value="'.$arrcari[$keya].'"><br><span class="filter" onclick="remove_filter($(\'#tb_keycari'.$keyb.'\'));" title="Hapus Filter">'.$arrfound[1].' Dengan Kata Kunci <span class="red">'.$arrcari[$keya].'</span></span>';
					}
				}
				if(count($arrkunci)>0) $out .= '</div>';
				$out .= '</td></tr>';
			}*/
			$out .= '</tfoot>';
		}
		$out .= $this->template['table_close'];
		$out .= '<input type="hidden" id="tb_hal" value="'.$this->hal.'" /><input type="hidden" id="tb_view" value="'.$this->baris.'" /><input type="hidden" id="orderby" value="'.$this->orderby.'"><input type="hidden" id="sortby" value="'.$this->sortby.'"><input type="hidden" id="useajax" value="'.($this->use_ajax==TRUE?'TRUE':'FALSE').'">';
		$out .= '<input type="hidden" id="tblang" value="'.$this->lang.'">';
		if ($this->detils!="") $out .= '<input type="hidden" id="urldtl" value="'.$this->detils.'">';
		if($ajax==0){
			#$out .= '<input type="hidden" id="refresh" value="1"></form>';
			return $out.'</form></div>';
		}else{
			#$out .= '<input type="hidden" id="refresh" value="0"></form>';
			echo $out.'</form>'; die();
		}
	}
	
	function clear()
	{
		$this->rows				= array();
		$this->heading			= array();
		$this->auto_heading		= TRUE;	
	}
	
	function _set_from_object($query)
	{
		if ( ! is_object($query))
		{
			return FALSE;
		}
		
		if (count($this->heading) == 0)
		{
			if ( ! method_exists($query, 'list_fields'))
			{
				return FALSE;
			}
			empty($this->heading);
			if ( $this->show_chk ) $this->heading[] = '<input type="checkbox" '.(!$this->show_process?'style="display:none"':'').' id="tb_chkall" />';
			foreach ($query->list_fields() as $a)
			{
				//if ( ! in_array($a, $this->hiderows)) $this->heading[] = $a;
				$this->heading[] = $a;
			}
			//print_r($this->heading);
		}
		
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$this->rows[] = $row;
			}
		}
	}

	function _set_from_array($data, $set_heading = TRUE)
	{
		if ( ! is_array($data) OR count($data) == 0)
		{
			return FALSE;
		}
		
		$i = 0;
		foreach ($data as $row)
		{
			if ( ! is_array($row))
			{
				$this->rows[] = $data;
				break;
			}
						
			if ($i == 0 AND count($data) > 1 AND count($this->heading) == 0 AND $set_heading == TRUE)
			{
				$this->heading = $row;
			}
			else
			{
				$this->rows[] = $row;
			}
			
			$i++;
		}
	}

 	function _compile_template()
 	{ 	
 		if ($this->template == NULL)
 		{
 			$this->template = $this->_default_template();
 			return;
 		}
		
		$this->temp = $this->_default_template();
		foreach (array('table_open','heading_row_start', 'heading_row_end', 'heading_cell_start', 'heading_cell_end', 'row_start', 'row_end', 'cell_start', 'cell_end', 'row_alt_start', 'row_alt_end', 'cell_alt_start', 'cell_alt_end', 'table_close') as $val)
		{
			if ( ! isset($this->template[$val]))
			{
				$this->template[$val] = $this->temp[$val];
			}
		} 	
 	}
	
	function _default_template()
	{
		return  array (
						'table_open' 			=> '<table class="tabelajax">',

						'heading_row_start' 	=> '<tr>',
						'heading_row_end' 		=> '</tr>',
						'heading_cell_start'	=> '<th>',
						'heading_cell_end'		=> '</th>',

						'row_start' 			=> '<tr>',
						'row_end' 				=> '</tr>',
						'cell_start'			=> '<td>',
						'cell_end'				=> '</td>',

						'row_alt_start' 		=> '<tr>',
						'row_alt_end' 			=> '</tr>',
						'cell_alt_start'		=> '<td>',
						'cell_alt_end'			=> '</td>',

						'table_close' 			=> '</table>'
					);	
	}
}