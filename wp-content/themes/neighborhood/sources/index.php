<?php
// disable warnings
if (version_compare(phpversion(), "5.3.0", ">=")  == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE); 

require_once('classes/CMySQL.php'); // include service classes to work with database and comments
require_once('classes/CMyComments.php');

if ($_POST['action'] == 'accept_comment') {
    echo $GLOBALS['MyComments']->acceptComment();
    exit;
}

// prepare a list with photos
$sPhotos = '';
$aItems = $GLOBALS['MySQL']->getAll("SELECT * FROM `s281_photos` ORDER by `when` ASC"); // get photos info
foreach ($aItems as $i => $aItemInfo) {
    $sPhotos .= '<div class="photo"><img src="images/thumb_'.$aItemInfo['filename'].'" id="'.$aItemInfo['id'].'" /><p>'.$aItemInfo['title'].' item</p><i>'.$aItemInfo['description'].'</i></div>';
}

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8" />
    <title>Facebook like photo gallery with comments | Script Tutorials</title>

    <!-- Link styles -->
    <link href="css/main.css" rel="stylesheet" type="text/css" />

    <!-- Link scripts -->
    <script src="https://www.google.com/jsapi"></script>
    <script>
        google.load("jquery", "1.7.1");
    </script>
    <script src="js/script.js"></script>
</head>
<body>
    <header>
        <h2>Facebook like photo gallery with comments</h2>
        <a href="http://www.script-tutorials.com/facebook-like-photo-gallery-with-comments/" class="stuts">Back to original tutorial on <span>Script Tutorials</span></a>
    </header>

    <!-- Container with last photos -->
    <div class="container">
        <h1>Last photos:</h1>
        <?= $sPhotos ?>
    </div>

    <!-- Hidden preview block -->
    <div id="photo_preview" style="display:none">
        <div class="photo_wrp">
            <img class="close" src="images/close.gif" />
            <div style="clear:both"></div>
            <div class="pleft">test1</div>
            <div class="pright">test2</div>
            <div style="clear:both"></div>
        </div>
    </div>
</body></html>