<?php
//
/**
 * 
 * Ajax обработчики модуля
 * 
 */

# Защита от мудаков
if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Вызов файлов плагина напрямую запрещен.', 'acs'));
}

class AftCCAjax{
	# Конструктор
	function __construct() {
		add_action( 'wp_ajax_aftcb_show_form', array($this, 'aftcb_show_form') );
		add_action( 'wp_ajax_test_code', array($this, 'test_code') );

	}

	function aftcb_show_form(){
		if(!is_admin()) die();
		//check_ajax_referer( 'FasdaEEr1123SAB><asdW', 'nonce', true );	// Проверка безопасности. Если ключи нe совпадают - вызывается die();
		// Всплывающую форму формируем тут.
		global $wpdb;
		$table_name = $wpdb->prefix.'aft_cc';
		$query = $wpdb->prepare("SELECT * FROM {$table_name}
										WHERE `mode`='on'", 
										array($id,)
										);
		$arr = $wpdb->get_results($query, ARRAY_A);
		if(count($arr) == 0){ echo "<div class='white_popup'>" . __('Сниппеты еще не созданы.','acs') . "<p></div>"; die();
		}
		// Тут формирует html нашего iframe
		$res  = "<div class='white_popup'>";
		$res .= "<table class='snippets_table' cellspacing='0' border='1'>";
		$res .= "<tbody><tr><th>Id</th><th>Name</th><th>+</th></tr>";
		foreach($arr as $snippet){
			$res .= "<tr>";
			$res .= "<td class='st_id'>".$snippet['id']."</td>";
			$res .= "<td class='st_title'>".$snippet['title']."</td>";
			$res .= "<td class='st_actions'><a id='select_snippet' class='button-primary' href='#''>".__('Выбрать','acs'). "</a></td>";
			$res .= "</tr>";
		}

		$res .= "</tbody></table>";
		$res .= "</div>";
		//$res .= "</body></html>";

		echo $res;

		die();
	}

	function test_code(){
		check_ajax_referer( 'FasdaEEr1123SAB><asdW', 'nonce', true );
		$code = urldecode(stripslashes($_POST['code']));

		preg_match('#<\?php([\s\S]+?)\?>#im',$code,$e_php);
		if($e_php){
			$res = "";
			ob_start(); // Ловим вывод eval'а в основной буфер вывода
			eval($e_php[1]);
			$res = ob_get_contents();
			ob_clean();
			ob_end_flush();
			$code = preg_replace('#<\?php([\s\S]+?)\?>#im',$res,$code);
		}
		echo $code;
		die();
	}
}

new AftCCAjax();
// end of file //