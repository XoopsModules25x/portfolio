<?php

include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

$aboutAdmin = new ModuleAdmin();

echo $aboutAdmin->addNavigation('about.php');
echo $aboutAdmin->renderAbout('xoopsfoundation@gmail.com', false);

include 'admin_footer.php';
