<html>
<?php
$db= new PDO('sqlite:Events.db');

$result=$db->query("SELECT * FROM Friends;")->fetchAll(PDO::FETCH_NUM);
$r=Count($result);
$c=Count($result[0]);

print "<p><table border=1 width=80%>";
for($i=0;$i<$r;$i++){
    print "<tr>";
      for($j=0;$j<$c;$j++){
	  print "<td>".$result[$i][$j]."</td>";
      }
    print "</tr>";
}
print "</table></p>";
?>
</html>
