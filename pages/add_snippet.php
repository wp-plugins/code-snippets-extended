<?php
//
/**
 * 
 * Страница добавления сниппета
 * 
 */

# Защита от мудаков
if (!defined( 'AFTCC__MAIN_FILE' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Access Denied.', 'acs'));
}

include_once(AFTCC__PLUGIN_DIR . "proc/add_snippet.proc.php");

?>



<div class="wrap">
	<!-- В этом блоке разместим результаты теста через js обработчик -->
	<div id="test_res"></div>
	<?php if($snipp->p_msg != ""): ?>
	<div class="aft_info">
		<?php echo $snipp->p_msg; ?>
	</div>
	<?php endif; ?>
	<form method="post">
		<input type="hidden" value="<?php echo $snipp->is_edit; ?>" name="is_edit" />
		<table class="form-table">
			<tr>
				<th><?php _e('Snippet Name','acs'); ?></th>
				<td><input type="text" name="title" placeholder="title" value="<?php echo $snipp->title; ?>" /></td>
			</tr>
			<tr>
				<th><?php _e('Snippet Code','acs'); ?></th>
				<td>
					<p>
						<a href="#" id="acs-insert-media-button" class="button-primary" data-editor="content" title="Add Media">
							<span class="dashicons dashicons-welcome-write-blog" style="margin-top:2px;"></span>
							<?php _e("Pick media","acs"); ?>
						</a>
						<input id="txt_img_url" name="img_url" placeholder="url" style="width:500px; margin-left:5px;" type="text"></input>
					</p>
					<br />
					<textarea name="snippet_code"><?php echo $snipp->code; ?></textarea>
					<div id="s_code" />
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<div class="aft_info">
						<strong><?php _e('Small info:','acs'); ?></strong>
						<p><?php _e('Snippet Code field allows you to write js, html or css code.','acs'); ?></p>
						<ul>
							<li class="aft_data_info">
								<b><?php _e('Plase CSS like that:','acs'); ?></b><br>
								&lt;style&gt; #main{ display:block; } &lt;/style&gt;
							</li>
							<li class="aft_data_info">
								<?php _e('Plase PHP like that:','acs'); ?><br>
								&lt;?php echo "123"; ?&gt;
							</li>
							<li class="aft_data_info">
								<b><?php _e('Plase JS like that:','acs'); ?></b>
								<p>&lt;script type="text/javascript"&gt; alert("Hello World!"); &lt;/script&gt;</p>
							</li>
							<li class="aft_data_info">
								<b><?php _e('For JQuery:', 'acs'); ?></b><br>
								&lt;script type="text/javascript"&gt; jQuery(function($) {<br>
											$(document).ready(function(){<br>
												// write your code here<br>
											});<br> });<br>
								&lt;/script&gt;<br>
							</li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
		<p class="submit" style="position: relative;">
			<?php
			/**
			 * Режим тестирования
			 */ 
			$ajax_nonce = wp_create_nonce( "FasdaEEr1123SAB><asdW" );
			?>
			<script type="text/javascript">
				var nonce_data = "<?php echo $ajax_nonce; ?>";
			</script>
			<a id="test_code" class="button-primary" href="#"><?php _e('Test','acs');  ?></a>

			<?php
			/**
			 * Режим сохранения
			 */
			?>

			<input type="submit" class="button-primary" name="submit" value="<?php 
				if($snipp->is_edit == 0) _e('Save Snippet','acs'); 
				else _e('Update Snippet','acs');  
			?>" />
		</p>
		

	</form>
</div>

<?php // end of file // ?>