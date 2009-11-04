<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>twitter suggest</title>
<link rel="stylesheet" media="screen" href="../css/barred.css" type="text/css" />
<script type="text/javascript" src="../js/prototype-1.6.0.3.js" ></script>
<script type="text/javascript" src="../js/json2dom.js" ></script>
<script type="text/javascript" src="../js/notification.js" ></script>
</head>
<body>
<label>What's your twitter name? <input id="tweep" type="text" /></label><button onclick="suggest()">Suggest</button>
<div id="faces"></div>
<div id="message_serveur"></div>
<script type="text/javascript">
Notification.area = $('message_serveur');
function suggest() {
	new Notification("Ok. I'm doing dome magic. Patience... I'll tell you when I'm finished.");
	new Ajax.Request("../json/suggest/", {
		method: "get",
		parameters: {name: $('tweep').value},
		onSuccess: function (transport) {
			var tweeps = transport.responseText.evalJSON();
			for (var i=0; i < tweeps.length; i++) {
				var icon = buildom(['P', ['A', {href: "http://twitter.com/users/show.xml?user_id="+tweeps[i].id}, "tweep id "+tweeps[i].id+" has "+tweeps[i].common_friends+" common friends with you"]]);
				$("faces").insert(icon);
			}
			new Notification("Tadaaa!!");
		}
	});
}
</script>
</body>
</html>

