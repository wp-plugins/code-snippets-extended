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
	exit(__('Вызов файлов плагина напрямую запрещен.', 'acs'));
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

	preg_match_all('#<\?php([\s\S]+?)\?>#imu',$code,$e_php);
	if($e_php){
		foreach($e_php[1] as $key=>$tmp){
			$res = "";
			ob_start(); // Ловим вывод eval'а в основной буфер вывода
			/**
			 * 
			 * Если вдруг вас интересует - как работают вложенные друг в друга ob_start, то вот тема на стековерфлов - http://stackoverflow.com/questions/10441410/what-happened-when-i-use-multi-ob-start-without-ob-end-clean-or-ob-end-flush
			 * 
			 */

			eval($tmp);
			$res = ob_get_contents();
			ob_clean();
			ob_end_flush();
			$code = str_replace ( $e_php[0][$key] , $res , $code);
		}
	}

	return $code;
}
// end of file //
