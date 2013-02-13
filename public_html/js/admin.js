/*Ext.apply(Ext.MessageBox, {
    promptPassword: function(){
        var d = Ext.MessageBox.getDialog().body.child('.ext-mb-input').dom;
        Ext.MessageBox.getDialog().on({
            show:{fn:function(){d.type = 'password';},single:true},
            hide:{fn:function(){d.type = 'text';},single:true}
        });
        Ext.MessageBox.prompt.apply(Ext.MessageBox, arguments);
    }
});

Ext.onReady(function(){
    Ext.MessageBox.promptPassword('Login', 'Type password', function(button, value) {
        Ext.MessageBox.alert('Your password...', value);
    });
});
*/

function EXTJS_ChangePasswordPrompt(Title, Message, UserNum, FirstPasswordIn) {
	var FirstPassword = FirstPasswordIn || "";
	if(FirstPassword == ""){
		Ext.onReady(function(){
			Ext.MessageBox.passwordprompt("Change "+Title, Message+":", function(btn,text){
				if(btn == 'ok'){
					EXTJS_ChangePasswordPrompt(Title, Message, UserNum, text);
				}
			})
		});
	}
	else{
		Ext.onReady(function(){
			Ext.MessageBox.passwordprompt("Confirm "+Title, Message+" again:", function(btn,text){
				if(btn == 'ok'){
					AJAX_ChangeAccountPassword(UserNum, FirstPassword, text);
				}
			})
		});
	}
}

function AJAX_ChangeAccountPassword(UserNumIn, PasswordIn, PasswordConfirmIn)
{
	var UserNum = UserNumIn || 0;
	var Password = PasswordIn || "";
	var PasswordConfirm = PasswordConfirmIn || "";
	
	var xmlHttp;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	
	var POSTData = "Password="+Password+"&PasswordConfirm="+PasswordConfirm;
	var workingPostPage = "/adminajax/changepassword/"+encodeURIComponent(UserNum);
	
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
						//grab the success status
						var SuccessTag = xmlDoc.getElementsByTagName('Success');
						Success = (window.ActiveXObject) ? SuccessTag[0].text : SuccessTag[0].textContent;
						//grab the message
						var MessageTag = xmlDoc.getElementsByTagName('Message');
						Message = (window.ActiveXObject) ? MessageTag[0].text : MessageTag[0].textContent;
						if(Message != "") {
							if(Success == "1") {
								EXTJS_DisplaySuccessMsg("Password Change Request", Message);
							}
							else
								EXTJS_DisplayErrorMsg("Password Change Request", Message);	
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

function EXTJS_ShowProduct(Title, Message, ProductNum)
{
	if(!document.forms['ShowProductForm'])
		return;
	Ext.onReady(function(){
		Ext.MessageBox.confirm(Title, Message, function(btn){
			if(btn == 'yes'){
				document.forms['ShowProductForm'].elements['ProductNum'].value = ProductNum;
				document.forms['ShowProductForm'].submit();
			}
		})
	});
}

function EXTJS_HideProduct(Title, Message, ProductNum)
{
	if(!document.forms['HideProductForm'])
		return;
	Ext.onReady(function(){
		Ext.MessageBox.confirm(Title, Message, function(btn){
			if(btn == 'yes'){
				document.forms['HideProductForm'].elements['ProductNum'].value = ProductNum;
				document.forms['HideProductForm'].submit();
			}
		})
	});
}

function AJAX_DisplayEditProductForm(ProductNumIn)
{
	var ProductNum = ProductNumIn || 0;
	
	var xmlHttp;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	
	$.blockUI({message: 'Please Wait...',  css: { fontSize: '20px', padding: '6px' }}); 
	var POSTData = "";
	var workingPostPage = "/adminajax/getproductinfo/"+encodeURIComponent(ProductNum);
	
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
						var Exists = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('Exists')[0].text :  xmlDoc.getElementsByTagName('Exists')[0].textContent;
						if(Exists == "0") {
							alert('The product you are trying to edit no longer exists on the Solomon inventory database.');
							window.location.href = window.location.href;
						}
						else {
							//grab the data
							var SectionNum = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('SectionNum')[0].text :  xmlDoc.getElementsByTagName('SectionNum')[0].textContent;
							var CategoryNum = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('CategoryNum')[0].text :  xmlDoc.getElementsByTagName('CategoryNum')[0].textContent;
							var ProductNumber = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('ProductNumber')[0].text :  xmlDoc.getElementsByTagName('ProductNumber')[0].textContent;
							var ProductName = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('ProductName')[0].text :  xmlDoc.getElementsByTagName('ProductName')[0].textContent;
							var ProductPrice = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('ProductPrice')[0].text : xmlDoc.getElementsByTagName('ProductPrice')[0].textContent;
							var ProductDescription = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('LongDescription')[0].text :  xmlDoc.getElementsByTagName('LongDescription')[0].textContent;
							var IsImage = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('IsImage')[0].text :  xmlDoc.getElementsByTagName('IsImage')[0].textContent;
							var ProductLink = (window.ActiveXObject) ? xmlDoc.getElementsByTagName('Link')[0].text :  xmlDoc.getElementsByTagName('Link')[0].textContent;
							
							//place data in product edit form
							if(document.forms['EditProductForm']){
								document.forms['EditProductForm'].elements['ProductNumber'].value = ProductNum;
								document.getElementById('SectionNumEdit').innerHTML = SectionNum;
								document.getElementById('CategoryNumEdit').innerHTML = CategoryNum;
								document.getElementById('ProductNumberEdit').innerHTML = ProductNumber;
								document.getElementById('ProductNameEdit').innerHTML = ProductName;
								document.getElementById('ProductPriceEdit').innerHTML = ProductPrice;
								document.forms['EditProductForm'].elements['ProductDescription'].value = ProductDescription;
								document.forms['EditProductForm'].elements['ProductLink'].value = ProductLink;
								document.getElementById('ProductImageTD').innerHTML = '<input type="file" name="ProductImage" />';
								if(IsImage == "1") {
									document.getElementById('ProductImageTD').innerHTML = '<img src="/displayblob/productimage/'+ProductNum+'" style="max-width:300px;" /><br /><a href="javascript:;" onclick="EXTJS_DeleteProductImage(\'Delete Product Image\',\'Are you sure you want to remove the image from this product?<br />You will be able to select a new image for this product after doing so.\',\''+ProductNum+'\');">Delete Image</a>';
								}
							}
							
							//change visibilities to only show product edit form stuff
							if(document.getElementById('EditProductH4'))
								document.getElementById('EditProductH4').style.display = '';
							if(document.getElementById('EditProductH4Table'))
								document.getElementById('EditProductH4Table').style.display = '';
							if(document.getElementById('EditProductTable'))
								document.getElementById('EditProductTable').style.display = '';
						}
					}		
				}
				$.unblockUI();
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

function EXTJS_DeleteProductImage(Title, Message, ProductNum)
{
	Ext.onReady(function(){
		Ext.MessageBox.confirm(Title, Message, function(btn){
			if(btn == 'yes'){
				AJAX_DeleteProductImage(ProductNum);
			}
		})
	});
}

function AJAX_DeleteProductImage(ProductNumIn)
{
	var ProductNum = ProductNumIn || 0;
	
	var xmlHttp;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	
	var POSTData = "";
	var workingPostPage = "/adminajax/deleteproductimage/"+encodeURIComponent(ProductNum);
	
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
						//grab the success status
						var SuccessTag = xmlDoc.getElementsByTagName('Success');
						Success = (window.ActiveXObject) ? SuccessTag[0].text : SuccessTag[0].textContent;
						if(Success == "1") {
							if(document.getElementById('ProductImageTD'))
								document.getElementById('ProductImageTD').innerHTML = '<input type="file" name="ProductImage" />';
						}
						else {
							EXTJS_DisplayErrorMsg("Error", "There was an unknown error encountered while trying to delete this image");	
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

function HideProductForms()
{
	if(document.getElementById('EditProductH4'))
		document.getElementById('EditProductH4').style.display = 'none';
	if(document.getElementById('EditProductH4Table'))
		document.getElementById('EditProductH4Table').style.display = 'none';
	if(document.getElementById('EditProductTable'))
		document.getElementById('EditProductTable').style.display = 'none';
	if(document.getElementById('AddNewSection'))
		document.getElementById('AddNewSection').style.display = '';
}