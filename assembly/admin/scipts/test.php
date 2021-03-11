<?php
$x=1;
while($x<5)	{
$nameP1 = array("Neo", "Morpheus", "Trinity", "Cypher", "Tank");
$constructor = array_rand($nameP1, 2);
echo $nameP1[$constructor[0]] . "\n";
echo $nameP1[$constructor[1]] . "\n";
$x++;
}
?>