<?php
//
/**
 *
 * @package aft_cb
 *
 *
 *	Plugin Name: Code Snippets Extended
 *	Plugin URI: http://aftamat4ik.ru/
 *	Description: Плагин, позволяющий размещать в постах php/js или css код. Позволяет создавать сниппеты из блоков кода и добавлять их простым кликом мыши.
 *	Version: 1.0.1
 *	Author: Роман Кудашев
 *	Author URI: http://aftamat4ik.ru/
 *	License: GPLv2 or later
 *  Text Domain: acs
 *  Domain Path: /languages/
 */

# Защита от мудаков
if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
}

# Константы
define( 'AFTCC__PLUGIN_URL', dirname( __FILE__ ) );	// Урл папки плагина. Для подключения картинок и скриптов из внутренних папок
define( 'AFTCC__PLUGIN_DIR', dirname( __FILE__ ) . "/" );	// Системный путь до папки плагина, нужен для include_once
define( 'AFTCC__MAIN_FILE' , __FILE__ );					// Путь до главного (то есть до этого) файла плагина


function l18n(){ # Подрубаем переводы
	load_plugin_textdomain( 'acs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'l18n');

include_once(AFTCC__PLUGIN_DIR."functions.php");
include_once(AFTCC__PLUGIN_DIR."process_snippet.php");

if (is_admin()) {
	include_once( AFTCC__PLUGIN_DIR . 'main.php' );				// Основной файл плагина.	
	include_once( AFTCC__PLUGIN_DIR . 'ajax.php' );	
}











// end of file //