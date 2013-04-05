function canTransmit(f)
{
  var fields = [f.alunno, f.classe, f.sezione, f.emailCompratore];
  for (var i = 0; i < fields.length; i++)
  {
    if(fields[i].value == "")
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
    document.getElementById("quota").innerHTML = "<big><strong>&euro; "+parseFloat(prezzo).toFixed(2)+"</strong></big>";
  } else {
    f.totale.value = 0;
    document.getElementById("quota").innerHTML = "<big><strong>&euro; N/A</strong></big>";
  }
  canTransmit(f);
}