<?php
//
/**
 * 
 * Обработчик для главной страницы
 * 
 */

# Защита от мудаков
if (!defined( 'ABSPATH' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Вызов файлов плагина напрямую запрещен.', 'acs'));
}

if(!class_exists( 'WP_List_Table' )) { // еще один баг вротпресса... ну сколько можно, блджад! уже 3 бага насчитал...
    include_once( ABSPATH . WPINC . '/class-wp-list-table.php' );
}

class_alias('WP_List_Table', 'AftLT');	// некрасиво. Я не допускаю слеш в именах классов.

class CCListTable extends AftLT{
	
	# Конструктор
    function __construct(){
        parent::__construct(
			array(
				'singular'  => 'Сниппет',	//Название одной записи
				'plural'    => 'Сниппеты',	//Название нескольких записей
				'ajax'      => false,		//Поддержка Ajax
			)
		);
    }
 
	 # Если данных нет
	function no_items(){
		_e('Данные отсутствуют.','acs');
	}
	
	/**
	 * 
	 * Тут определеся какие данные будут каким колонкам соответствовать. У нас массив [{id:'0', title:'Название',....}], соответственно выборка будет $item['title'], $item['id'].
	 * В данном случае $column_name изменяется и принимает значения 'id','title' и другие, при итерациях цикла. Поэтому и выборка идет в виде: return $item[$column_name];
	 * 
	 */
	function column_default($item, $column_name) {
		switch($column_name){ 
			case 'id':
			case 'title':
			case 'mode':
			case 'code':
				return $item[$column_name];
				break;
			default:
				return print_r( $item, true ) ;	// По умолчанию распечатываем весь массив
		}
	}
	
	/**
	 * 
	 * Тут можно указать какие колонки мы хотим сделать сортируемыми
	 * 
	 */
	function get_sortable_columns() {
		return array(
				'id'  		=> array('id',false),
				'title' 	=> array('title',false),
			);
	}
	
	/**
	 * 
	 * Тут указываются надписи над колонками
	 * 
	 */
	function get_columns(){
		$columns = array(
			//'cb'        	=> '<input type="checkbox" />',
			'id' 			=> 'ID',
			'title'    		=> __('Название','acs'),
			'mode'	 		=> __('Состояние','acs'),	//Включено - выключено
			'code' 			=> __('Код','acs'),
			);
		return $columns;
    }
	
	/**
	 * 
	 * Значения сортировки по умолчанию.
	 * 
	 */
	function usort_reorder($a, $b) {
		// По умолчанию сортировка идет по айди
		$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'id';
		// По возрастанию
		$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
		// Переопределяем порядок сортировки
		$result = strcmp($a[$orderby], $b[$orderby]);
		return ($order === 'asc') ? $result : -$result;
	}
	
	/**
	 * 
	 * Названия дополнительных ссылок в каждой колонке.(Edit, Delete)
	 * Формат функции: column_{$keyname}, где keyname - имя колонки, к которой будут добавляться доп. ссылки.
	 */
	function column_title($item){
		$edit_page_url = "aft_snippets/new_snippet";
		
		$actions = array(
			'edit'	=> sprintf('<a href="?page=%s&action=%s&snippet_id=%s">'.__('Редактировать','acs').'</a>', $edit_page_url, 'edit', $item['id']),
			'on'	=> sprintf('<a href="?page=%s&action=%s&snippet_id=%s">'.__('Включить','acs').'</a>', $_REQUEST['page'], 'on', $item['id']),
			'off'	=> sprintf('<a href="?page=%s&action=%s&snippet_id=%s">'.__('Выключить','acs').'</a>', $_REQUEST['page'], 'off', $item['id']),
			'delete'=> sprintf('<a href="?page=%s&action=%s&snippet_id=%s">'.__('Удалить', 'acs').'</a>', $_REQUEST['page'], 'delete', $item['id']),
		);
		return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
	}
	
	/**
	 * 
	 * Действия, которые можно применить для нескольких выбранных элементов
	 * 
	 */
	/*
	function get_bulk_actions() {
		$actions = array(
			'delete'  => 'Удалить',
		);
		return $actions;
	}*/

	# Колонка с чекбоксом
	function column_cb($item) {
        //return sprintf('<input type="checkbox" name="parsers[]" value="%s" />', $item['ID']);    
    }
	
	/**
	 * Тут мы формируем массив данных, отображаемый при поиске и при перехода по страницам
	 * Данные поиска, берутся из $_POST['s'], $page берется из $this->get_pagenum(); $num_items - количество элементов на одной странице
	 */
	function get_data_array($page, $num_items){
		$page = $page - 1;	//Весь прикол в том, что ебаный вротперсс начинает отсчет страниц не с 0, как это в php, а с 1. Пизда тупая...
		global $wpdb;
		$table_name = $wpdb->prefix.'aft_cc';
		// Обычный запрос для первой страницы
		$query = $wpdb->prepare("SELECT `title`,`mode`,`id` FROM `{$table_name}` LIMIT %d,%d",
									array(
										$page*$num_items,
										$num_items,
									)
								);
		// Запрос, содержащий строку поиска
		$search = "";
		if(isset($_POST['s']) && $_POST['s']){	// Если массив пуст его значение == false
			$query = $wpdb->prepare("SELECT `title`,`mode`,`id` FROM `{$table_name}`
									WHERE `title` LIKE %s LIMIT %d,%d", 
										array(
											'%'.like_escape($_POST['s']).'%',
											$page*$num_items, 
											$num_items,
										)
									);
		}
		
		return $wpdb->get_results($query, ARRAY_A);	// ARRAY_A - ассоциативный массив, то же самое что и mysql_fetch_assoc
	}
	
	/**
	 * 
	 * Возвращает кол-во записей в таблице. $def - количество, которое получается из count($found_data)
	 * 
	 */
	function get_total_items($def){
		global $wpdb;
		$table_name = $wpdb->prefix.'aft_сс';
		$count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
		if($count == null) return $def;
		else return $count;
	}
	
	/**
	 *
	 * Основаня функция. Тут идет получение данных для табицы($this->get_data_array), плучение колонок($this->get_columns) и прочей хрени.
	 *
	 */
	function prepare_items() {
		$hidden   = array('code');					// Список колонок, которые нужно скрыть
		
		$columns  = $this->get_columns();			// Получаем колонки
		$sortable = $this->get_sortable_columns();	// Определяем, какие из них можно сортировать, а какие- нельзя
		
		$this->_column_headers = array($columns, $hidden, $sortable);	// Задаем заголовки

		$per_page = 10;				// Число элементов на одной странице
		$cp = $this->get_pagenum();	//$cp - не удержался, троллинг власти даже в коде весьма уместен
		
		$found_data = $this->get_data_array($cp, $per_page);		// Получение данных для конкретного представления
		$total_items = $this->get_total_items(count($found_data));	// Получение общего количества элементов в таблице
		usort($found_data, array( &$this, 'usort_reorder' ));		// Сортировка
		
		$this->set_pagination_args( 
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) 
		);
		$this->items = $found_data;	// Задаем содержимое таблицы
	}

}
// end of file //