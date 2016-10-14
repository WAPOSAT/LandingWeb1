<?php

 function ObtenerIP()
{
if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
$ip = getenv("HTTP_CLIENT_IP");
else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
$ip = getenv("REMOTE_ADDR");
else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
$ip = $_SERVER['REMOTE_ADDR'];
else
$ip = "IP desconocida";
return($ip);
}

function display_children($parent, $level,$path=null,$url=null) {

$result = mysql_query("SELECT a.id, a.`meta-title` as label, a.url as link, Deriv1.Count FROM `modulo` a  LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count FROM `modulo` where menu=1 GROUP BY parent) Deriv1 ON a.id = Deriv1.parent WHERE menu=1 and a.parent=" . $parent.' order by array');

 if ($parent==0) {  echo "<ul class=menu>"; }
 else   {  echo "<ul>";     }

 while ($row = mysql_fetch_assoc($result)) {
     if ($row['Count'] > 0) {
         echo "<li><a class=\"js-as\" >" . utf8_encode($row['label']) . "</a>";
   display_children($row['id'], $level + 1);
   echo "</li>";
     } elseif ($row['Count']==0) {
         echo "<li><a  class=\"js-as\"  href='".$path.$row['link'] . "'>" .utf8_encode($row['label']) . "</a></li>";
     } else;
 }
 echo "</ul>";
}


?>
