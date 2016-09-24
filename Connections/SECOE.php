<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_SECOE = "localhost";
$database_SECOE = "SECOE";
$username_SECOE = "moises";
$password_SECOE = "pemex11";
$SECOE = mysql_pconnect($hostname_SECOE, $username_SECOE, $password_SECOE) or trigger_error(mysql_error(),E_USER_ERROR); 
?>