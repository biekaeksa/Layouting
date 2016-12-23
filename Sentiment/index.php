<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Sentiment Analysis</title>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	</head>
	<body>

  <form name="formcari" method="post" >
<table width="330" border="0" align="center" cellpadding="0">
<tr bgcolor="orange">
<td height="25" colspan="3">
<strong> Sentiment Analysis </strong>
</td>
</tr>
<tr> <td>  Cari </td>
<td> <input type="text" name="cari"> </td>
</tr>
<td></td>
<td> <input type="SUBMIT" name="asem" id="SUBMIT" value="search" > </td>
</table>
</form>


<?php
require_once 'twitteroauth/twitteroauth.php';
define('CONSUMER_KEY', 'Jb0OfSeUA0xbTYcM9gmPLqY0A'); 
define('CONSUMER_SECRET', 'O8Z8dM2NebzmPrqs6a3u0LDjcP61sK6yf218JLgNsY43BeeepD'); 
define('ACCESS_TOKEN', '535855595-89WmVhEkvMVGGctBk0Ghrjb10HLTARGgOQUtINQB'); 
define('ACCESS_TOKEN_SECRET', 'Ucfs3mKFtNx4PzXgHFUceeEFAN8SF6UgdVWhXdkiu7lAx'); 

$positiv = 0;
$persentase_positiv = 0;
$negative = 0;
$persentase_negativ = 0;

if(isset($_POST['asem'])){

function search($query)
{
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
  return $connection->get('search/tweets', $query);
}

$data_cari = $_POST['cari'];

$query = array(
  "q" => $data_cari, 
  "count"=>100
);

$ketemu = false;
$results = search($query);
// print_r($results);

function cek_sentimen_positiv($teks){
    $jml_data = 0;
    $data_positiv = array("ahaha", "terkenal", "trend", "internasional", "ngakak","cinta");
    for($i = 0; $i < count($data_positiv);$i++){
      if(stristr($teks, $data_positiv[$i])){
        $jml_data = $jml_data + 1;
      }
    }
return $jml_data;
}

function cek_sentimen_negativ($teks){
    $jml_data = 0;
    $data_negativ = array("sepi", "bingung", "gagal", "bingung", "marah");
    for($i = 0; $i < count($data_negativ);$i++){
      if(stristr($teks, $data_negativ[$i])){
        $jml_data = $jml_data + 1;
      }
    }
return $jml_data;
}



foreach ($results->statuses as $result) {

    if(cek_sentimen_positiv($result->text) ){
         $positiv =  $positiv + 1;
       }else 
         if(cek_sentimen_negativ($result->text) ){
         $negative =  $negative + 1;
       }
       
       
  
} 

$persentase_positiv = $positiv / ($positiv + $negative) * 100 ;
$persentase_negativ = 100 - $persentase_positiv;

}
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Persentase Sentiment Analysis : <?php echo $data_cari; ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Positive',
                y: <?php echo $persentase_positiv; ?>,
            }, {
                name: 'Negative',
                y: <?php echo $persentase_negativ; ?>,
                sliced: true,
                selected: true
            }]
        }]
    });
});
	</script>
<br>
<br>
<br>
<br>
<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
	</body>
</html>
