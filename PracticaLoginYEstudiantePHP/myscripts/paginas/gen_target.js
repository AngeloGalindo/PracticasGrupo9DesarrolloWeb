function show_date()
	{//modificado el rango de fechas
	validate_date(document.forms['example']);
	}
	
function fncPromotion()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="insertPromotion=1";
	ajax_dynamic_div("POST",'gen_target_get.php',str_param,div);
	}

function fncTarget()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	str_param="day1="+day1+"&day2="+day2+"&showTarget=1";
	ajax_dynamic_div("POST",'gen_target_get.php',str_param,div);
	}
	
function fncPrint()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'> Cargando");
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	str_param="day1="+day1+"&day2="+day2+"&printTarget=1";
	ajax_dynamic_div("POST",'gen_target_get.php',str_param,div,false);
	}
function fncChangeField(id,value,field)
	{
	div=document.getElementById("divUpdate");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >Grabando Cambio...");
	strParam="id="+id+"&value="+value+"&field="+field+"&changeField=1";
	ajax_dynamic_div("POST",'gen_target_update.php',strParam,div,false);
	}

function fncChangeFieldX(x,value,field)
	{
	id=document.getElementById('idpromotion_'+x).value;
	if(id==0)
		{
		id=document.getElementById('idaddress_'+x).value;
		div=document.getElementById("divUpdate");
		$(div).html("<img src='general_repository/image/loading.gif' align='center' >Grabando Cambio...");
		strParam="id="+id+"&x="+x+"&insertPromotion=1";
		ajax_dynamic_div("POST",'gen_target_update.php',strParam,div,false);
		id=document.getElementById('idpromotion_'+x).value;
		}
	fncChangeField(id,value,field);
	}

function fncChangeWeek()
	{
	x=document.getElementById('week').value;
	arrayDay1={'0':'02/01/2013','1':'19/06/2013','2':'26/06/2013','3':'03/07/2013','4':'10/07/2013','5':'17/07/2013','6':'24/07/2013','7':'31/07/2013','8':'07/08/2013','9':'14/08/2013','10':'21/08/2013','11':'28/08/2013','12':'04/09/2013','13':'11/09/2013','14':'18/09/2013','15':'25/09/2013','16':'02/10/2013','17':'09/10/2013','18':'16/10/2013','19':'23/10/2013','20':'30/10/2013','21':'06/11/2013','22':'13/11/2013','23':'20/11/2013','24':'27/11/2013'};
	arrayDay2={'0':'08/01/2013','1':'25/06/2013','2':'02/07/2013','3':'09/07/2013','4':'16/07/2013','5':'23/07/2013','6':'30/07/2013','7':'06/08/2013','8':'13/08/2013','9':'20/08/2013','10':'27/08/2013','11':'03/09/2013','12':'10/09/2013','13':'17/09/2013','14':'24/09/2013','15':'01/10/2013','16':'08/10/2013','17':'15/10/2013','18':'22/10/2013','19':'29/10/2013','20':'05/11/2013','21':'12/11/2013','22':'19/11/2013','23':'26/11/2013','24':'03/12/2013'};
	document.getElementById('date1').value=arrayDay1[x];
	document.getElementById('date2').value=arrayDay2[x];
	fncTarget();
	}
	
function fncCheckAll()
	{
	lastX=document.getElementById('lastX').value;
	value=document.getElementById('check_all').checked;
	for(x=0;x<lastX;x++)
		{
		if (document.getElementById('idtarget_'+x).value==0)
			document.getElementById('check_'+x).checked=value;
		}
	}
	
function fncNewTarget()
	{
	lastX=document.getElementById('lastX').value;
	idpromotion='';reward='';sales='';sales_target='';refund='';maximun_refund='';evaluation='';value_default='';
	arrayEvaluation={'0':'2','1':'1'};
	y=0;
	for(x=0;x<lastX;x++)
		{
		if(document.getElementById('check_'+x).checked)
			{
			idpromotion+=document.getElementById('idpromotion_'+x).value+",";
			reward+=document.getElementById('reward_'+x).value+",";
			sales+=document.getElementById('sales_'+x).value+",";
			sales_target+=document.getElementById('sales_target_'+x).value+",";
			refund+=document.getElementById('refund_'+x).value+",";
			maximun_refund+=document.getElementById('maximun_refund_'+x).value+",";
			value_default+=document.getElementById('value_default_'+x).value+",";
			evaluation+=arrayEvaluation[document.getElementById('evaluation_'+x).value]+",";
			y++;
			}
		}
	if(y>0)
		{
		idpromotion=idpromotion.substring(0, idpromotion.length-1);
		reward=reward.substring(0, reward.length-1);
		day1=document.getElementById('date1').value;
		day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
		day2=document.getElementById('date2').value;
		day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		div=document.getElementById("divUpdate");
		$(div).html("<img src='general_repository/image/loading.gif' align='center' >Grabando Cambio...");
		strParam="day1="+day1+"&day2="+day2+"&idpromotion="+idpromotion+"&reward="+reward+"&value_default="+value_default+"&sales="+sales+"&sales_target="+sales_target+"&refund="+refund+"&maximun_refund="+maximun_refund+"&evaluation="+evaluation+"&insertTarget=1";
		ajax_dynamic_div("POST",'gen_target_update.php',strParam,div);
		fncTarget();
		}
	else 
		alert("No se selecciono ningun cliente para crear nuevos vales");
	}
	
function fncDeleteTarget(id)
	{
	div=document.getElementById("divUpdate");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >Eliminando Cambio...");
	strParam="id="+id+"&deleteTarget=1";
	ajax_dynamic_div("POST",'gen_target_update.php',strParam,div);
	fncTarget();
	}