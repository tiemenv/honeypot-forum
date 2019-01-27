<?php
require_once "General.php";

Logger::log("user visited our hall of fame!");

//register CSS files
$res = "<head>";
$res .= "<link type='text/css' rel='stylesheet' href='assets/css/forum.css' />";
$res .= "<link rel='stylesheet' type='text/css' href='assets/css/mui.css' />";
$res .= "<link href='assets/css/hub.css' rel='stylesheet' type='text/css' />";
$res .= "</head>";
echo $res;

//TODO: wss zijn we onderhevig aan DB connection DoS?? -> toch weinig aan te doen en buiten project scope dus ¯\_(ツ)_/¯
$db = DbController::getDbInstance();

$res = "<header class='mui-appbar'>";
$res .= "<table width='100%'>";
$res .= "<tr style='vertical-align:middle;'>";
$res .= "<td align='left'><a href='forum.php'>Home</a></td>";
$res .= "<td><a href='feedback.php'>Found a vulnerability?</a></td>";
$res .= "<td class='mui--appbar-height' align='right'>";
$res .= "</td>";
$res .= "</tr>";
$res .= "</table>";
$res .= "</header>";
if(Cookie::decryptCookie()){
$res .= "<button class='mui-btn mui-btn--primary' id='logout'>Logout</button>";
}
echo $res;

//fetch & display hall of fame contents
$hallOfFame = $db->getHallOfFame();
echo "<h1>Groep 10's Hall of Fame</h1>";
if(empty($hallOfFame)){
    echo "No disclosures yet! Want to be the first? <a href='feedback.php'>Submit your disclosure here!</a>";
} else {
    echo "<table class='mui-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Date of report</th>";
    echo "<th>Date of fix</th>";
    echo "<th>Discovered by</th>";
    echo "<th>Description</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($hallOfFame as $entry){
        echo "<tr>";
        echo "<td>".$entry->disclosure_date."</td>";
        echo "<td>".$entry->fix_date."</td>";
        echo "<td>".$entry->name."</td>";
        echo "<td>".$entry->description."</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}



