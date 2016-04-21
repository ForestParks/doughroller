<?php

header("X-Robots-Tag: noindex, nofollow", true);

$asin = htmlentities($_GET['asin']);

$link = "http://www.amazon.com/exec/obidos/ASIN/".$asin."/ref=nosim/thedoughrol-20";

header("Location:".$link);

?>