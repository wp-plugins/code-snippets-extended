<?php
//
/**
 * 
 * Главная страница модуля
 * 
 */

# Защита от мудаков
if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Access Denied.', 'acs'));
}

require_once( AFTCC__PLUGIN_DIR . "proc/index.proc.php");
$items_table = new CCListTable();

if(isset($_POST['snippets']) && $_POST['action']){
	global $wpdb;
	$table_name = $wpdb->base_prefix.'aft_cc';
	if($_POST['action'] == "delete"){
		foreach($_POST['snippets'] as $id){
			$wpdb->delete( $table_name, array('id'=>$id));
		}
	}
}

# Действия - включить, выключить и удалить
if(isset($_GET['action'])){
	global $wpdb;
	$id = intval($_GET['snippet_id']);
	$table_name = $wpdb->base_prefix.'aft_cc';
	if($_GET['action'] == "on")
		$wpdb->update( $table_name, array('mode'=>'on'), array('id'=>$id));	// $wpdb->update( $table, $data, $where, $format = null, $where_format = null );
	if($_GET['action'] == "off")
		$wpdb->update( $table_name, array('mode'=>'off'), array('id'=>$id));
	if($_GET['action'] == "delete")
		$wpdb->delete( $table_name, array('id'=>$id)); 	// $wpdb->delete( $table, $where, $where_format = null ); 
}

# Инициализация таблицы
$items_table->prepare_items();
?>


<div class="wrap">
	<h2><?php _e('Code snippets.','acs'); ?>
		<a href="?page=aft_snippets/new_snippet" class="add-new-h2"><?php _e('Add New Snippet','acs'); ?></a>
	</h2>
	<div class="aft_info">
		<p><img style="width:60px;" src='<?php echo AFTCC__PLUGIN_URL . "img/icon.png";?>' id="main_i"></img></p>
		<p>
			<?php _e('Code Snippets Extended id powerful plugin, that allows you to create code snippets & embed it into posts or pages easily.','acs'); ?>
		</p>
		<p><?php _e('Dear user, i am - Kudashev Roman, author of this plugin. I am from Russia, but not from Moscow, i am from Ural. If you like this plugin - please donate me and help me to improve my work. <b>I have nothing to eat</b>, only potatoes, the prices are increasing every day. I am work for 7000 rubles per month(100$) and dont have money. Please help me if you can.', 'acs'); ?></p>
		<p>Webmoney: Z395586459766, R343924802694</p>
	</div>
	<form method="post">
		<input type="hidden" name="page" value="clt_page" /> <!-- Этот параметр нужен для $_REQUEST['page'] --> 
		<?php
			$items_table->search_box(__('Search by name','acs'), 'search_by_title');
			$items_table->display(); 
		?>
	 </form>
	 <p><?php _e('Author', 'acs'); ?>: &copy; <a href="http://aftamat4ik.ru" target="_blank"><?php _e('Roman Kudashev','acs'); ?></a></p>
</div>