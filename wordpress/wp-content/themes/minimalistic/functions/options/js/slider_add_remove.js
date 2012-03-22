// Functions for admin control panel's slider - Add, Edit, Remove images
function getSelectValue_pages(obj) 
{
	var displayObj = document.getElementById('mini_exclude_header_pages');
	var str = displayObj.value;

	var myRegExp = ','+obj+',';
	var matchPos1 = str.search(myRegExp);

	if(matchPos1 != -1)
		displayObj.value = str.replace(','+obj+',', '');
	else
		displayObj.value = displayObj.value + ',' + obj + ',';
}

function getSelectValue_cats(obj) 
{
	var displayObj = document.getElementById('mini_exclude_categories');
	var str = displayObj.value;

	var myRegExp = ','+obj+',';
	var matchPos1 = str.search(myRegExp);

	if(matchPos1 != -1)
		displayObj.value = str.replace(','+obj+',', '');
	else
		displayObj.value = displayObj.value + ',' + obj + ',';
}

	
function addRowToTable()
{
	var tbl = document.getElementById('demo');
	var lastRow = tbl.rows.length;
	// if there's no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);

	// left cell
	var cellLeft = row.insertCell(0);
	var textNode = document.createTextNode(iteration);
	cellLeft.appendChild(textNode);

	// up cell
	var cellLeft1 = row.insertCell(1);
	newlink = document.createElement('a');
	newlink.setAttribute('href', '#up');
	newlink.setAttribute('rel', 'up');
	newlink.setAttribute('id', 'demo');
	newlink.setAttribute('class', 'control');
	newlink.innerHTML = 'Up';
	cellLeft1.appendChild(newlink);

	// down cell
	var cellLeft1 = row.insertCell(2);
	newlink = document.createElement('a');
	newlink.setAttribute('href', '#down');
	newlink.setAttribute('rel', 'down');
	newlink.setAttribute('class', 'control');
	newlink.setAttribute('id', 'demo');	
	newlink.innerHTML = 'Down';
	cellLeft1.appendChild(newlink);

	// right cell
	var cellRight = row.insertCell(3);
	var el = document.createElement('input');
	el.type = 'text';
	el.name = 'mini_slider_cp_url_' + iteration;
	el.id = 'mini_slider_cp_url_' + iteration;
	//el.size = 40;
	el.setAttribute('style', 'width:457px;');
	el.onkeypress = keyPressTest;
	cellRight.appendChild(el);
  



}
function keyPressTest(e, obj)
{
  var validateChkb = document.getElementById('chkValidateOnKeyPress');
  if (validateChkb.checked) {
    var displayObj = document.getElementById('spanOutput');
    var key;
    if(window.event) {
      key = window.event.keyCode; 
    }
    else if(e.which) {
      key = e.which;
    }
    var objId;
    if (obj != null) {
      objId = obj.id;
    } else {
      objId = this.id;
    }
    displayObj.innerHTML = objId + ' : ' + String.fromCharCode(key);
  }
}

function removeRowFromTable()
{
  var tbl = document.getElementById('demo');
  var lastRow = tbl.rows.length;
  if (lastRow > 1) tbl.deleteRow(lastRow - 1);
}