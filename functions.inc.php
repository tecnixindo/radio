<?php
// v2020.10
// (c)2012 Flat File Database System by Muhammad Fauzan Sholihin 		www.tetuku.com		Bitcoin donation: 1LuapJhp6TkBGgjSEE62SFc3TaSDdy4jYK
// Your donation will keep development process of this web apps. Thanks for your kindness
// You may use, modify, redistribute my apps for free as long as keep the origin copywrite
// https://github.com/tecnixindo/textpress-db 

error_reporting(1);

function write_file($filename, $string) {	// file name, data
$db_size = @filesize($filename);
if ($db_size > 5242880 ) {$string = trim($string); $string = substr($string,0,5242880); } //5242880 / 10485760
$fixed = str_replace("\n\n\n","\n",$string);
$fixed = str_replace("\'","'",$string);
$fixed = str_replace("\\\"","\"",$string);
$fixed = trim($fixed);
$fp = @fopen( $filename,"w"); 
for ($i=0;$i<10;$i++) {
	if (flock($fp, LOCK_EX | LOCK_NB)) {
	fseek($fp, 0, SEEK_END);
	//rewind($fp);
	//fseek($fp, 0, SEEK_SET);
	@fwrite( $fp, "\n".$fixed."\n");
	break;
	}
	usleep(100000);	// 1 second = 1.000.000 micro second
}
@fflush($fp);
@flock($fp, LOCK_UN); 
@fclose( $fp ); 
}

function read_file($filename) {		// file name
if (!file_exists($filename)) {return;}
$db_size = filesize($filename);
if ($db_size <=0 ) {return;}
if ($db_size > 5242880 ) {$db_size = 5242880;} //5242880 / 10485760
$handle = fopen($filename, "r");
flock($handle, LOCK_SH); 
$contents = fread($handle, $db_size);
while (!feof($handle)) { 
$contents .= fread($handle, $db_size);
    }
flock($handle, LOCK_UN); 
fclose($handle);
//sleep(0.3);
return $contents;
}

// format: file name, first row, last row
function read_db($filename,$first_row,$last_row) { //output as array data
if (!stristr($filename,'http://')) {$data_storage = read_file($filename);}
$data_storage = str_replace("\n\n","\n",$data_storage);
$pieces = explode("{-}",$data_storage);
	for ($i=$first_row;$i<=$last_row;$i++) { 
	if (!$pieces[$i]) {break;}
	$out[] = explode ("{,}",$pieces[$i]);
	}
if (count($out) <= 0) {$out = array();}
return $out;
}

function replace_db($filename,$ar_data,$pattern) {
$pattern = trim($pattern);
if (strlen($pattern) < 1) {return;}
$data_storage = read_file($filename);
$data_storage = str_replace("\n\n","\n",$data_storage);
$data_storage = str_replace("\n\n","\n",$data_storage);
$old_size = strlen($data_storage);
$last_key = in_string('{-}','{,}',$data_storage);
	if (!stristr($data_storage,$pattern)) {
		$key = 1 + $last_key;
		$countdata = count($ar_data);
		if ($ar_data[0] != '') {$key = $ar_data[0]; $countdata = $countdata - 1; }
		for ($i=1;$i<=$countdata;$i++) {
			if (!stristr($ar_data[$i],'{-}{,}')){$data .= $ar_data[$i].'{,}';}
		}
		if (stristr($data_storage,$pattern)) {return;}

		if (stristr($data,'{-}{,}')){
			$wrong_data = in_string('{-}{,}','{-}',$data);
			$data = str_replace('{-}{,}'.$wrong_data,'',$data);
		}
		$data = "\n{-}".$key."{,}".$data."\n".$data_storage;
		$new_size = strlen($data);
		if (stristr($data,'{-}{,}')){echo 'error add data'; die();}
		if (is_numeric($key) && stristr($data,'{-}'.$key.'{,}') && $new_size > $old_size) {write_file($filename,$data);}
		return $data;
	}
	if (stristr($data_storage,$pattern)) {
		$cut_storage = in_string('',$pattern,$data_storage);
		$cut_storage = in_string('',strrev('{-}'),strrev($cut_storage));
		$key = in_string('','{,}',strrev($cut_storage));

		$find_key = in_string('{-}'.$key.'{,}','{-}',$data_storage);
		if ($find_key == '') {$find_key = in_string('{-}'.$key.'{,}','',$data_storage);}
		if ($find_key == '') {return false;}
		if ($find_key != '') {$find_key = '{-}'.$key.'{,}'.$find_key;}
		//echo $find_key; die();
		$ar_data[0] = $key;
		$countdata = count($ar_data);
		$data = "\n{-}" ;
		for ($i=0;$i<$countdata;$i++) {
			if (!stristr($ar_data[$i],'{-}{,}')){$data .= $ar_data[$i].'{,}';}
		}
		$data .= "\n";
		$data = str_replace('{-}{-}','{-}',$data);
		//echo $data; die();
		$data_storage = str_replace($find_key,$data,$data_storage);
		$data_storage = str_replace("\n\n","\n",$data_storage);
		if (stristr($data_storage,'{-}{,}')){
			$wrong_data = in_string('{-}{,}','{-}',$data_storage);
			$data_storage = str_replace('{-}{,}'.$wrong_data,'',$data_storage);
		}
		$new_size = strlen($data_storage);
		$old_size = $old_size*0.4;
		if (stristr($data_storage,'{-}{,}')){echo 'error edit data'; die();}
		if (is_numeric($key) && stristr($data_storage,'{-}'.$key.'{,}') && $new_size > $old_size) {write_file($filename,$data_storage);}
		return $data;
	}
}

// format: file name , database unique key
function key_db ($filename,$key){ // output: row data at specific key
if ($key == '') {$out = array(); return $out;}
$data = "{-}".$key."{,}";
$data_storage = read_file($filename);
if (!stristr($data_storage,$data)) {return;}
$find_key = substr($data_storage, strpos($data_storage, $data));
$find_key = substr($find_key,0, strpos($find_key, "\n{-}"));
if ($find_key == '') {$find_key = substr($data_storage, strpos($data_storage, $data));}
$data_storage = str_replace("\n\n","\n",$data_storage);
$out = explode ("{,}",$find_key);
return $out;
}

function array_sort($array, $column_data, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $column_data) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
			case SORT_NUM:
				natsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function in_string($start, $end, $string) 
{ 
	if ($start == '') {$string = '{#}'.$string; $start = '{#}'; }
	$count_string = strlen($start);
	$result = substr($string, strpos($string, $start));
	$result = substr($result, strpos($result, $start) + $count_string);
	if ($end == '') {$result = $result.'{#}'; $end = '{#}';}
	$result = substr($result,0, strpos($result, $end));
	return $result;
} 

function access_url($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_USERAGENT, base64_decode('TW96aWxsYS81LjAgKFdpbmRvd3M7IFU7IFdpbmRvd3MgTlQgNS4xOyBydTsgcnY6MS45LjIuMTEpIEdlY2tvLzIwMTAxMDEyIEZpcmVmb3gvMy42LjEx'));
	curl_setopt($curl, CURLOPT_REFERER, base64_decode('aHR0cDovL3d3dy50ZXR1a3UuY29t') );
	curl_setopt($curl, CURLOPT_POST, false);
	$curlData = curl_exec($curl);
	curl_close($curl);
	return $curlData;
}

?>