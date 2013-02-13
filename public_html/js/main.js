function PleaseWait(obj)
{
	var button;
	if(document.getElementById(obj.name+"Submit"))
		button = document.getElementById(obj.name+"Submit");
	else
		button = document.getElementById(obj.id+"Submit");
	if(button.tagName == "TABLE")
	{
		//fix if obj is a EXT JS converted form
		button = button.childNodes[0].childNodes[0].childNodes[1].childNodes[0].childNodes[0];
		button.innerHTML = "Please Wait...";
	}
	else
		button.value = "Please Wait...";
	button.disabled = "disabled";
	return true;
}

function EXTJS_DisplaySuccessMsg(SuccessTitle, SuccessMessage, ReturnFunctionPtrIn) {
	ReturnFunctionPtr = ReturnFunctionPtrIn || function() {};
	Ext.onReady(function(){
		Ext.MessageBox.show(
			{title:SuccessTitle,
			 msg: SuccessMessage,
			 buttons: Ext.MessageBox.OK,
			 animEl: 'GenericSuccessMessage',
			 fn: ReturnFunctionPtr,
			 icon: Ext.MessageBox.INFO}
		);
	});
}

function EXTJS_DisplayErrorMsg(ErrorTitle, ErrorMessage, ReturnFunctionPtrIn) {
	ReturnFunctionPtr = ReturnFunctionPtrIn || function() {};
	Ext.onReady(function(){
		Ext.MessageBox.show(
			{title:ErrorTitle,
			 msg: ErrorMessage,
			 buttons: Ext.MessageBox.OK,
			 animEl: 'GenericErrorMessage',
			 fn: ReturnFunctionPtr,
			 icon: Ext.MessageBox.ERROR}
		);
	});
}

function createXMLDoc(xmlData)
{
	//code for IE
	if (window.ActiveXObject)
	{
		var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async=false;
		xmlDoc.loadXML(xmlData);
	}
	//code for Mozilla, etc.
	else if (document.implementation && document.implementation.createDocument)
	{
		var parser = new DOMParser();
 		var xmlDoc = parser.parseFromString(xmlData,"text/xml");		
	}
	else
	{
		alert('Your browser does not support this script');
	}
	
	return xmlDoc;
}

function AJAX_GetCategoryList(ReturnForm, DefaultValueText, SelectedCategoryIn)
{
	var SelectedCategory = SelectedCategoryIn || "All";
	
	if(!document.forms[ReturnForm])
		return false;
	if(!document.forms[ReturnForm].elements['SectionNum'])
		return false;
	
	document.forms[ReturnForm].elements['CategoryNum'].disabled = true;
	if(document.forms[ReturnForm].elements['CategoryNum'].childNodes[0].tagName == "OPTION")
		document.forms[ReturnForm].elements['CategoryNum'].childNodes[0].innerHTML = "Loading...";
	else
		document.forms[ReturnForm].elements['CategoryNum'].childNodes[1].innerHTML = "Loading...";
	document.getElementById(ReturnForm+'Submit').disabled = true;
	OldSubmitButtonValue = document.getElementById(ReturnForm+'Submit').value;
	document.getElementById(ReturnForm+'Submit').value = "Please Wait...";
	
	var SectionNum = document.forms[ReturnForm].elements['SectionNum'].value;
	
	var xmlHttp;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	
	var POSTData = "";
	var workingPostPage = "/catalogajax/getcategorylist/"+encodeURIComponent(SectionNum);
	
	xmlHttp.onreadystatechange = function () 
	{
		if (xmlHttp.readyState == 4)
		{
			if (xmlHttp.status == 200)
			{
				if(xmlHttp.responseText != "")
				{
					xmlDoc = createXMLDoc(xmlHttp.responseText);
					// first check to see if there are any redirect requests
					var RedirectRequest = xmlDoc.getElementsByTagName('Redirect');
					if(RedirectRequest.length > 0)
					{
						window.location.href = (window.ActiveXObject) ? RedirectRequest[0].text : RedirectRequest[0].textContent;
					}
					else
					{
						var OptionArray = new Array();
						var CategoryList = xmlDoc.getElementsByTagName('ProductCategory');
						if(CategoryList.length < 1) {
							OptionArray[0] = document.createElement('option');
							OptionArray[0].innerHTML = 'No Categories Exist';
							OptionArray[0].value = '0';
							OptionArray[0].style.fontWeight = 'bold';
						}
						else {
							OptionArray[0] = document.createElement('option');
							OptionArray[0].innerHTML = DefaultValueText;
							OptionArray[0].value = DefaultValueText;
							OptionArray[0].style.fontWeight = 'bold';
							document.forms[ReturnForm].elements['CategoryNum'].disabled = false;
						}
						
						for(var x=0; x<CategoryList.length; x++) {
							var CategoryID = (window.ActiveXObject) ? CategoryList[x].getElementsByTagName('ID')[0].text : CategoryList[x].getElementsByTagName('ID')[0].textContent;
							var CategoryName = (window.ActiveXObject) ? CategoryList[x].getElementsByTagName('Name')[0].text : CategoryList[x].getElementsByTagName('Name')[0].textContent;
							OptionArray[x+1] = document.createElement('option');
							OptionArray[x+1].innerHTML = CategoryName;
							OptionArray[x+1].value = CategoryID;
							if(SelectedCategory==CategoryID)
								OptionArray[x+1].selected = true;
						}
						document.forms[ReturnForm].elements['CategoryNum'].innerHTML = '';
						for(var i=0; i<OptionArray.length; i++) {
							document.forms[ReturnForm].elements['CategoryNum'].appendChild(OptionArray[i]);
						}
					}
				}
				else {
					var DefaultOption = document.createElement('option');
					DefaultOption.innerHTML = '--Select a Section--';
					DefaultOption.value = '0';
					DefaultOption.style.fontWeight = 'bold';
					document.forms[ReturnForm].elements['CategoryNum'].innerHTML = '';
					document.forms[ReturnForm].elements['CategoryNum'].appendChild(DefaultOption)
				}
				document.getElementById(ReturnForm+'Submit').value = OldSubmitButtonValue;
				document.getElementById(ReturnForm+'Submit').disabled = false;
			}
			else if (xmlHttp.status == 404)
			{
				alert("We're sorry, the page you requested could not be loaded at this time.  Error Code: AJ404");
			}
			else
			{
				alert("An unexpected error has been encountered.  Error Code: "+xmlHttp.status);	
			}
		}
	};
	
	xmlHttp.open("POST",workingPostPage,true);
	xmlHttp.setRequestHeader("Method", "POST " + self.location + " HTTP/1.1");
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.send(POSTData);
}

function ClearSearchBox()
{
	if(document.forms['CatalogSearchForm']) {
		document.forms['CatalogSearchForm'].elements['SectionNum'].value = 'All';
		document.forms['CatalogSearchForm'].elements['CategoryNum'].selectedIndex = 0;
		document.forms['CatalogSearchForm'].elements['ProductNum'].value = '';
		document.forms['CatalogSearchForm'].elements['DescriptionText'].value = '';
		document.forms['CatalogSearchForm'].submit();
	}
}