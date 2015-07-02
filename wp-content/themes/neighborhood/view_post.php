<?php
/*
Template Name: post helper
*/
if (empty($_REQUEST['id'])) 
{
  echo 'An Error Has Occoured!';
  die();
}
global $post_id;
$post_id = convert_uudecode(base64_decode($_REQUEST['id']));


 require_once('../../../wp-load.php'); 
 
 
//require_once("../../../wp-blog-header.php");
$post_data = get_post($post_id,ARRAY_A);
//echo "<pre>";print_r($post_data);die;
//$postdate=$post_data['post_date'];  // post date
$postcontent=$post_data['post_content'];
$length = 150; //modify for desired width
if (strlen($postcontent) <= $length) {
   $stringpostcontent= $postcontent; //do nothing
} else {
   $postcontent = substr($postcontent, 0, strpos(wordwrap($postcontent, $length), "\n"));
}

//$next_post = get_next_post();
//$previous_post = get_previous_post();

 //$next_post_id =$next_post->ID;
 //$previous_post_id = $previous_post->ID;
//echo $postcontent;die;
 
$humantext=human_time_diff(get_the_time('U',$post_id), current_time('timestamp')) . ' ago'; 

 if (has_post_thumbnail($post_id)):
 $image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'single-post-thumbnail' ); 
 $image=$image[0]; 
 endif;
  $post_author_id = get_post_field( 'post_author', $post_id);
  
  //echo $userpro->permalink($user_id)."<br/>";
  
?>
<div>
 <img src="<?php echo $image; ?>" class="userimage"/>
 <?php //echo get_avatar( $user_id,80); ?>
 <img class="avatar" src="<?php echo $_POST['userimage'];?>"/>
 <div class="postuser"><?php echo $_POST['postusername']; ?></div>
 <div class="postuserurl"><?php echo $_POST['postuserurl']; ?></div>
 <div class="postcontent"><?php echo $postcontent; ?></div>
 <div class="humantext"><?php echo $humantext;  
 ?> </div>
 <?php
 if (function_exists( 'lip_love_it_link' )) {
						echo "<div class='love'>". lip_love_it_link($post_id, '<i class="fa-heart"></i>', '<i class="fa-heart"></i>', false)."</div>";
						} 
 ?>
 
 <div class="commentportion">
  <div class="comments" id="comments">
                        <h2>Comments</h2>
                        <div id="comments_warning1" style="display:none">Don`t forget to fill both fields (Name and Comment)</div>
                        <div id="comments_warning2" style="display:none">You can't post more than one comment per 10 minutes (spam protection)</div>
                        <div id="comments_list">
                           <?php
								global $wpdb;
								$myrows = $wpdb->get_results( "SELECT * FROM wp_v85emj_comments where comment_post_ID=$post_id",ARRAY_A);
								if(count($myrows)==0)
								{
								  echo '<div class="comment" style="width:100%;">';
								   echo "<p>No Comments</p>";
								  echo '</div>';  	
								} 
								for($k=0; $k<count($myrows);$k++) 
								{
								  $commentuserid= $myrows[$k]['user_id'];
                                   global $userpro;
								   if(empty($myrows[$k]['comment_content']))
								    {
								      continue;	 
								    } 
  							  ?>
                              
								<div class="comment" style="width:100%;">
                                   <div class='userimage' style="width:10%; float:left;">
                                     <?php 
									 $userimage1= $userpro->post_thumb($post_id,1);
									
		 $imagesrc=urldecode(str_replace('<img src="http://www.zuvaa.com/shop/wp-content/plugins/userpro/lib/timthumb.php?src=','', $userimage1));
		                             ?>
                                      <a href="<?php echo bloginfo('url').'/profile/'.strtolower($myrows[$k]['comment_author']); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 );?></a>
                                   </div> 
                                   <div class="actcomment" style="float:left;margin-top:-8px;width:320px;">
<p class='userurl' style="width: 100%;clear: both;margin-left: 12px;color: blue;font-weight: bold;"><a href="<?php echo bloginfo('url').'/profile/'.strtolower($myrows[$k]['comment_author']); ?>"><?php echo $myrows[$k]['comment_author']; ?></a></p>
                 <p class="commentcontent" style="margin-left:12px;"><?php echo $myrows[$k]['comment_content']; ?></p>
                                   </div>
                                     
                                </div>  
                                <div style="clear:both"></div>
						  <?php } ?>    
     
                            
                        </div>
                    </div>
                    <form  class="commentform" action="">
                      <?php 
					   
                        $username = wp_get_current_user()->user_login;
					  ?>
                      <div id="result_side"></div>
                        <table style="width:100%;">
                            <tbody>
                                <tr><td class="field" style="width: 89%;float: left;display: block;"><textarea name="text" id="text" style='padding: 0px;margin: 0px;width: 100%;'></textarea></td>
                                <!--<td  style="display: inline-block;float: left;width: 9%;"><img src="<?php //echo bloginfo('template_url')?>/images/comment.png" style="padding-top:7px;" class='postcomment' onClick="submitComment(this)" id="<?php //echo $post_id; ?>"/></td>-->
                                <input type="hidden" value="pajax" name="action" />
                                <input type="hidden" value="<?php echo $username ?>" name="username" />
                                <input type="hidden" value="<?php echo $post_id; ?>" name="post_id" />
                                
                                <td style="display: inline-block;float: left;width: 9%;"><input style="border:none;text-indent:-9999px;background: url(<?php echo bloginfo('template_url')?>/images/comment.png) no-repeat;width:32px;height:32px;cursor:pointer;" type="submit" value="post" /></td>
                                </tr>
                                
                            </tbody></table>
                    </form>
           <script type="text/javascript">  						
			jQuery(".commentform").submit(function() {			
				  jQuery('#result_side').html('<span class="loading" style="padding:5px;background:#f5a676;color:#fff;margin-bottom:5px;float:left;width:354px;">Sending...</span>').fadeIn();
				  var input_data = jQuery('.commentform').serialize();
				  jQuery.ajax({
							  type: "POST",
							  url:  "http://www.zuvaa.com/shop/comment-helper/",
							  data: input_data,
							  success: function(msg){
								  jQuery('.loading').remove();
								  jQuery('<div>').html(msg).appendTo('div#result_side').hide().fadeIn('slow');
								 
							  }
				  });
				  return false;
			
			});
			</script>
                
 </div>
</div>

