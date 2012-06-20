<?php
require_once "..\maincore.php";
include("../crosstab.php");
require_once THEMES."templates/header.php";
crosstab("lop","mon","max(gvbm)","qlt_phanconggd");
require_once THEMES."templates/footer.php";
?>