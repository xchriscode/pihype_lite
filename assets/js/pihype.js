(function(){
	// PIHYPE 
	// Handle Alerts
	var pih_alert = document.getElementById("pihype_alert");
	if(typeof pih_alert == "object" && typeof pih_alert != "undefined"
		&& pih_alert != null)
	{
		// message would disapear in 4 seconds
		if(!pih_alert.hasAttribute("data-alert-hide"))
		{
			setTimeout(function(){
				pih_alert.setAttribute("data-alert-hide",true);
				pih_alert.style.display = "none";
			},8000);
		}
	}

	var alertb = document.getElementById("defult_message");
	if(typeof alertb == "object" && alertb != null)
	{
		setTimeout(function(){
				alertb.style.display = "none";
		},8000);
	}

})();

function hide_alert(id)
{
	var alertb = document.getElementById(id);
	alertb.style.display = "none";
}