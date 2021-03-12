<html><head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=UTF-8">
<title>Literacy2 Users Admin</title>
</head>
<body bgcolor=ffffff><h1 align=center>Literacy2 ユーザ管理 (サンプル)</h1>

<?php
// create table users (uname text,upword text,email text,utime int);


$db= new PDO('sqlite:users.db');
$Create="Create";
$Verify="Verify";
$Delete="Delete";
$ChangePassword="ChangePassword";
$Email="Email";
if(isset($_POST["uname"])){$uname=$_POST["uname"];}
if(isset($_POST["email"])){$email=$_POST["email"];}
if(isset($_POST["upword"])){$upword=$_POST["upword"];}
$submit="";
if(isset($_POST["submit"])){$submit=$_POST["submit"];}

if($submit==$Create){
    if($uname && $email && $upword){
        $query="SELECT count(*) FROM users WHERE uname='$uname';";
        $m=$db->query($query)->fetch();
        if($m[0]>0)
            print "<h1 align=center>'$uname' already exists.</h1>";
        else {
            $utime=time();
            $md5=md5('$upword');
            $query="INSERT INTO users VALUES
                    ('$uname','$md5','$email',$utime);";
            $db->query($query);
            print $query;
       	}
    }
} else if($submit==$Delete || $submit==$Verify){
    if($uname && $email && $upword){
        $md5=md5('$upword');
	$query="SELECT count(*) FROM users WHERE uname='$uname' 
                AND email='$email' AND upword='$md5';";
        $m=$db->query($query)->fetch();
        if($m[0]>0) {
	    if($submit==$Delete){
		$query="DELETE FROM users WHERE uname='$uname'
                    AND email='$email' AND upword='$md5');"; 
		pg_query($db,$query);
	    } else print "<h1 align=center>Verified.</h1>";
	} else print "<h1 align=center>Incorrect data.</h1>";
    }
} else if($submit==$ChangePassword){
} else if($submit==$Email){
} else {}

print "
<p align=center><table border=1><tr><td>
<table><form method=post>
<tr><td>Name:</td><td><input type=text name=uname></td></tr>
<tr><td>Password:</td><td><input type=password name=upword></td></tr>
<tr><td>E-Mail:</td><td><input type=text name=email></td></tr>
<tr><td colspan=2>
<input type=submit name=submit value=$Create>
<input type=submit name=submit value=$Verify>
<input type=submit name=submit value=$Delete>
<input type=submit name=submit value=$ChangePassword>
<input type=submit name=submit value=$Email>
</td></tr></form></table></td></tr></table></p>
";
?>
</body></html>
