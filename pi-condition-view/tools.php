<?php
function print_r2($d)
{
	echo "<pre>";
	echo print_r($d);
	echo "</pre>";
}

function print_r2_tab_all($tab)
{
	echo "<table>";
	
	echo "<tr>";
		foreach($tab as $key => $s)
		{
			echo "<th>".$key."</th>";
		}
	echo "</tr>";
	
	echo "<tr>";
		foreach($tab as $key => $s)
		{
			if(is_object($s))
			{
				$s = (array) $s;
			}
			
			if(is_array($s))
			{
				echo "<td>";
				print_r2_tab_all($s);
				echo "</td>";
			}else
			{
				echo "<td>".$s."</td>";
			}
		}
	echo "</tr>";
	
	
	echo "</table>";
}
?>