function AJAX_DisplayProductDetails(ProductNumIn)
{
	var ProductNum = ProductNumIn || 0;
	
	var xmlHttp;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	
	$.blockUI({message: 'Please Wait...',  css: { fontSize: '20px', padding: '6px' }}); 
	
	var POSTData = "";
	var workingPostPage = "/catalogajax/getproductinfo/"+encodeURIComponent(ProductNum);
	
	xmlHttp.onreadystatechange = function () 
	{
		if (xmlHttp.readyState == 4)
		{
			if (xmlHttp.status == 200)
			{
				if(xmlHttp.responseText != "")
				{
					xmlDoc = createXMLDoc(xmlHttp.responseText);
					//first check to see if there are any redirect requests
					var RedirectRequest = xmlDoc.getElementsByTagName('Redirect');
					if(RedirectRequest.length > 0)
					{
						window.location.href = (window.ActiveXObject) ? RedirectRequest[0].text : RedirectRequest[0].textContent;
					}
					else
					{
						//grab the data
						var ProductSection = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('ProductSection')[0].text :  xmlDoc.getElementsByTagName('ProductSection')[0].textContent;
						var ProductCategory = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('ProductCategory')[0].text :  xmlDoc.getElementsByTagName('ProductCategory')[0].textContent;
						var ProductNumber = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('InvtID')[0].text :  xmlDoc.getElementsByTagName('InvtID')[0].textContent;
						var ProductName = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('Descr')[0].text :  xmlDoc.getElementsByTagName('Descr')[0].textContent;
						var ProductDescription = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('LongDescr')[0].text :  xmlDoc.getElementsByTagName('LongDescr')[0].textContent;
						var ProductPrice = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('StkBasePrc')[0].text :  xmlDoc.getElementsByTagName('StkBasePrc')[0].textContent;
						var ProductPriceUnits = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('StkUnit')[0].text :  xmlDoc.getElementsByTagName('StkUnit')[0].textContent;
						var ProductLink = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('Link')[0].text : xmlDoc.getElementsByTagName('Link')[0].textContent;
						
						if(ProductLink != '')
							ProductDescription = '<a href="http://'+ProductLink+'" target="_blank">Product Link</a><br /><br />' + ProductDescription;
						
						//place data in details table
						if(document.getElementById('ProductDetails')){
							document.getElementById('ProductImage').src = "/displayblob/productimage/"+ProductNum;
							document.getElementById('ProductName').innerHTML = ProductName;
							document.getElementById('ProductSection').innerHTML = ProductSection;
							document.getElementById('ProductCategory').innerHTML = ProductCategory;
							document.getElementById('ProductNumber').innerHTML = ProductNumber;
							document.getElementById('ProductDescription').innerHTML = ProductDescription;
							document.getElementById('ProductPrice').innerHTML = "$"+ProductPrice+"/"+ProductPriceUnits;
							//setTimeout(function(){$.unblockUI();},1000);
							setTimeout(function(){$.blockUI({ message: $('#ProductDetails'), overlayCSS: { cursor: 'auto' }, css: { cursor: 'auto', top: '50px', left: '20%', width: '60%', fontSize: '14px', textAlign: 'left'} }); $('.blockOverlay').click($.unblockUI);}, 1000); 
						}
					}		
				}
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