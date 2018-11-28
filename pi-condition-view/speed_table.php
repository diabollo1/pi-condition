<html>
<head>
	<link rel="stylesheet" type="text/css" href="table.css">
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	
	<script language="javascript" type="text/javascript" src="lib/jquery/jquery-3.3.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="color_table.js"></script>
</head>

<body>

<script>
	paying_attention_to_high_values('Ping');
	paying_attention_to_high_values('Download',1);
	paying_attention_to_high_values('Upload',1);
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


// Create connection
$conn = mysqli_connect($pass['host'], $pass['user'], $pass['passwd'], $pass['db']);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
	
	$sql = "SELECT * FROM speedtest_net ORDER BY czas DESC";
	$result = mysqli_query($conn, $sql);
	
	$naglowek=0;
	
	echo "<table id='tabela' class='table table-sm'>";
	
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
								echo "<th scope='col'>".$key."</th>";
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


	
	</div>
	
	
</body>
</html>