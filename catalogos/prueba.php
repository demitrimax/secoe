<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<?php
$a = array();
$a[0]='<foo>';
$a[1]="bar";
$a[2]="'baz'";
$a[3]='&blong&';
//'<foo>',"'bar'",'"baz"','&blong&'

echo "Normal: ",  json_encode($a), "\n";
?>
<body>
</body>
</html>