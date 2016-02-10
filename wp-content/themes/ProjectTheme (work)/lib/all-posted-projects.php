<?php

/***************************************************************************
*
*	ProjectTheme - copyright (c) - sitemile.com
*	The only project theme for wordpress on the world wide web.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/products/wordpress-project-freelancer-theme/
*	since v1.2.5.3
*
***************************************************************************/

function ProjectTheme_display_all_prjs_page_disp()
{
	
		global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;	

	if ($_POST["all_filter"]){
		$_SESSION['all_filter'] = $_POST["all_filter"];
	}
	?>    
        
   <div id="content">
   <div class="padd10">
   		<div class="filter-field-area">   		
   			<form method="POST" id="all_filter_form">
   				<div class="padd10">
			   		<div class="search-keyword-bb">
						<div class="search-keyword-bb-left">Filter: </div>
						<div class="search-keyword-bb-right">
							<select name="all_filter" class="do_input" id="all_filter">
							<?php
								$a='';
								$b='selected';
								if(!$_SESSION["all_filter"]){
									echo '<option value="All" '.$b.'>All</option>';
								}
								else{
									echo '<option value="All" '.$a.'>All</option>';
								}
								if ($_SESSION["all_filter"]=="Open") {
									echo '<option value="Open" '.$b.'>Open</option>';
								}
								else{
									echo '<option value="Open" '.$a.'>Open</option>';
								}
								if ($_SESSION["all_filter"]=="Closed") {
									echo '<option value="Closed" '.$b.'>Closed</option>';
								}
								else{
									echo '<option value="Closed" '.$a.'>Closed</option>';
								}
								if ($_SESSION["all_filter"]=="Ending") {
									echo '<option value="Ending" '.$b.'>Ending Soon</option>';
								}
								else{
									echo '<option value="Ending" '.$a.'>Ending Soon</option>';
								}
								if ($_SESSION["all_filter"]=="Newest") {
									echo '<option value="Newest" '.$b.'>Newest</option>';
								}
								else{
									echo '<option value="Newest" '.$a.'>Newest</option>';
								}
							?>
			                </select>
			            </div>			
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#all_filter').change(function(){
					$('#all_filter_form').submit();
				});
			});
		</script>
	</div>
			
             
            		<div class="box_content">
                    
                    <?php
					
					global $wp_query;
					$query_vars = $wp_query->query_vars;
					
					$posts_per_page = 8;
					$posts_per_page = apply_filters('ProjectTheme_all_projects_page_per_page', $posts_per_page);
					if($_SESSION["all_filter"]){
						$sel = $_SESSION["all_filter"];
						switch ($sel) {

							case 'Closed':
								$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_query' => array(array('key' => 'closed', 'value' => '1', 'compare' => '=')));
								break;

							case 'Ending':								
								$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'ending','orderby' => 'meta_value_num','order' => 'ASC', 'meta_query' => array(array('key' => 'closed', 'value' => '0', 'compare' => '=')));
								break;

							case 'Open':								
								$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_query' => array(array('key' => 'closed', 'value' => '0', 'compare' => '=')));
								break;

							case 'Newest':								
								$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value&meta_key=post_date', 'order' => 'DESC',  'meta_query' => array(array('key' => 'closed', 'value' => '0', 'compare' => '=')));
								break;
							
							default:
								$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value_num', 'order' => 'DESC');
								break;
						}
					}
					else{
						$args = array('post_type' => 'project', 'paged' => $query_vars['paged'] , 'posts_per_page' => $posts_per_page, 'meta_key' => 'featured', 'orderby' => 'meta_value_num', 'order' => 'DESC');
					}

					$my_query = new WP_Query( $args );

					if($my_query->have_posts()):
					while ( $my_query->have_posts() ) : $my_query->the_post();
					
						ProjectTheme_get_post();
					
					endwhile;
					
						if(function_exists('wp_pagenavi')):
							wp_pagenavi( array( 'query' => $my_query ) );
						endif;
					
					else:
					_e('There are no projects posted.','ProjectTheme');
					
					endif;
					
					
					
					
					
					
					
					
					?>
                    
                    </div>
  </div>
    
    
      <!-- ################### -->
    
    <div id="right-sidebar">    
    
    	<ul class="xoxo">

        	 <?php dynamic_sidebar( 'other-page-area' ); ?>
        </ul>    
    </div>
    
    <?php
	
}

?>