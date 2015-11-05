function fncShowPdf()
	{
	div=document.getElementById("divTable");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >");
	customer_code=document.getElementById('customerCode').value;
	customer_code_rel=document.getElementById('customerCodeRel').value;
	nd_agency_id=fncSelectMultiple(document.getElementById('nd_agency_id'));
	person_type=document.getElementById('person_type').value;
	customer_type_id=fncSelectMultiple(document.getElementById('customer_type_id'));
	strParam="customer_code="+customer_code+"&customer_code_rel="+customer_code_rel+"&nd_agency_id="+nd_agency_id+"&person_type="+person_type+"&customer_type_id="+customer_type_id+"&showPdf=1";
	ajax_dynamic_div("POST",'rel_parent_customer_get.php',strParam,div);
	}

function fncShowTable()//v2
	{
	div=document.getElementById("divTable");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >");
	customer_code=document.getElementById('customerCode').value;
	customer_code_rel=document.getElementById('customerCodeRel').value;
	iddistribution_channel=document.getElementById('iddistribution_channel').value;
	strParam="customer_code="+customer_code+"&customer_code_rel="+customer_code_rel+"&iddistribution_channel="+iddistribution_channel+"&showTable=1";
	ajax_dynamic_div("POST",'rel_parent_customer_get.php',strParam,div);
	}
	
	
function fncShowCsv()
	{
	div=document.getElementById("divTable");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >");
	customer_code=document.getElementById('customerCode').value;
	customer_code_rel=document.getElementById('customerCodeRel').value;
	nd_agency_id=fncSelectMultiple(document.getElementById('nd_agency_id'));
	person_type=document.getElementById('person_type').value;
	customer_type_id=fncSelectMultiple(document.getElementById('customer_type_id'));
	strParam="customer_code="+customer_code+"&customer_code_rel="+customer_code_rel+"&nd_agency_id="+nd_agency_id+"&person_type="+person_type+"&customer_type_id="+customer_type_id+"&showCsv=1";
	ajax_dynamic_div("POST",'rel_parent_customer_get.php',strParam,div);
	}

function fncChangeParent(id,value,x)//v2
	{
	div=document.getElementById("add_"+x);
	$(div).html("a");
	strParam="id="+id+"&value="+value+"&x="+x+"&ParentSearch=1";
	ajax_dynamic_div("POST",'rel_parent_customer_get.php',strParam,div);
	}

function fncMasterBotton(optionValue)
	{
	document.getElementById('divRefresh').style.background = "#FFFFFF";
	
	if(optionValue<0)
		optionValue=document.getElementById('buttonBars').value;
	else
		document.getElementById('buttonBars').value=optionValue;
		
	$('#divRefresh').html("<image src='general_repository/image/ref_48x48.png' width = 48 onclick='fncMasterBotton(0);' alt=\"Click para Mostrar\" title=\"Click para Mostrar\">");
	
	if(optionValue==0)
		{
		document.getElementById('divRefresh').style.background = "#E0F8F7";
		fncShowTable();
		}
	
	}

function fncGetCode(code)
	{
	objName=document.getElementById('objName').value;
	document.getElementById(objName).value=code;
	$('#dialog_jquery').dialog( "close" );
	if ((objName =='customerCode')||(objName =='customerCodeRel'))
		fncMasterBotton(-1);
	else
		{
		objName=objName.split('_');
		x=objName[1];
		id=document.getElementById('id_'+x).value;
		fncChangeParent(id,code,x);
		}
	}

function fncModalParent(id)
	{
	document.getElementById('objName').value=id;
	fncModal("gen_customer_search.php");
	}	
	
function fncChangeVersionSearch(code,x)
	{
	$('#dialog_jquery').dialog( "close" );
	document.getElementById('version_'+x).value=code;
	id=document.getElementById('id_'+x).value;
	fncChangeVersion(id,code,x);
	}
	

function fncDeleteParent(id,rel,x) //v2
	{
	div=document.getElementById("divParent_"+x);
	$(div).html("");
	strParam="id="+rel+"&x="+x+"&deleteParent=1";
	ajax_dynamic_div("POST",'rel_parent_customer_update.php',strParam,div,false);
	fncShowTableRel(id,x);
	}
	
function fncAddParent(id,id2,x) //v2
	{
	div=document.getElementById("divParent_"+x);
	$(div).html("");
	strParam="id="+id+"&id2="+id2+"&x="+x+"&AddParent=1";
	ajax_dynamic_div("POST",'rel_parent_customer_update.php',strParam,div,false);
	div=document.getElementById("add_"+x);
	$(div).html("");
	document.getElementById('parent_'+x).value='';
	fncShowTableRel(id,x);
	}


function fncShowTableRel(id,x)//v2
	{
	div=document.getElementById("divParent_"+x);
	$(div).html("");
	customer_code_rel=document.getElementById('customerCodeRel').value;
	strParam="customer_code_rel="+customer_code_rel+"&x="+x+"&customer_id="+id+"&showTableRel=1";
	ajax_dynamic_div("POST",'rel_parent_customer_get.php',strParam,div,false);
	}
	
	