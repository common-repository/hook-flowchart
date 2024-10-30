jQuery(document).ready(function () {
  jQuery("#wp-admin-bar-hook-flowchart a").click(function () {
	jQuery(".hookr-flowchart").show();
	var i = 0;
	//Parse every hook
	[].forEach.call(document.querySelectorAll('.mermaid-noise'), function (el) {
	  i++;
	  //Get the markdown text
	  var convert = el.innerHTML;
	  convert = convert.replace(/\[n\]/g, "\n");
	  //Get the hook name
	  var hook = convert.slice(15).split(']');
	  hook = hook[0];

	  document.querySelector('.hookr-flowchart').innerHTML += "<h3 id='" + hook + "'>Hook " + hook + "</h3>";
	  document.querySelector('.buttons').innerHTML += "<a href='#" + hook + "' class='button'>" + hook + "</a><input type='checkbox' data-hook='" + hook + "' data-id='" + i + "' alt='Hide' title='Hide' class='hide-hook' style='margin-top:3px'/>";
	  document.querySelector('.hookr-flowchart').innerHTML += "<div class='mermaid-" + i + "'>" + convert + "</div><hr data-id='" + i + "'>";
	  //Generate the flowchart
	  mermaid.init({
		logLevel: 0,
		flowchart: {
		  useMaxWidth: false
		}}, ".mermaid-" + i);
	});

	jQuery("#wpbody,#wpfooter").hide();
	document.querySelector('.hookr-flowchart').scrollIntoView({block: 'start', behavior: 'smooth'});
	//Go to top
	document.querySelector('button.gotop').addEventListener('click', function () {
	  document.body.scrollTop = document.documentElement.scrollTop = 0;
	});
	//Hide system for hooks
	[].forEach.call(document.querySelectorAll('.hide-hook'), function (e) {
	  e.addEventListener('click', function () {
		if (e.checked) {
		  document.querySelector(".mermaid-" + e.dataset.id).style.display = 'none';
		  document.querySelector("#" + e.dataset.hook).style.display = 'none';
		  document.querySelector("hr[data-id='" + e.dataset.id + "']").style.display = 'none';
		} else {
		  document.querySelector(".mermaid-" + e.dataset.id).style.display = 'block';
		  document.querySelector("#" + e.dataset.hook).style.display = 'block';
		  document.querySelector("hr[data-id='" + e.dataset.id + "']").style.display = 'block';
		}
	  });
	});
  });
});
