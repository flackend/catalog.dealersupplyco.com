function makeEXTJSPanel(panelDiv, contentWidth, panelTitle, panelTitleAlignIn, pageLinksIn)
{
	panelTitleAlign = panelTitleAlignIn || "center";
	pageLinks = pageLinksIn || "";
	if(contentWidth > 0 && panelTitle != "")
	{
		if(document.getElementById(panelDiv))
		{
			// display styled panel
			var panelDivObj = document.getElementById(panelDiv);
			panelDivObj.className = 'x-panel';
			
			// form body head
			var xPanelBWRAP = document.createElement("div");
			xPanelBWRAP.className = 'x-panel-bwrap';
			xPanelBWRAP.style.width = (contentWidth+12)+'px';
			xPanelBWRAP.align = 'left';
			
			var xPanelML = document.createElement("div");
			xPanelML.className = 'x-panel-ml';
			
			var xPanelMR = document.createElement("div");
			xPanelMR.className = 'x-panel-mr';
			
			var xPanelMC = document.createElement("div");
			xPanelMC.className = 'x-panel-mc';
			
			var xPanelBody = document.createElement("div");
			xPanelBody.className = 'x-panel-body';
			xPanelBody.style.width = contentWidth+'px';
			
			for(x in panelDivObj.childNodes)
				if(panelDivObj.childNodes[x])
					if(panelDivObj.childNodes[x].innerHTML)
						xPanelBody.appendChild(panelDivObj.childNodes[x]);
			xPanelMC.appendChild(xPanelBody);
			xPanelMR.appendChild(xPanelMC);
			xPanelML.appendChild(xPanelMR);
			xPanelBWRAP.appendChild(xPanelML);
			
			// panel footer
			var xPanelBL = document.createElement("div");
			xPanelBL.className = 'x-panel-bl';
			
			var xPanelBR = document.createElement("div");
			xPanelBR.className = 'x-panel-br';
			
			var xPanelBC = document.createElement("div");
			xPanelBC.className = 'x-panel-bc';
			
			var xPanelFooter = document.createElement("div");
			xPanelFooter.className = 'x-panel-footer';
			
			xPanelBC.appendChild(xPanelFooter);
			xPanelBR.appendChild(xPanelBC);
			xPanelBL.appendChild(xPanelBR);
			xPanelBWRAP.appendChild(xPanelBL);			
			
			// add main bwrap div (contains body and footer) to main div
			panelDivObj.appendChild(xPanelBWRAP);
			
			// panel title
			var xPanelTL = document.createElement("div");
			xPanelTL.className = 'x-panel-tl';
			xPanelTL.style.width = (contentWidth+6)+'px';
			xPanelTL.align = panelTitleAlign;
			
			var xPanelTR = document.createElement("div");
			xPanelTR.className = 'x-panel-tr';
			
			var xPanelTC = document.createElement("div");
			xPanelTC.className = 'x-panel-tc';
			xPanelTC.style.paddingTop = '1px';
			xPanelTC.style.paddingBottom = '1px';
			
			/*var xPanelHeader = document.createElement("div");
			xPanelHeader.className = 'x-panel-header x-unselectable';
			xPanelHeader.style.mozUserSelect = 'none';
			xPanelHeader.style.KhtmlUserSelect = 'none';
			xPanelHeader.unselectable = 'on';*/
			
			var xPanelHeaderTable = document.createElement("table");
			xPanelHeaderTable.className = 'x-panel-header x-unselectable';
			xPanelHeaderTable.style.display = 'inline';
			var xPanelHeaderTbody = document.createElement("tbody");
			var xPanelHeaderTr = document.createElement("tr");
			
			var xPanelHeaderText = document.createElement("span");
			xPanelHeaderText.className = 'x-panel-header-text';
			var xPanelHeaderTdLeft = document.createElement("td");
			xPanelHeaderTdLeft.width = (pageLinks=="") ? contentWidth+'px' : (contentWidth/2)+'px';
			
			var titleText = document.createTextNode(panelTitle);
			xPanelHeaderText.appendChild(titleText);
			xPanelHeaderTdLeft.appendChild(xPanelHeaderText);
			xPanelHeaderTr.appendChild(xPanelHeaderTdLeft);
			
			if(pageLinks != "")
			{
				var xPanelPageLinks = document.createElement("div");
				xPanelPageLinks.className = 'x-panel-header-text';
				xPanelPageLinks.style.display = 'inline';
				xPanelPageLinks.innerHTML = pageLinks;	
				var xPanelHeaderTdRight = document.createElement("td");
				xPanelHeaderTdRight.align = 'right';
				xPanelHeaderTdRight.width = (contentWidth/2)+'px';
				xPanelHeaderTdRight.appendChild(xPanelPageLinks);
				xPanelHeaderTr.appendChild(xPanelHeaderTdRight);	
			}
			
			xPanelHeaderTbody.appendChild(xPanelHeaderTr);
			xPanelHeaderTable.appendChild(xPanelHeaderTbody);
			//xPanelHeader.appendChild(xPanelHeaderTable);
			xPanelTC.appendChild(xPanelHeaderTable);
			xPanelTR.appendChild(xPanelTC);
			xPanelTL.appendChild(xPanelTR);
			
			panelDivObj.insertBefore(xPanelTL, panelDivObj.firstChild);
			
			// re-position form buttons (if exists)
			if(document.getElementById(panelDiv+"Buttons"))
			{
				var xPanelBtnsCT = document.createElement("div");
				xPanelBtnsCT.className = 'x-panel-btns-ct';
				
				var xPanelBtns = document.createElement("div");
				xPanelBtns.className = 'x-panel-btns x-panel-btns-center';
			
				var newT = document.createElement("table");
				var newTB = document.createElement("tbody");
				var newTR = document.createElement("tr");
				var newTD;
				
				buttonEle = document.getElementById(panelDiv+"Buttons");
				var buttonChildren = new Array();
				var buttonChildrenCount = 0;
				getDOMChildElements(buttonEle, buttonChildren, buttonChildrenCount);
				for(x in buttonChildren)
				{
					if(buttonChildren[x].tagName == "TABLE")
					{
						newTD = document.createElement("td");
						newTD.className = 'x-panel-btn-td';
						newTD.appendChild(buttonChildren[x]);
						newTR.appendChild(newTD);
					}
				}
							
				buttonEle.parentNode.removeChild(buttonEle);
				
				var xClear = document.createElement("div");
				xClear.className = 'x-clear';
				
				newTB.appendChild(newTR);
				newT.appendChild(newTB);
				xPanelBtns.appendChild(newT);
				xPanelBtns.appendChild(xClear);
				xPanelBtnsCT.appendChild(xPanelBtns);
				xPanelFooter.appendChild(xPanelBtnsCT);
			}
						
		}
		else
			alert("Panel '"+panelDiv+"' Not Defined");
	}
	else
		alert("You did not provide valid parameters to makeEXTJSPanel()");
}