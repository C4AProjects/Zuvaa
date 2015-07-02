<?php

require_once('../../../../wp-load.php');
$_FILES = $_FILES['file-0'];
parse_str($_POST['form'], $get_array);
$_POST = $get_array;
$message = array();
$_FILES['image_file'] = $_FILES;
$upload_dir = wp_upload_dir();
//print_r($upload_dir);
$abs_path = $upload_dir['basedir'];

function uploadImageFile() { // Note: GD library is required for this function
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $iWidth = $iHeight = 200; // desired image result dimensions
        $iJpgQuality = 90;

        if ($_FILES) {

            // if no errors and size less than 250kb
            if (!$_FILES['image_file']['error'] && $_FILES['image_file']['size'] < 1250 * 2500) {
                if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                    global $abs_path;
                    $abs_path;
                    // new unique filename
                    $sTempFileName = $abs_path . '/' . md5(time() . rand());

                    // move uploaded file into cache folder
                    move_uploaded_file($_FILES['image_file']['tmp_name'], $sTempFileName);

                    // change file permission to 644
                    @chmod($sTempFileName, 0644);
                    if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                        $aSize = getimagesize($sTempFileName); // try to obtain image info
                        if (!$aSize) {
                            @unlink($sTempFileName);
                            return;
                        }
                        // check for image type
                        switch ($aSize[2]) {
                            case IMAGETYPE_JPEG:
                                $sExt = '.jpg';
                                // create a new image from file 
                                $vImg = @imagecreatefromjpeg($sTempFileName);
                                break;
                            /* case IMAGETYPE_GIF:
                              $sExt = '.gif';

                              // create a new image from file
                              $vImg = @imagecreatefromgif($sTempFileName);
                              break; */
                            case IMAGETYPE_PNG:
                                $sExt = '.png';

                                // create a new image from file 
                                $vImg = @imagecreatefrompng($sTempFileName);
                                break;
                            default:
                                @unlink($sTempFileName);
                                return;
                        }

                        // create a new true color image
                        $vDstImg = @imagecreatetruecolor($iWidth, $iHeight);

                        // copy and resize part of an image with resampling
                        imagecopyresampled($vDstImg, $vImg, 0, 0, (int) $_POST['x1'], (int) $_POST['y1'], $iWidth, $iHeight, (int) $_POST['w'], (int) $_POST['h']);

                        // define a result image filename
                        $sResultFileName = $sTempFileName . $sExt;

                        // output image to file
                        imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                        @unlink($sTempFileName);

                        return $sResultFileName;
                    }
                }
            }
        }
    }
}

if (!empty($_POST)) {

    $sImage = uploadImageFile();
    $explode_array = explode('/', $sImage);
    $file_name = end($explode_array);
    //echo $abs_path . '/' . $file_name;
    $upload_dir['url'] . '/' . $file_name;
    // Create post object
    $my_post = array(
        'post_title' => $_POST['post_title'],
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    );
    // Insert the post into the database
    $post_id = wp_insert_post($my_post);
    $filename = $abs_path . '/' . $file_name;

// Check the type of tile. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype(basename($filename), null);

// Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

// Prepare an array of post data for the attachment.
    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
        'post_mime_type' => $filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content' => '',
        'post_status' => 'inherit'
    );

// Insert the attachment.
    $attach_id = wp_insert_attachment($attachment, $filename);
    set_post_thumbnail($post_id, $attach_id);
}
