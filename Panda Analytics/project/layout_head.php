<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- set the page title-->
    <title><?php echo isset($page_title) ? strip_tags($page_title) : "Store Front"; ?></title>

    <!-- Bootstrap CSS -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen" />

    <!-- user custom CSS -->
  <link href="<?php echo $home_url . "libs/css/user.css" ?>" rel="stylesheet" />

  <script>
  function myFunction()
  {
  var x = document.getElementById("NavbarBottom");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}
  </script>
  		<script>
window.onload= function(){
	var chart = new CanvasJS.Chart("chartContainer1", {
	title: {
		text: "Cash Flow"
	},
	axisY: {
		title: "Anno"
	},
	data: [{
		type: "line",
		dataPoints: <?php echo json_encode($_SESSION['data1'], JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 chart = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	title:{
		text: "Grafico anno corrente"
	},
	axisY: {
		title: "Revenue (in EURO)",
		prefix: "€",
		suffix:  ""
	},
	data: [{
		type: "bar",
		yValueFormatString: "€#,###.##",
		indexLabel: "{y}",
		indexLabelPlacement: "inside",
		indexLabelFontWeight: "bolder",
		indexLabelFontColor: "white",
		dataPoints: <?php echo json_encode($_SESSION['data2'], JSON_NUMERIC_CHECK); ?>
	}]
});

chart.render();
}
</script>





</head>
<body>

    <!-- include the navigation bar -->
    <?php include_once 'navigation.php';
	if(isset($_GET['h'])&&$_GET['h']==0)
		$_SESSION['current_flow_id']=0;
	?>

    <!-- container -->
    <div class="container-fluid">

   
</body>
