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
	exit(__('Вызов файлов плагина напрямую запрещен.', 'acs'));
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
				<th><?php _e('Название сниппета','acs'); ?></th>
				<td><input type="text" name="title" placeholder="title" value="<?php echo $snipp->title; ?>" /></td>
			</tr>
			<tr>
				<th><?php _e('Код сниппета','acs'); ?></th>
				<td>
					<p>
						<a href="#" id="acs-insert-media-button" class="button-primary" data-editor="content" title="Add Media">
							<span class="dashicons dashicons-welcome-write-blog" style="margin-top:2px;"></span>
							<?php _e("Медиа","acs"); ?>
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
						<strong><?php _e('Короткая справка:','acs'); ?></strong>
						<p><?php _e('Поле "Код Сниппета" позволяет размещать на сайте php\javascript или css код.','acs'); ?></p>
						<ul>
							<li class="aft_data_info">
								<?php _e('Размещайте CSS код следующим образом:','acs'); ?> <br>
								&lt;style&gt; #main{ display:block; } &lt;/style&gt;
							</li>
							<li class="aft_data_info">
								<?php _e('Размещайте PHP код следующим образом:','acs'); ?> <br>
								&lt;?php echo "123"; ?&gt;
							</li>
							<li class="aft_data_info">
								<?php _e('Размещайте js код следующим образом:','acs'); ?> <br>
								&lt;script type="text/javascript"&gt; alert("Hello World!"); &lt;/script&gt;
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
			<a id="test_code" class="button-primary" href="#"><?php _e('Тестировать','acs');  ?></a>

			<?php
			/**
			 * Режим сохранения
			 */
			?>

			<input type="submit" class="button-primary" name="submit" value="<?php 
				if($snipp->is_edit == 0) _e('Добавить сниппет','acs'); 
				else _e('Обновить сниппет','acs');  
			?>" />
		</p>
		

	</form>
</div>

<?php // end of file // ?>