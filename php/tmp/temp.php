<html><head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=UTF-8">
<title>Kakeibo</title>
</head>
<body bgcolor=ffffff><h1 align=center>家計簿 (サンプル)</h1>
<?php
/*
create table kakeibo (kid int,utime int,category text,title text,
                       income int,expenses int,comments text);
kid: kakeibo id;
*/
date_default_timezone_set('Asia/Tokyo');

$db=new PDO('sqlite:kakeibo.db');
$query='create table if not exists kakeibo (kid int,utime int,category text,title text,income int,expenses int,comments text);';
$db->query($query);

$new_="入力";
$del_="削除";
$kid=0;

if(isset($_POST["submit"])){
   $submit=$_POST["submit"];
} else {
   $submit="";
}

if($submit==$del_){
    $kid=$_POST["kid"];
    $db->query("delete FROM kakeibo where kid=$kid");
}
$submit=$new_;

if($submit==$new_ && isset($_POST["title"]) && 
   (isset($_POST["income"]) || isset($_POST["expenses"]))){
   if($_POST["income"]>0 || $_POST["expenses"]>0){
    $category=$_POST["category"];
    if(isset($_POST["new_category"])){
        if(strlen($_POST["new_category"])) $category=$_POST["new_category"];
}
$fortuneS = array('吉','中吉','小吉','半吉','末吉','末小吉',
'平','凶','小凶','半凶','末凶','大凶','大吉');
    $title=$_POST["category"];
    $a=mt_rand()/10;
    if($a==7)$a=11+(mt_rand()%2);
    elseif($a%2!=0)$a=mt_rand()%4+7;
    else $a=mt_rand()%7;
    $ofortunesS = $fortuneS[$a];
    $title=$_POST["title"];
    $income=$_POST["income"];
    $expenses=$_POST["expenses"];
    $comments=$_POST["comments"];
    $utime=time();
    $m=$db->query("SELECT max(kid) FROM kakeibo")->fetch();
    $kid=$m[0]+1;
    $query="insert into kakeibo values ($kid,$utime,'$category','$title',
$income,$expenses,'$comments')";
    $db->query($query);
  }
}


$catlist=$db->query("SELECT distinct category FROM kakeibo")->fetchAll(PDO::FETCH_NUM);
$rows=Count($catlist);
$categorylist="<option>未分類";
for($i=0;$i<$rows;$i++){
    $categorylist.="<option>";
    $categorylist.=$catlist[$i][0];
}

print "
<p align=center><table border=1 width=80%>
<tr bgcolor=bbfbff align=center>
<td>年月日</td><td>分類</td><td>タイトル</td>
<td>操作</td></tr>
";

$result=$db->query("SELECT * FROM kakeibo order by utime;")->fetchAll();
$rows=Count($result);
$sum=0;
for($i=0;$i<$rows;$i++){
	      $row=$result[$i];      
    $kid=$row["kid"];
    $utime=$row["utime"];
    $category=$row["category"];
    $title=$row["title"];
    $comments=$row["comments"];
    $udate=date("Y-m-d",$utime);
    print "
<tr bgcolor=eeeeff align=center><form method=post>
<input type=hidden name=kid value=$kid>
<td>$udate</td><td>$category</td><td>$title</td>
<td><input type=submit name=submit value=$del_></td></form></tr>
";
}

print "
<tr bgcolor=ffeeff align=center>
<form method=post><input type=hidden name=kid value=$kid>
<td></td>
<td></td>
<td>name<br><input type=text name=title size=10></td>
<td><input type=submit name=submit value=$submit></td>
</form></tr></table></p>
";

?>
</body></html>
