<?php
   if (!empty($_REQUEST['commentpost'])) 
   {
    $id = $_REQUEST['commentpost'];
	require('../../../../wp-load.php');
	//require_once("../../../wp-blog-header.php");
    $time = current_time('mysql');

  $data = array(
    'comment_post_ID' => $_POST['post_id'],
    'comment_author' => $_POST['username'],
    'comment_author_email' =>$_POST['useremail'],
    'comment_author_url' => '',
    'comment_content' =>$_POST['comenttext'],
    'comment_type' => '',
    'comment_parent' => 0,
    'user_id' => 1,
    'comment_author_IP' =>$_POST['useremailip'],
    'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
    'comment_date' => $time,
    'comment_approved' => 1,
      );

//$wpdb->query("insert into wp_v85emj_comments(comment_ID,comment_post_ID,comment_author) values(100,9,'manish bajaj')");
//die;
//echo "<pre>";print_r($data);die;
		if(wp_insert_comment($data))
		{
          echo "success";			
		}
}
?>
