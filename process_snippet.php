<?php
//
/**
 * 
 * Файл обработчика сниппета
 * 
 */

# Защита от мудаков
if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Access Denied.', 'acs'));
}

# Регистрируем свой обработчик шорткодов в системе.
# Наш шорткод выглядит так: [code_snippet id="1"]
add_shortcode( 'code_snippet', 'proc_shortcode');

function proc_shortcode($data){
	$id = $data['id'];
	global $wpdb;
	$table_name = $wpdb->base_prefix.'aft_cc';
	$query = $wpdb->prepare("SELECT * FROM {$table_name}
									WHERE `id` = '%d' AND `mode`='on'", 
									array($id,)
									);
	$arr = $wpdb->get_results($query, ARRAY_A);
	if($arr == false) return "";
	$code = $arr[0]['code'];
	
	ob_start();
	eval("?> ".$code. " <?php;");
	$res = ob_get_contents();
	ob_clean();
	ob_end_flush();

	return $res;
}
// end of file //
