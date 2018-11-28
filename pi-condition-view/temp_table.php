<html>
<head>
	<link rel="stylesheet" type="text/css" href="table.css">
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	
	<script language="javascript" type="text/javascript" src="lib/jquery/jquery-3.3.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script language="javascript" type="text/javascript" src="color_table.js"></script>
</head>

<body>

<script>
	
	$(document).ready(function(){ 
		paying_attention_to_high_values('CPU_temp');
		paying_attention_to_high_values('RAM_used');
		paying_attention_to_high_values('RAM_free',1);
		paying_attention_to_high_values('DISK_free',1);
		
		$('#pi0').hide();
	
		$("#latenineties1").on('change', function()
		{
			$('#pi').show();
			$('#pi0').hide();
		});
		$("#latenineties2").on('change', function()
		{
			$('#pi').hide();
			$('#pi0').show();
		});
	});
</script>

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



echo '
	<div id="option" class="btn-group btn-group-toggle" data-toggle="buttons">
	  <label class="btn btn-secondary active">
		<input type="radio" name="options" id="latenineties1" autocomplete="off" checked> Raspberry Pi 3 [192.168.1.100]
	  </label>
	  <label class="btn btn-secondary">
		<input type="radio" name="options" id="latenineties2" autocomplete="off"> Raspberry Pi 0 [192.168.1.101]
	  </label>
	</div>
	';




// Create connection
$conn = mysqli_connect($pass['host'], $pass['user'], $pass['passwd'], $pass['db']);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
	
	$sql = "SELECT * FROM temp_cpu_ram_disk ORDER BY czas DESC";
	$result = mysqli_query($conn, $sql);
	
	$naglowek=0;
	
	echo "<table id='pi' class='table table-sm'>";
		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
					if($naglowek == 0)
					{
						echo '<thead class="thead-dark">';
							foreach($row as $key => $s)
							{
								echo "<th>".$key."</th>";
							}
						echo '</thead>';
					}
					$naglowek=1;
				echo "</tr>";
				
				echo "<tr>";
					foreach($row as $key => $s)
					{
						echo "<td>".$s."</td>";
					}
				echo "</tr>";
			}
		}
		echo "</tr>";
	echo "</table>";

	$sql = "SELECT * FROM temp_cpu_ram_disk0 ORDER BY czas DESC";
	$result = mysqli_query($conn, $sql);
	
	$naglowek=0;
	
	echo "<table id='pi0' class='table table-sm'>";
		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				echo "<tr>";
					if($naglowek == 0)
					{
						echo '<thead class="thead-dark">';
							foreach($row as $key => $s)
							{
								echo "<th>".$key."</th>";
							}
						echo '</thead>';
					}
					$naglowek=1;
				echo "</tr>";
				
				echo "<tr>";
					foreach($row as $key => $s)
					{
						echo "<td>".$s."</td>";
					}
				echo "</tr>";
			}
		}
		echo "</tr>";
	echo "</table>";

	mysqli_close($conn);
	
?>
	
	
	
</body>
</html>