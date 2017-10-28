(function(){
	// PIHYPE 
	// Handle Alerts
	var pih_alert = document.getElementById("pihype_alert");
	if(typeof pih_alert == "object" && typeof pih_alert != "undefined")
	{
		// message would disapear in 4 seconds
		if(!pih_alert.hasAttribute("data-alert-hide"))
		{
			setTimeout(function(){
				pih_alert.setAttribute("data-alert-hide",true);
				pih_alert.style.height = "0px";
				pih_alert.style.display = "none";
			},4000);
		}
	}

})();