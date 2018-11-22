<html>
<head>
	<link rel="stylesheet" type="text/css" href="table.css">
	<link rel="stylesheet" type="text/css" href="chart.css">
	<script language="javascript" type="text/javascript" src="lib/flot/jquery.js"></script>
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
	
	mysqli_close($conn);
	
?>
	
	</div>
	
	
	<script type="text/javascript">
	
	$(function() {

		var ping 		= <?php echo json_encode($ping );?>;
		var download 	= <?php echo json_encode($download );?>;
		var upload 		= <?php echo json_encode($upload );?>;
		
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
				container:$("#legendholder")
				
				/*
				position: "sw",
				backgroundOpacity: 0,
				margin: 20
				*/
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
		
		$.plot(
			"#placeholder",
			dane,
			options1);
		
		$("#latenineties1").click(function () {
			$.plot(
				"#placeholder",
				dane,
				options1);
		});
		
		$("#latenineties2").click(function () {
			$.plot(
				"#placeholder",
				dane,
				options2);
		});
		
		$("#latenineties3").click(function () {
			$.plot(
				"#placeholder",
				dane,
				options3);
		});
		
		$("#latenineties4").click(function () {
			$.plot(
				"#placeholder",
				dane,
				options4);
		});
		
	});

	</script>
	
	
	<div class="content">

		
		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
			<div id="legendholder" class="demo-placeholder"></div>
		</div>

		<button id="latenineties1">Cały okres pomiarowy</button>
		<button id="latenineties2">Ostatni miesiąc</button>
		<button id="latenineties3">Ostatni tydzień</button>
		<button id="latenineties4">Ostatni dzień</button>
		
	</div>
	
	
	
</body>
</html>