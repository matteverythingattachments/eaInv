<?php
session_start();
include('../admin/scripts/auth_check.php');
include('../admin/scripts/components.php');
include('../components/html_header.php');
?>


<body>
<?php echo $nav_menu;?>
<ul>
	<li><a href="add_parts.php">Add Parts</a></li>
  <li><a href="parts_list.php">View Parts</a></li>
	<li><a href="inventory_levels.php">Check Parts Inventory</a></li>
	<li><a href="part_search.php">Search For Parts</a></li>
  <li><a href="parts_by_location.php">Parts By Location</a></li>
	<li><a href="identifier.php">Parts Without Pictures</a></li>
  <li><a href="purchased_parts.php">Purchased Parts</a></li>
  <li><a href="bolts.php">Bolts</a></li>
</ul>
</body>
</html>