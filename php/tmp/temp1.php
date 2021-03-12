
<html><head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=UTF-8">
<title>Kakeibo</title>
</head>
<body bgcolor=ffffff><h1 align=center>御神籤</h1>
<?php
/*
create table kakeibo (kid int,utime int,category text,title text,
                       income int,expenses int,comments text);
kid: kakeibo id;
*/
date_default_timezone_set('Asia/Tokyo');

$db=new PDO('sqlite:kakeibo.db');
$query='create table if not exists kakeibo (kid int,utime int,category text,title text);';
$db->query($query);

$new_="御神籤を引く";
$del_="結果を消す";
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


if($submit==$new_ && isset($_POST["title"])&& $_POST["title"]!=''){
$fortuneS = array('吉','中吉','小吉','半吉','末吉','末小吉',
'平','凶','小凶','半凶','末凶','大凶','大吉');
    $a=mt_rand()%10;
    if($a==7)$a=11+(mt_rand()%2);
    elseif($a%2!=0)$a=mt_rand()%4+7;
    else $a=mt_rand()%7;
    $category = $fortuneS[$a];
    $title=$_POST["title"];
    $utime=time();
    $m=$db->query("SELECT max(kid) FROM kakeibo")->fetch();
    $kid=$m[0]+1;
    $query="insert into kakeibo values ($kid,$utime,'$category','$title')";
    $db->query($query);
  
}
else print "
<p align=center>名前を入力してください</p>
";



print "
<p align=center><table border=1 width=80%>
<tr bgcolor=bbfbff align=center>
<td>年月日</td><td>御神籤の結果</td><td>名前</td>
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
    $udate=date("Y-m-d",$utime);
    if($i>0)print "
<tr bgcolor=eeeeff align=center><form method=post>
<input type=hidden name=kid value=$kid>
<td>$udate</td><td>$category</td><td>$title</td>
<td><input type=submit name=submit value=$del_></td></form></tr>
";
   else print "
<tr bgcolor=eeeeff align=center><form method=post>
<input type=hidden name=kid value=$kid>
<td>$udate</td><td>$category</td><td>$title</td>
<td></td></form></tr>
";
}

print "
</p></table>
";

		 ?>

<h1 >御神籤</h1>
  <p><?php echo $title; ?> さんの御神籤の結果は「<?php echo $category; ?>」です！</p>
  <tr bgcolor=ffeeff align=center>
<form method=post><input type=hidden name=kid value=$kid>
  <p><td>名前<br><input type=text name=title size=10></td>
<td><input type=submit name=submit value=御神籤を引く></td></form></tr>
</body></html>
