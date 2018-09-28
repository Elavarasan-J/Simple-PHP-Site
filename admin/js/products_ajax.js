// JavaScript Document

function selectProducts(worksheet)
{
	
	product_line = document.getElementById('product_line').value;
	var worksheetid=parseInt(worksheet),workshhetquery_string='';
	if(worksheetid>0)
		workshhetquery_string="&worksheet="+worksheetid;
	
	url="select_productsubline.php?product_line="+product_line+workshhetquery_string;
	//alert(url);
	ajaxpagestock(url,"disp_productsubline");
		
}
function show_worksheet(value)
{
	if(value!='')	{
		url="select_productsubline.php?product_line4worksheet="+value;
		ajaxpagestock(url,"worksheet");
	}
		
}

function show_subline(value)
{
	
	if(value!='')	{
		url="select_productsubline.php?product_line4productsubline="+value;
		ajaxpagestock(url,"productsublines");
	}
		
}



function ajaxpagestock(url, containerid)
{
	var page_request = false
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
	page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){ // if IE
	try {
	page_request = new ActiveXObject("Msxml2.XMLHTTP")
	} 
	catch (e){
	try{
	page_request = new ActiveXObject("Microsoft.XMLHTTP")
	}
	catch (e){}
	}
	}
	else
	return false
	page_request.onreadystatechange=function()
	{
		loadpagestock(page_request, containerid)
	}
	page_request.open('GET', url, true)
	page_request.send(null)
}

function loadpagestock(page_request, containerid)
{
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1))
	{
		if(page_request.responseText!="no")
		 {
		 	document.getElementById(containerid).innerHTML=page_request.responseText;
		 }
		else
		 {
			//document.getElementById(containerid).innerHTML="Stock Not Available"
			
		 }
	}
	//if(page_request.readyState == 1 || page_request.readyState == 2 || page_request.readyState == 3 )
	//document.getElementById(containerid).innerHTML="<br><br><center>loading...</center><br><br><br><br><br>";
}

