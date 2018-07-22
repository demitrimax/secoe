<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ResEquipos = "localhost";
$database_ResEquipos = "secoe";
$username_ResEquipos = "root";
$password_ResEquipos = "";
$ResEquipos =  mysqli_connect($hostname_ResEquipos, $username_ResEquipos, $password_ResEquipos) or trigger_error(mysqli_error($ResEquipos),E_USER_ERROR); 

function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}
?>