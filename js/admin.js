jQuery(function($) {
    $(document).ready(function(){
    	/******************* Инициализация текстового редактора ************************/
		if($('#s_code').length != 0){
			editor = ace.edit("s_code");

			textarea = $('textarea[name="snippet_code"]').hide();

			editor.getSession().setValue(textarea.val());
			editor.getSession().on('change', function(){	//Заставляем менять содержимое текстового поля. Мы пишем в ActEdit, а символы копируются в невидимый textarea
				textarea.val(editor.getSession().getValue());
			});

			editor.setTheme("ace/theme/xcode");
			editor.session.setMode({path:"ace/mode/php"});
			editor.focus();
		}
		
		/**** Тык на кнопку "тестировать шорткод" на странице добавления шорткода *******/
		
		if($("#test_code").length != 0)
			$('#test_code').on('click',function(){
				macro_code = $('textarea[name="snippet_code"]').val();
				$.post(ajaxurl, {
					action	: 	'test_code',
					code	:	macro_code,
					nonce	: 	window.nonce_data,
				}, 
				function(response){
					tr = $("#test_res");
					tr.empty();
					tr.append('<div class="aft_info">'+response+'</div>');
				});
				return false;
			});
			
		/** Тык на кнопку "Сниппет" над текстовым редактором при добавлении материала ****/
		if($("#show_code_snippets").length != 0)
			$("#show_code_snippets").magnificPopup({
				type: 'ajax',
				preloader: false,
				callbacks: {
					open				: function() {},
			    	ajaxContentAdded	: function() {
								    		$("a#select_snippet").on("click", function(){
								    			id = $(this).parent().parent().find("td.st_id").html();
								    			
								    			wp.media.editor.insert(id);
								    			$.magnificPopup.close();
								    		});
								    	  },
			    	close				: function() {}
				}
			});
		
		/** Показать окно выбора медиа файла **/
		if($("#acs-insert-media-button").length != 0)
			$("#acs-insert-media-button").click(function() {
				formfield = $('#txt_img_url').attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
		
		/** Медиа выбрано **/
		if($("#show_code_snippets").length == 0)
			window.send_to_editor = function(html) {
				imgurl = $('img',html).attr('src');
				$('#txt_img_url').val(imgurl);
				tb_remove();
			}
		
		
    });
});