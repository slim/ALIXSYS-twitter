<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>twitter suggest</title>
<script type="text/javascript" src="../js/prototype-1.6.0.3.js" ></script>
<script type="text/javascript" src="../js/json2dom.js" ></script>
<script type="text/javascript" src="../js/json2dom.js" ></script>
</head>
<body>
<label>What's your twitter name? <input id="tweep" type="text" /></label><button onclick="suggest()">Suggest</button>
<div id="faces"></div>
<script type="text/javascript">
function suggest() {
	new Ajax.Request("../json/suggest/", {
		method: "get",
		parameters: {name: $('tweep').value},
		onSuccess: function (transport) {
			var tweeps = transport.responseText.evalJSON();
			for (var i=0; i < tweeps.length; i++) {
				var icon = buildom(['P', ['A', {href: "http://twitter.com/users/show.xml?user_id="+tweeps[i].id}, "tweep id "+tweeps[i].id]]);
				$("faces").insert(icon);
			}
		}
	});
}
</script>
</body>
</html>

