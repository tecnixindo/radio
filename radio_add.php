<?php

include_once "functions.inc.php";

if ($_POST['save'] == 'radio') {
	$data_radio[1] = $_POST['radio_url'];		// radio url
	$data_radio[2] = $_POST['radio_name'];		// radio name
	$data_radio[3] = $_POST['radio_logo'];		// url of radio logo
	$data_radio[4] = $_POST['radio_region'];	// region / distric
	$data_radio[5] = $_POST['radio_country'];	// country
	$data_radio[6] = $_POST['radio_genre'];		// genre separated by comma
	$data_radio[7] = '';
	replace_db('db/radio_list.txt',$data_radio,$data_radio[2]);
	
	$radio_rating[1] = $_POST['radio_name'];	// radio name
	$radio_rating[2] = 9999;					// pre rating
	$radio_rating[3] = '';
	replace_db('db/radio_rating.txt',$radio_rating,$radio_rating[1]);
	
	access_url('https://radio.tetuku.com/radio_add_tmp.php?radio='.urlencode(serialize($data_radio)));
}
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage the list of Radio online</title>
</head>

<body>
<form name="form1" method="post" action="">
  <table width="100%"  border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>Audio URL </td>
      <td><input name="radio_url" type="text" id="radio_url" value="<?=$_GET['radio_url']?>" size="55" placeholder="URL of the radio audio"></td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input name="radio_name" type="text" id="radio_name" value="<?=$_GET['radio_name']?>" size="55" placeholder="Name of the radio station"></td>
    </tr>
    <tr>
      <td>Logo</td>
      <td><input name="radio_logo" type="text" id="radio_logo" value="<?=$_GET['radio_logo']?>" size="55" placeholder="URL of logo"></td>
    </tr>
    <tr>
      <td>Region</td>
      <td><input name="radio_region" type="text" id="radio_region" value="<?=$_GET['radio_region']?>" size="55" placeholder="Region within the country"></td>
    </tr>
    <tr>
      <td>Country</td>
      <td><input name="radio_country" type="text" id="radio_country" value="<?=$_GET['radio_country']?>" size="55" placeholder="Country name"></td>
    </tr>
    <tr>
      <td>Genre</td>
      <td><input name="radio_genre" type="text" id="radio_genre" value="<?=$_GET['radio_genre']?>" size="55" placeholder="Genre seperated by comma"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Save">
      <input name="save" type="hidden" id="save" value="radio"></td>
    </tr>
  </table>
</form>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<?php
	$row_list = read_db('db/radio_list.txt',1,100);
	foreach ($row_list as $column_list) {
?>
  <tr>
    <td align="center" valign="middle"><img src="<?=$column_list[3]?>" width="90px" alt=""/><br><?=$column_list[2]?></td>
    <td valign="middle"><audio controls style="width: 160px;"><source src="<?=$column_list[1]?>" type="audio/mp3"></audio></td>
    <td valign="middle"><?=$column_list[4]?></td>
    <td valign="middle"><?=$column_list[5]?></td>
    <td valign="middle"><?=$column_list[6]?></td>
  </tr>
<?php }?>
</table>
<p>&nbsp;</p>
</body>
</html>
