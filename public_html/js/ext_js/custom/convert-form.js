function getDOMChildElements(baseobj, fieldArray, arrayCount)
{
	for(x in baseobj.childNodes)
	{
		fieldArray[arrayCount] = baseobj.childNodes[x];
		arrayCount++;
		arrayCount = getDOMChildElements(baseobj.childNodes[x], fieldArray, arrayCount);
	}
	return arrayCount;
}

function convertFormToEXTJS(formId)
{
	formObj = document.getElementById(formId);	
	var action = (formObj.action!="") ? formObj.action : window.location.href;
	var frm = new Ext.form.BasicForm(formId, {url:action, standardSubmit:true, style:'display:inline'});
	formObj.style.display = "inline";

	//var fields = frm.getValues();
	var formChildItems = new Array();
	getDOMChildElements(formObj, formChildItems, 0);
	fields = new Array();
	fieldsCount = 0;
	for(x in formChildItems)
	{
		if(formChildItems[x].id)
		{
			fields[fieldsCount] = formChildItems[x].id;
			fieldsCount++;
		}
	}
	for (key in fields)
	{
		var elem = Ext.get(fields[key]);
		if(elem && elem.is('input'))
		{
			var inputType = elem.getAttributeNS("input","type");
			if(inputType == 'text')
			{
				var tb = new Ext.form.TextField({
					applyTo:elem.dom.name
				});
			}
			else if(inputType == 'password')
			{
				var tb = new Ext.form.TextField({
					type:'password',
					applyTo:elem.dom.name
				});
			}
			else if(inputType == 'file')
			{
				var tb = new Ext.form.TextField({
					type:'file',
					applyTo:elem.dom.name
				});
			}
			else if(inputType == 'hidden')
			{
				var tb = new Ext.form.TextField({
					type:'hidden',
					applyTo:elem.dom.name
				});
			}
			else if(inputType == 'submit')
			{
				document.getElementById(elem.dom.id).style.display = 'none';
				var origid = elem.dom.id;
				var bindid = elem.dom.id+"Bind";
				document.getElementById(elem.dom.id).id = bindid;
				var bn = new Ext.Button({
					formBind: true,
					style:'display:inline;',
		            scope: frm,
		            handler: frm.submit,
		            id:origid,
		            text:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+elem.dom.value+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		            type:'submit',
					applyTo:elem.dom.id
				});
				bn.on('click', function(){PleaseWait(frm);});
				//, this, {single: true, delay: 100, forumId: 4}
				//add functionality to submit form on enter
				var nav = new Ext.KeyNav(frm.getEl(), {
				  'enter': function(e) {
				  	PleaseWait(frm);
				    this.submit();
				  },
				  'scope': frm
				});
				document.getElementById(origid).style.paddingRight = "2px";
				document.getElementById(origid).style.paddingLeft = "2px";
			}
			else if(inputType == 'reset')
			{
				document.getElementById(elem.dom.id).style.display = 'none';
				var origid = elem.dom.id;
				var bindid = elem.dom.id+"Bind";
				document.getElementById(elem.dom.id).id = bindid;
				var bn = new Ext.Button({
					formBind: true,
					style:'display:inline;',
		            scope: frm,
		            handler: frm.reset,
		            id:origid,
		            text:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+elem.dom.value+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		            type:'reset',
					applyTo:elem.dom.id
				});
				document.getElementById(origid).style.paddingRight = "2px";
				document.getElementById(origid).style.paddingLeft = "2px";
			}
			else if(inputType == 'button')
			{
				document.getElementById(elem.dom.id).style.display = 'none';
				var origid = elem.dom.id;
				var bindid = elem.dom.id+"Bind";
				var origevent = String(document.getElementById(origid).onclick);
				var copyevent = false;
				var origeventcommand = "";
				for(x=0; x<origevent.length; x++)
				{
					if(origevent.charAt(x) == "}") copyevent = false;	
					if(copyevent) origeventcommand += origevent.charAt(x);
					if(origevent.charAt(x) == "{") copyevent = true;			
				}
				document.getElementById(elem.dom.id).id = bindid;
				var bn = new Ext.Button({
					formBind: true,
					style:'display:inline;',
		            scope: frm,
		            handler: function(){eval(origeventcommand);},
		            id:origid,
		            text:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+elem.dom.value+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		            type:'button',
					applyTo:elem.dom.id
				});
				document.getElementById(origid).style.paddingRight = "2px";
				document.getElementById(origid).style.paddingLeft = "2px";
			}
		}
		else if(elem && elem.is('select'))
		{
			var cb = new Ext.form.ComboBox({
				transform:elem.dom.name,
				typeAhead: true,
				triggerAction: 'all',
				width: elem.getWidth()-25,
				forceSelection:true
			});
			// fix dropdown arrow in IE
			if(navigator.appName == "Microsoft Internet Explorer")
				document.getElementById(elem.dom.id).parentNode.childNodes[2].style.marginTop = "-1px";
		}
		else if(elem && elem.is('label'))
		{
			var lb = new Ext.form.Label({
				applyTo:elem.dom.id,
				style:"padding-top:3px;",
				cls:"x-form-item label"
			});
		}
		/*else if (elem && elem.hasClass('date-picker'))
		{
			var df = new Ext.form.DateField({
			    format:'m/d/Y'
			});
			df.applyTo(elem.dom.name);
		}*/
		
		/*if (elem && elem.hasClass('resizeable'))
		{
            var dwrapped = new Ext.Resizable(elem, {
                wrap:true,
                pinned:true,
                width:400,
                height:150,
                minWidth:200,
                minHeight: 50,
                dynamic: true
            });
		}*/
	}
}