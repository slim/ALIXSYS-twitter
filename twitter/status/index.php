<html>
<head>
<title>tweet</title>
</head>
<body>
<form action="../update/">
<input name="k" type="hidden" value="<?php print $_GET['k'] ?>"/>
<input name="s" type="hidden" value="<?php print $_GET['s'] ?>"/>
<input name="status" type="text" />
<input type="submit" value="update" />
<p>tip: bookmark this page</p>
</form>
</body>
</html>
