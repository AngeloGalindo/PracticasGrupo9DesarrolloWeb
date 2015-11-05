
function show_table()
	{
	div=document.getElementById("div_tabla");
	$(div).html("");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="&ver_tabla=1";
	ajax_dynamic_div("POST",'execute_python_r12_tabla.php',str_param,div,false);
	get_hora();
	}

function get_hora()
	{
	var hoy=new Date();
	document.getElementById('hora_cliente').value=hoy.getDate()+"/"+(hoy.getMonth()+1)+"/"+hoy.getFullYear()+" "+hoy.getHours()+":"+hoy.getMinutes()+":"+hoy.getSeconds();
	}
function grabar()
	{
	div=document.getElementById("div_update");
	
	hora=document.getElementById('hora').value;
	day3=document.getElementById('date3').value;
	day3 = day3.substring(6,10)+"-"+day3.substring(3,5)+"-"+day3.substring(0,2);
	if(document.getElementById('proceso').value=='migracion_r12')
		{
		dato=document.getElementById('datos').value;
		repo=document.getElementById('reporteria').value;
		day1=document.getElementById('date1').value;
		day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
		day2=document.getElementById('date2').value;
		day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		day4=document.getElementById('date4').value;
		day4 = day4.substring(6,10)+day4.substring(3,5)+day4.substring(0,2);
		str_param="day3="+day3+"&day2="+day2+"&day1="+day1+"&day4="+day4+"&hora="+hora+"&dato="+dato+"&repo="+repo+"&migracion_r12=migracion&o_insertar=1"; 
		}
	else if(document.getElementById('proceso').value=='gerencial')
		{
		mes=document.getElementById('mes').value;
		anio=document.getElementById('anio').value;
		modoEnvio=document.getElementById('modoEnvio').value;
		str_param="day3="+day3+"&hora="+hora+"&mes="+mes+"&anio="+anio+"&modoEnvio="+modoEnvio+"&gerencial=gerencial&o_insertar=1";
		}
	else if(document.getElementById('proceso').value=='fact_papel')
		{
		day1=document.getElementById('date1').value;
		day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
		day2=document.getElementById('date2').value;
		day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		datos=document.getElementById('datos_papel').value;
		str_param="day3="+day3+"&hora="+hora+"&day1="+day1+"&day2="+day2+"&datos="+datos+"&fact_papel=fact_papel&o_insertar=1";
		}
	else if(document.getElementById('proceso').value=='myemail')
		{
		day2=document.getElementById('date1').value;
		day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		modoEnvio=document.getElementById('modoEnvio').value;
		str_param="day3="+day3+"&hora="+hora+"&day2="+day2+"&modoEnvio="+modoEnvio+"&myemail=myemail&o_insertar=1";
		}
	ajax_dynamic_div("POST",'execute_python_r12_update.php',str_param,div,false);
	cancelar();
	show_table();
	}
function anular(id)
	{
	div=document.getElementById("div_update");
	str_param="id="+id+"&o_anular=1";
	ajax_dynamic_div("POST",'execute_python_r12_update.php',str_param,div,false);
	show_table();
	}
function cancelar()
	{
	document.getElementById('div_0').style.visibility = 'visible';
	document.getElementById('div_1').style.visibility = 'hidden';
	document.getElementById('div_2').style.visibility = 'hidden';
	document.getElementById('div_3').style.visibility = 'hidden';
	document.getElementById('div_4').style.visibility = 'hidden';
	document.getElementById('div_5').style.visibility = 'hidden';
	document.getElementById('div_6').style.visibility = 'hidden';
	document.getElementById('div_7').style.visibility = 'hidden';
	document.getElementById('div_8').style.visibility = 'hidden';
	document.getElementById('div_9').style.visibility = 'hidden';
	document.getElementById('div_10').style.visibility = 'hidden';
	document.getElementById('div_11').style.visibility = 'hidden';
	document.getElementById('div_12').style.visibility = 'hidden';
	document.getElementById('div_13').style.visibility = 'hidden';
	document.getElementById('div_0').style.display = 'inline';
	document.getElementById('div_1').style.display = "none";
	document.getElementById('div_2').style.display = "none";
	document.getElementById('div_3').style.display = "none";
	document.getElementById('div_4').style.display = "none";
	document.getElementById('div_5').style.display = "none";
	document.getElementById('div_6').style.display = "none";
	document.getElementById('div_7').style.display = "none";
	document.getElementById('div_8').style.display = "none";
	document.getElementById('div_9').style.display = "none";
	document.getElementById('div_10').style.display = "none";
	document.getElementById('div_11').style.display = "none";
	document.getElementById('div_12').style.display = "none";
	}
function nueva()
	{
		
	document.getElementById('proceso').value= 'migracion_r12';
	document.getElementById('div_0').style.visibility = 'hidden';
	document.getElementById('div_1').style.visibility = 'visible';
	document.getElementById('div_2').style.visibility = 'visible';
	document.getElementById('div_8').style.visibility = 'visible';
	document.getElementById('div_9').style.visibility = 'visible';
	document.getElementById('div_0').style.display = "none";
	document.getElementById('div_1').style.display = 'inline';
	document.getElementById('div_2').style.display = 'inline';
	document.getElementById('div_8').style.display = 'inline';
	document.getElementById('div_9').style.display = 'inline';
	fncCambiarProceso();
	}
	
function fncCambiarProceso()
{
	if(document.getElementById('proceso').value=='migracion_r12')
	{
		document.getElementById('div_3').style.visibility = 'visible';
		document.getElementById('div_4').style.visibility = 'visible';
		document.getElementById('div_5').style.visibility = 'visible';
		document.getElementById('div_6').style.visibility = 'visible';
		document.getElementById('div_7').style.visibility = 'visible';
		document.getElementById('div_10').style.visibility = 'hidden';
		document.getElementById('div_11').style.visibility = 'hidden';
		document.getElementById('div_12').style.visibility = 'hidden';
		document.getElementById('div_13').style.visibility = 'hidden';
		document.getElementById('div_3').style.display = 'inline';
		document.getElementById('div_4').style.display = 'inline';
		document.getElementById('div_5').style.display = 'inline';
		document.getElementById('div_6').style.display = 'inline';
		document.getElementById('div_7').style.display = 'inline';
		document.getElementById('div_10').display = "none";
		document.getElementById('div_11').display = "none";
		document.getElementById('div_12').display = "none";
		document.getElementById('div_13').display = "none";
	}
	else if (document.getElementById('proceso').value=='gerencial')
	{
		document.getElementById('div_3').style.visibility = 'hidden';
		document.getElementById('div_4').style.visibility = 'hidden';
		document.getElementById('div_5').style.visibility = 'hidden';
		document.getElementById('div_6').style.visibility = 'hidden';
		document.getElementById('div_7').style.visibility = 'hidden';
		document.getElementById('div_10').style.visibility = 'visible';
		document.getElementById('div_11').style.visibility = 'visible';
		document.getElementById('div_12').style.visibility = 'visible';
		document.getElementById('div_13').style.visibility = 'hidden';
		document.getElementById('div_3').style.display = "none";
		document.getElementById('div_4').style.display = "none";
		document.getElementById('div_5').style.display = "none";
		document.getElementById('div_6').style.display = "none";
		document.getElementById('div_7').style.display = "none";
		document.getElementById('div_10').style.display = 'inline';
		document.getElementById('div_11').style.display = 'inline';
		document.getElementById('div_12').style.display = 'inline';
		document.getElementById('div_13').display = "none";
	}
	else if (document.getElementById('proceso').value=='myemail')
	{
		document.getElementById('div_3').style.visibility = 'hidden';
		document.getElementById('div_4').style.visibility = 'visible';
		document.getElementById('div_5').style.visibility = 'hidden';
		document.getElementById('div_6').style.visibility = 'hidden';
		document.getElementById('div_7').style.visibility = 'hidden';
		document.getElementById('div_10').style.visibility = 'hidden';
		document.getElementById('div_11').style.visibility = 'hidden';
		document.getElementById('div_12').style.visibility = 'visible';
		document.getElementById('div_13').style.visibility = 'hidden';
		document.getElementById('div_3').style.display = "none";
		document.getElementById('div_4').style.display = "inline";
		document.getElementById('div_5').style.display = "none";
		document.getElementById('div_6').style.display = "none";
		document.getElementById('div_7').style.display = "none";
		document.getElementById('div_10').style.display = 'none';
		document.getElementById('div_11').style.display = 'none';
		document.getElementById('div_12').style.display = 'inline';
		document.getElementById('div_13').display = "none";
	}
	else
	{
		document.getElementById('div_3').style.visibility = 'hidden';
		document.getElementById('div_4').style.visibility = 'visible';
		document.getElementById('div_5').style.visibility = 'visible';
		document.getElementById('div_6').style.visibility = 'hidden';
		document.getElementById('div_7').style.visibility = 'hidden';
		document.getElementById('div_10').style.visibility = 'hidden';
		document.getElementById('div_11').style.visibility = 'hidden';
		document.getElementById('div_12').style.visibility = 'hidden';
		document.getElementById('div_13').style.visibility = 'visible';
		document.getElementById('div_3').style.display = 'none';
		document.getElementById('div_4').style.display = 'inline';
		document.getElementById('div_5').style.display = 'inline';
		document.getElementById('div_6').style.display = 'none';
		document.getElementById('div_7').style.display = 'none';
		document.getElementById('div_10').display = "none";
		document.getElementById('div_11').display = "none";
		document.getElementById('div_12').display = "none";
		document.getElementById('div_13').display = "inline";
	}
}
function verificar_hora()
	{
	hora=document.getElementById('hora').value;
	hora=hora.split(':');
	if(isNaN(hora[0])||hora[0]>24||hora[0]<0||hora[0].length>2||hora[0]=="")
		hora[0]='12';
	if(isNaN(hora[1])||hora[1]>60||hora[1]<0||hora[1].length>2||hora[0]=="")
		hora[1]='00';
	if(isNaN(hora[2])||hora[2]>60||hora[2]<0||hora[2].length>2||hora[0]=="")
		hora[2]='00';
	document.getElementById('hora').value=hora[0]+":"+hora[1]+":"+hora[2];
	}