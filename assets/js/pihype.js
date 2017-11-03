(function(){
	// PIHYPE 

	// Handle Alerts
	var pih_alert = document.getElementsByClassName("pihype_alert");

	if(typeof pih_alert != "undefined" && pih_alert.length > 0)
	{
		var len = pih_alert.length;
		var id = 0;
		setInterval(function(){
			if(typeof pih_alert[id] != "undefined")
			{
				pih_alert[id].style.display = "none";
			}
			id++;
		}, 7000);
	}
})();

function hide_alert(id)
{
	var pih_alert = document.getElementsByClassName("alert"+id);
	if(typeof pih_alert != "undefined" && pih_alert.length > 0)
	{
		pih_alert[0].style.display = "none";
	}
}