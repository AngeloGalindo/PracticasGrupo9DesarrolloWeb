function generar()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	tipo=document.getElementById('tipo').value;
	str_param="tipo="+tipo+"&generar=1";
	ajax_dynamic_div("POST",'gen_customer_code_tabla.php',str_param,div);
	}
