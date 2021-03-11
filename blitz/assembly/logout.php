<?php
session_start();
session_destroy();
header('Location: http://localhost/groundbraker/blitz/assembly/index.php');
