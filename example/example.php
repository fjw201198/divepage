<?php
require_once("conn.php");
require_once("../divpage.php");
$pageid=$_GET["pageid"];
?>
<div style="width: 1200px; height: 800px; margin: auto;">
<?php    
    echo "<link href='../page.css' rel='stylesheet' type='text/css'>";
    echo "<link href='css/perpage.css' rel='stylesheet' type='text/css'>";
    echo "<script src='../jquery.js'></script>"; //must include jquery
    $sql="select id, title, ico from mvs order by id desc";
    $url="example.php";
    $curpage=$_GET["pageid"];
    if ('"'.$curpage.'"'=="")
        $curpage=0;
    $puts=array("<a class='item' href='../../playing.php?id=","' target=_blank><img src='","' /><div class='title'>","</div></a>");
    $cols=array("id","ico","title");
    $max=8;
    $max2=6;
    $dp=new DivPage($con,$sql,$url,$max,$max2,$puts,$cols);
    $dp->gotopage($curpage,2);
    echo "<script src='../page.js'></script>"; // Must contained this line
    mysql_close($con);
?>
</div>
