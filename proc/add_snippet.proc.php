<?php
//
/**
 * 
 * Обработчик добавления сниппета
 * 
 */

# Защита от мудаков
if (!defined( 'AFTCC__MAIN_FILE' )){
	header('HTTP/1.0 403 Forbidden');
	exit(__('Access Denied.', 'acs'));
}


class AddSnippetProc{

	public $title 	= ""; 		//Значения полей по умолчанию
	public $code 	= "";
	public $p_msg 	= "";
	public $is_edit = 0;		//1 - режим редактирования

	function __construct(){
		// При созранении
		if(isset($_POST['submit'])){
			$this->add_snippet_proc();
		}

		// Режим редактирования
		if(isset($_GET['action']) && $_GET['action'] == "edit"){
			$this->is_edit = 1;
			global $wpdb;
			$id = intval($_GET['snippet_id']);
			$table_name = $wpdb->base_prefix.'aft_cc';
			$query = $wpdb->prepare("SELECT * FROM {$table_name}
											WHERE `id` = '%d'", 
												array(
													$id,
												)
											);
			$data = $wpdb->get_results($query, ARRAY_A);
			if($data){
				$this->title = $data[0]['title'];
				$this->code = $data[0]['code'];
			}else{
				$this->p_msg = __("Error, plugin can't edit this","acs" ); 
			}
		}

	}

	# Добавляем сниппет в базу
	function add_snippet_proc(){
		$this->code = htmlspecialchars_decode(stripslashes($_POST['snippet_code']));
		$this->title = htmlspecialchars(urldecode($_POST['title']));
		$this->is_edit = intval($_POST['is_edit']);

		if(empty($this->title)){ 
			$this->p_msg = __('Error: Please set snippet name before save.', 'acs' );
			return;
		}

		if(empty($this->code)){ 
			$this->p_msg = __('Error: Snippet code not exist', 'acs' );
			return;
		}

		global $wpdb;
		$table_name = $wpdb->base_prefix . "aft_cc";

		$ret = false;

		if($this->is_edit == 0){ // Сохранение нового сниппета
			$ret = $wpdb->insert( 
				$table_name, 
				array( 
					'id'			=> NULL, 
					'title'			=> $this->title,
					'mode'			=> 'on',
					'code'			=> $this->code
					)
				);
		}
		if($this->is_edit == 1){ // Редактирование уже существующего сниппета
			$id = intval($_GET['snippet_id']);
			$ret = $wpdb->update( 
				$table_name, 
				array( 
					'title'			=> $this->title,
					'code'			=> $this->code
					),
				array('id'=>$id)
				);
		}

		if($ret != false) $this->p_msg = __('Successful!','acs');
		else $this->p_msg = __('Unfortunately we cannot save this snippet. Some problems with database.','acs');

	}

}

$snipp = new AddSnippetProc();
// end of file //