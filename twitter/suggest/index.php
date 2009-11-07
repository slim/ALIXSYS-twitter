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
<label>What's your twitter names? <textarea id="tweep">
tunisiawatch ,
tkahlaoui ,
mouadhTR ,
ma7moud ,
HAITER ,
sarahbenhamadi ,
Souihli ,
kamal ,
projetefest ,
martinfowler ,
MisterShinigami ,
Marwen ,
lazreg ,
mouselink ,
yassine21 ,
inesTN ,
bendir_man ,
CinemAfricArt ,
demaghmak ,
DavidAbiker ,
ioerror ,
hasan_almustafa ,
chaouka ,
djcheeba ,
rafik_naccache ,
Ziitter ,
jbgme ,
EmirChouchane ,
zizoo ,
sanaoue ,
LASSADI ,
wassimghliss ,
Adil_Mhira ,
chlankboot ,
3amrouch ,
No_hypocrisy ,
MalekElGhali ,
nizarus ,
mouallef ,
RaniaBouhamed ,
moalla ,
merKKur ,
mushon ,
ben_chaouacha ,
hedimoalla ,
ammar404 ,
Barberousse1 ,
bidules ,
Ya_ssou ,
Livingstone68 ,
fidothe ,
TnElection ,
elqudsi ,
malekk ,
tariqkrim ,
tuniscarthage ,
hatembenrais ,
kamAYSAN ,
enmerer ,
linuxscout ,
Myarii ,
PinkTentacle ,
Elizrael ,
MehdiM ,
manixs ,
Falkvinge ,
Hisham_G ,
Sovodov ,
adelmn ,
eon01 ,
Selim_ ,
Matadorrr ,
Gue3bara ,
yssems ,
linuxdeveloper ,
juliansarkSD6 ,
chmounir ,
hamdanih ,
saoud79 ,
ByrsaOnLine ,
VMMoncrieff ,
Ghassi ,
3ayechmelmarsa ,
Houssem_H ,
ahmedkallel ,
Wassimply ,
wa7edekher ,
anisbenayed ,
xklee ,
samyjc ,
zittrain ,
mouins ,
yfd ,
Folletto ,
simbul ,
ElReg ,
commandlinefu ,
funnypurp ,
netprogress ,
oualid
</textarea></label><button onclick="suggest()">Suggest</button>
<div id="faces"></div>
<div id="message_serveur"></div>
<script type="text/javascript">
Notification.area = $('message_serveur');
function suggest() {
	now = new Date;
	$("faces").insert(buildom(['SPAN', {style:'color:white;background:black;'}, now.toString()]));
	new Notification("Ok. I'm doing some magic. Patience... I'll tell you when I'm finished.");
	var tweeps = $('tweep').value.split(',');
	for (var i=0; i < tweeps.length; i++) {
		var tweep = tweeps[i].replace(/^\s+/g,'').replace(/\s+$/g,'');
		new Ajax.Request("../json/suggest/", {
			method: "get",
			parameters: {name: tweep},
			onSuccess: function (transport) {
				var tweeps = transport.responseText.evalJSON();
				for (var i=0; i < tweeps.length; i++) {
					var icon = buildom(['P', ['A', {href: "http://twitter.com/"+tweeps[i].name}, ['IMG', {src: tweeps[i].profile_image_url, width: 32, height: 32}], tweeps[i].full_name+" has "+tweeps[i].common_friends+" common friends with you"]]);
					$("faces").insert(icon);
				}
				now = new Date;
				$("faces").insert(buildom(['SPAN', {style:'color:white;background:black;'}, now.toString()]));
				$("faces").insert(buildom(['HR']));
			}
		});
	}
}
</script>
</body>
</html>

