function extractNumber(obj, decimalPlaces, allowNegative)
{
	var temp = obj.value;
	
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if (decimalPlaces > 0) {
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if (allowNegative) {
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	
	obj.value = temp;
}
function blockNonNumbers(obj, e, allowDecimal, allowNegative)
{
	var key;
	var isCtrl = false;
	var keychar;
	var reg;
		
	if(window.event) {
		key = e.keyCode;
		isCtrl = window.event.ctrlKey
	}
	else if(e.which) {
		key = e.which;
		isCtrl = e.ctrlKey;
	}
	
	if (isNaN(key)) return true;
	
	keychar = String.fromCharCode(key);
	
	// check for backspace or delete, or if Ctrl was pressed
	if (key == 8 || isCtrl)
	{
		return true;
	}

	reg = /\d/;
	var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
	var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
	
	return isFirstN || isFirstD || reg.test(keychar);
}

function canTransmit(f)
{
  for (var i = 0; i < checkfields.length; i++)
  {
    if(checkfields[i].value == "")
    {
      f.submit_btn.setAttribute("class", "btn btn-primary btn-large disabled");
      f.submit_btn.disabled = true;
      return !f.submit_btn.disabled;
    }
  }
  f.submit_btn.setAttribute("class", "btn btn-primary btn-large");
  f.submit_btn.disabled = false;
  return !f.submit_btn.disabled;
}

function updateprice(f)
{
  if (f.classe.value != "" && f.sezione.value != "")
  {
    var key = f.classe.value.toString() + f.sezione.value;
    if (prezzi.hasOwnProperty(key))
      { var prezzo = prezzi[key]; } 
    else 
      { var prezzo = prezzi[f.classe.value.toString()]; }
    f.totale.value = parseFloat(prezzo).toFixed(2);
    document.getElementById("quota").innerHTML = "<big><strong>&euro; "+f.totale.value+"</strong></big>";
  } else {
    f.totale.value = 0;
    document.getElementById("quota").innerHTML = "<big><strong>&euro; N/A</strong></big>";
  }
  canTransmit(f);
}

function updateViaggio(f)
{
	f.currentViaggio.value = f.viaggio[f.viaggio.selectedIndex].innerHTML;
	if (f.viaggio.value != "")
	{
		f.totale.value = parseFloat(f.viaggio.value).toFixed(2);
		document.getElementById("quota").innerHTML = "<big><strong>&euro; "+f.totale.value+"</strong></big>";
	} else {
		f.totale.value = 0;
		document.getElementById("quota").innerHTML = "<big><strong>&euro; N/A</strong></big>";
	}
	canTransmit(f);
}