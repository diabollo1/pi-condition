<html>
<head>
	<link rel="shortcut icon" href="">
	<link rel="stylesheet" type="text/css" href="chart.css">
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	
	
	<script language="javascript" type="text/javascript" src="lib/jquery/jquery-3.3.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script language="javascript" type="text/javascript" src="lib/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="lib/flot/jquery.flot.time.js"></script>
	<script language="javascript" type="text/javascript" src="lib/flot/jquery.flot.axislabels.js"></script>
	<script language="javascript" type="text/javascript" src="lib/flot/jquery.flot.symbol.js"></script>
</head>

<body>
<?php

include "tools.php";

$pass = array();

//Pobranie danych do logowania do bazy danych
$fh = fopen('../../pass_pi_temp.py','r');
while ($line = fgets($fh))
{
	$temp = str_replace('"','',$line);
	
	$name_temp = substr($temp,0,strpos($temp,'='));
	$var_temp = substr($temp,strpos($temp,'=')+1,-1);
	
	if($name_temp != "")
	{
		$pass[$name_temp]=$var_temp;
	}
	//echo($line);
}
fclose($fh);

//print_r2_tab_all($pass);


// Create connection
$conn = mysqli_connect($pass['host'], $pass['user'], $pass['passwd'], $pass['db']);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
	
	$sql = "SELECT *,DATEDIFF(now(),czas) FROM speedtest_net ORDER BY czas DESC";
	$result = mysqli_query($conn, $sql);
	
	$naglowek=0;
	
	echo "<table>";
	
		
		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
					if($naglowek == 0)
					{
						foreach($row as $key => $s)
						{
							//echo "<th>".$key."</th>";
						}
					
					}
					$naglowek=1;
				echo "</tr>";
				
				echo "<tr>";
					foreach($row as $key => $s)
					{
						//echo "<td>".$s."</td>";
					}
				echo "</tr>";
				
				$strtotime_date = strtotime($row["czas"])*1000;
				
				$ping[] = array($strtotime_date,$row["Ping"]);
				$download[] = array($strtotime_date,$row["Download"]);
				$upload[] = array($strtotime_date,$row["Upload"]);
				
			}
		}
		echo "</tr>";
		
		
	echo "</table>";

	//print_r2_tab_all($ping);
	
	$sql = "SELECT *,DATEDIFF(now(),czas) FROM speedtest_net0 ORDER BY czas DESC";
	$result = mysqli_query($conn, $sql);
	
	$naglowek=0;
	
	echo "<table>";
	
		
		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
					if($naglowek == 0)
					{
						foreach($row as $key => $s)
						{
							//echo "<th>".$key."</th>";
						}
					
					}
					$naglowek=1;
				echo "</tr>";
				
				echo "<tr>";
					foreach($row as $key => $s)
					{
						//echo "<td>".$s."</td>";
					}
				echo "</tr>";
				
				$strtotime_date = strtotime($row["czas"])*1000;
				
				$ping0[] = array($strtotime_date,$row["Ping"]);
				$download0[] = array($strtotime_date,$row["Download"]);
				$upload0[] = array($strtotime_date,$row["Upload"]);
				
			}
		}
		echo "</tr>";
		
		
	echo "</table>";

	//print_r2_tab_all($ping);
	
	mysqli_close($conn);
	
?>
	
	</div>
	
	
	<script type="text/javascript">
	
	$(function() {

		var ping 		= <?php echo json_encode($ping );?>;
		var download 	= <?php echo json_encode($download );?>;
		var upload 		= <?php echo json_encode($upload );?>;
		
		var ping0 		= <?php echo json_encode($ping0 );?>;
		var download0 	= <?php echo json_encode($download0 );?>;
		var upload0 	= <?php echo json_encode($upload0 );?>;
		
		//console.log(ping);
		

	var options =
		{
			xaxis:
			{
				mode: "time",
				minTickSize: [1, "day"],
				timeformat: "%d.%m.%y"
			},
			series:
			{
				lines: { show: true }
			},
			axisLabels:
			{
				show: true
			},
			xaxes:
			[{
				axisLabel: "Data",
			}],
			yaxes:
			[
				{
					position: "left",
					axisLabel: "Prędkość [MB/s]",
				},
				{
					position: "right",
					axisLabel: "Ping [ms]"
				},
				{
					position: "right",
					axisLabel: "Czas wykonania [s]"
				}
			], 
			legend:
			{
				show: true,
				noColumns: 3,
				// container:$("#legendholder"),
				position: "nw",
				backgroundOpacity: 0,
				margin: 20
			}
			
		}



		//nie chciało skopiować normalnie bo robiło referencje
		var options1 = JSON.parse(JSON.stringify(options));
		var options2 = JSON.parse(JSON.stringify(options));
		var options3 = JSON.parse(JSON.stringify(options));
		var options4 = JSON.parse(JSON.stringify(options));
		
		//po przerobieniu na JSON i z powrotem ginął ten element
		options1["legend"]["container"]=options["legend"]["container"];
		options2["legend"]["container"]=options["legend"]["container"];
		
		//USTAWIENIA INDYWIDUALNE WYKRESÓW
		// options1["xaxis"]["min"]=
		options1["xaxis"]["max"]=(new Date()).getTime();
		
		options2["xaxis"]["min"]=(new Date()).getTime()-1000*60*60*24*30;
		options2["xaxis"]["max"]=(new Date()).getTime();
		
		options3["xaxis"]["min"]=(new Date()).getTime()-1000*60*60*24*7;
		options3["xaxis"]["max"]=(new Date()).getTime();
		
		// options4["xaxis"]={
				// mode: "time",
				// minTickSize: [1, "hour"],
				// min: (new Date()).getTime()-1000*60*60*24,
				// max: (new Date()).getTime()
				
			// }
		options4["xaxis"]["minTickSize"]=[1, "hour"];
		options4["xaxis"]["timeformat"]="%H:%M";
		options4["xaxis"]["min"]=(new Date()).getTime()-1000*60*60*24;
		options4["xaxis"]["max"]=(new Date()).getTime();
		
		// console.log((new Date()).getTime());
		// console.log((new Date()).getTime()-1000*60*60*60);
		
		dane =
			[
				{ label: "ping do internetu [ms]", data: ping, yaxis: 2 },
				{ label: "download z internetu [MB/s]", data: download, yaxis: 1, points: { show: true, symbol: "triangle" } },
				{ label: "upload do internetu [MB/s]", data: upload, yaxis: 1, points: { show: true, symbol: "triangle" } }
			]
		dane0 =
			[
				{ label: "ping do internetu [ms]", data: ping0, yaxis: 2 },
				{ label: "download z internetu [MB/s]", data: download0, yaxis: 1, points: { show: true, symbol: "triangle" } },
				{ label: "upload do internetu [MB/s]", data: upload0, yaxis: 1, points: { show: true, symbol: "triangle" } }
			]
		
		
		$.plot("#placeholder",	dane,	options1);
		$.plot("#placeholder0",	dane0,	options1);
		
		$("#latenineties1").on('change', function()
		{
			$.plot("#placeholder",	dane,	options1);
			$.plot("#placeholder0",	dane0,	options1);
		});
		
		$("#latenineties2").on('change', function()
		{
			$.plot("#placeholder",	dane,	options2);
			$.plot("#placeholder0",	dane0,	options2);
		});
		
		$("#latenineties3").on('change', function()
		{
			$.plot("#placeholder",	dane,	options3);
			$.plot("#placeholder0",	dane0,	options3);
		});
		
		$("#latenineties4").on('change', function()
		{
			$.plot("#placeholder",	dane,	options4);
			$.plot("#placeholder0",	dane0,	options4);
		});
		
	});

	</script>
	
	
	<div class="content">

		<div id="option" class="btn-group btn-group-toggle" data-toggle="buttons">
		  <label class="btn btn-secondary active">
			<input type="radio" name="options" id="latenineties1" autocomplete="off" checked> Cały okres pomiarowy
		  </label>
		  <label class="btn btn-secondary">
			<input type="radio" name="options" id="latenineties2" autocomplete="off"> Ostatni miesiąc
		  </label>
		  <label class="btn btn-secondary">
			<input type="radio" name="options" id="latenineties3" autocomplete="off"> Ostatni tydzień
		  </label>
		  <label class="btn btn-secondary">
			<input type="radio" name="options" id="latenineties4" autocomplete="off"> Ostatni dzień
		  </label>
		</div>
		
		<div class="demo-container">
			<!--<div id="legendholder" class="demo-placeholder"></div>-->
			<div id="placeholder" class="demo-placeholder"></div>
			<div id="placeholder0" class="demo-placeholder"></div>
		</div>
		<!--
		<button id="latenineties1">Cały okres pomiarowy</button>
		<button id="latenineties2">Ostatni miesiąc</button>
		<button id="latenineties3">Ostatni tydzień</button>
		<button id="latenineties4">Ostatni dzień</button>
		-->
		
		
	</div>
	
	
	
</body>
</html>