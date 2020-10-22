<?php

include_once "functions.inc.php";

?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Radio Online</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Source: github.com/tecnixindo/radio -->
</head>

<body>
<?php
	$row_list = read_db('db/radio_list.txt',1,999);
	foreach($row_list as $column_list) {
		$radio[$column_list[0]] = $column_list;	
	}
if ($_GET['id'] != '') {
?>
    <div class="jumbotron">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-xl-12" align="center">
            <br><a href="<?=$radio[$_GET['id']][1]?>" target="_blank"><img src="<?=$radio[$_GET['id']][3]?>" height="100px" alt=""/></a><br><?=$radio[$_GET['id']][2]?><br><audio controls style="height: 60px;" src="<?=$radio[$_GET['id']][1]?>" type="audio/mp3"></audio><br><span class="text-muted"><sub><?=$radio[$_GET['id']][4]?>, <?=$radio[$_GET['id']][5]?></sub><br><sup><?=$radio[$_GET['id']][6]?></sup></span><br>
        </div>
    </div>
    </div>
<?php
}
?>
<div class="container">
<div class="row">
<?php
$row_rating = read_db('db/radio_rating.txt',1,999);
$row_rating = array_sort($row_rating,2,SORT_NUM);
$total_rating = count($row_rating);
	foreach ($row_rating as $column_rating) {
if ($_GET['id'] == $column_rating[0]) {
	$radio_rating[0] = $column_rating[0];		// radio name
	$radio_rating[1] = $column_rating[1];		// radio name
	$radio_rating[2] = $column_rating[2] - 1;	// rating
	$radio_rating[3] = '';
}
?>
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" align="center">
    	<a href="radio_list.php?id=<?=$radio[$column_rating[0]][0]?>">
        <br><img src="<?=$radio[$column_rating[0]][3]?>" height="72px" alt=""/><br><?=$radio[$column_rating[0]][2]?><br><span class="text-muted"><sub><?=$radio[$column_rating[0]][4]?>, <?=$radio[$column_rating[0]][5]?></sub><br><sup><?=$radio[$column_rating[0]][6]?></sup></span><br>
    	</a>
    </div>
<?php }?>
<?php
	if ($_GET['id'] != '') {
		replace_db('db/radio_rating.txt',$radio_rating,$radio_rating[1]);
	}
?>
</div>
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
