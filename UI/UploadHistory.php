<?php
include 'include/Header/Header.php';
include 'include/HTMLWrapper.php';
include_once 'include/Template.php';

// construct a new Header
$h = new Header("Datenstrukturen",
                "",
                "Florian Lücke",
                "211221492");

$h->setBackURL("index.php")
->setBackTitle("zur Veranstaltung");

// construct a content element for the ability to look at the upload history of a student
$uploadHistory = Template::WithTemplateFile('include/UploadHistory/UploadHistory.template.html');

// construct a content element for sample results
$uploadHistoryResults = Template::WithTemplateFile('include/UploadHistory/Results.template.html');

// wrap all the elements in some HTML and show them on the page
$w = new HTMLWrapper($h, $uploadHistory, $uploadHistoryResults);
$w->set_config_file('include/configs/config_default.json');
$w->show();
?>

