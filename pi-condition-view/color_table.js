$( document ).ready(function() {
    // console.log( "ready!" );
	
	paying_attention_to_high_values('Ping');
	paying_attention_to_high_values('Download',1);
	paying_attention_to_high_values('Upload',1);
	
	function paying_attention_to_high_values(column_name,low=0)
	{
		var table = document.getElementById('tabela');
		// var cells = table.getElementsByTagName('td');
		
		
		columnTh = $("table th:contains('"+column_name+"')");
		columnIndex = columnTh.index() + 1;
		
		var cells =  $('table tr td:nth-child(' + columnIndex + ')');
		
		var header = Array();
			
		$("table tr th").each(function(i, v){
				header[i] = $(this).text();
		})
		// console.log(header);
			
		var max = null;
		var min = null;
		
		cells.each(function()
		{
			temp = $(this).text();
			if(temp != 0)
			{
				if(temp > max || max == null) max = temp;
				if(temp < min || min == null) min = temp;
			}
		});
		// console.log(max);
		// console.log(min);
		
		var temp = 0;
		
		if(low==1) 
		{
			temp = max;
			max = min;
			min = temp;
		}
		
		cells.each(function()
		{
			przezroczystosc = calculation_RGB(max,min,$(this).text());
			$(this).css("background", "rgb(0,0,255,"+przezroczystosc+")");
			// console.log(przezroczystosc);
		});
		
		
		function calculation_RGB(max, min, value)
		{			
			var wynik = 0;
			
			max = parseFloat(max)*0.1;	//zwiększenie płynności
			
			wynik = (value-min)/(max-min);
			wynik = Math.abs(wynik);
			// console.log("("+value+"-"+min+")/("+max+"-"+min+") = "+wynik);
			return wynik;
		}
	}

});