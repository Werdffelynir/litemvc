(function($){

    jQuery.fn.ajaxSlimBox = function(options){

        options = $.extend({
            hideScrollBar: false,			// убрать скрол-бар с браузера на время окна. По умолчанию не убирает
            loadingHtml: "Loading...",	    // контент при загрузке
            closeHtml: "close",		        // контент кнопки Close
            type: "out",					// out - в вспл. окно. in - в указаный блок атрибутом "data-dump"
            center: true,				    // по центру вспл. окна
            form: false,					// если true выполняется запрос через submit() для форм
            boxWin: "slimBox",			    // стили: основной блок окна
            boxWinClose: "slimBoxClose",	// стили: кнопка закрыть
            boxWinContent: "slimBoxContent",// стили: контента окна
            boxWinLoading: "slimLoading",	// стили: загрузки
            boxWinBG: "slimBackground"	    // стили: бг
        }, options);

        var handler = function(){

			var hideScrollBar = options.hideScrollBar,
				loadingHtml = options.loadingHtml,
				closeHtml = options.closeHtml,
				type = options.type,
				center = options.center,
				boxWin = options.boxWin,
				boxWinClose = options.boxWinClose,
				boxWinContent = options.boxWinContent,
				boxWinLoading = options.boxWinLoading,
				boxWinBG = options.boxWinBG;

	        var bodySelector = $("body"),
		        firstTarget =  bodySelector.children(0),
	            check = $(firstTarget[0]).attr('class');

	        if((check == undefined) && type == "out"){

		        // add div elements
		        firstTarget.before(
			        '<div class="'+boxWinBG+'"></div>' +
			        '<div class="'+boxWin+'">' +
			            '<div class="'+boxWinClose+'">'+closeHtml+'</div>' +
			            '<div class="'+boxWinContent+'"></div>' +
			        '</div>'
		        );

		        // base style
		        $("."+boxWinBG)
			        .css('display','none')
		        $("."+boxWin)
			        .css('display','none');

		        // position center
		        if(center == true){
			        $("."+boxWin)
				        .css('left','-'+($("."+boxWin).width() / 2)+'px')
				        .css('margin-left','50%');
		        }
	        }

			// AJAX запрос для всех елементов HTML кроме forms
			if(options.form == false){

                //bodySelector.on('click', this, function(event) {
			    $(this).click(function(event){

			        action = $(this).attr("href");
			        if(action == undefined) action = $(this).attr("data-href");
			        var dump = $(this).attr("data-dump");
			        post = $(this).attr("data-post");
			        if(post.length < 3)  post = '';
			        event.preventDefault();
					$.ajax({
						type: "POST",
						data: post,
						url: action,
						success: function(data){
							if(type == "out"){
								$('.'+boxWinContent).html(data);
							}else if(type == "in"){
								$(dump).html(data);
							}
						},
						error: function(jqXHR, textStatus, errorThrown){
							console.log(jqXHR);
							console.log(textStatus);
							console.log(errorThrown);
						},
						beforeSend: function(){
							if(type == "out"){
								$('.'+boxWinContent).html('<div class="'+boxWinLoading+'">'+loadingHtml+'</div>');
								_showWindows('.'+boxWin);
							}else if(type == "in"){
								$(dump).html('<div class="'+boxWinLoading+'">'+loadingHtml+'</div>');
							}
						}
					});
					return false;
				});

			// AJAX запрос для форм
			}else if(options.form == true){

                //bodySelector.on('submit', this, function(event) {
				$(this).submit(function(event){
					var postData = $(this).serializeArray();
			        action = $(this).attr("action");
			        var dump = $(this).attr("data-dump");

					$.ajax({
						type: "POST",
						url: action,
						data: postData,
						success: function(data){
							if(type == "out"){
								$('.'+boxWinContent).html(data);
							}else if(type == "in"){
								$(dump).html(data);
								
							}
						},
						error: function(jqXHR, textStatus, errorThrown){
							console.log(jqXHR);
							console.log(textStatus);
							console.log(errorThrown);
						},
						beforeSend: function(){
							if(type == "out"){
								$('.'+boxWinContent).html('<div class="'+boxWinLoading+'">'+loadingHtml+'</div>');
								_showWindows('.'+boxWin);
							}else if(type == "in"){
								$(dump).html('<div class="'+boxWinLoading+'">'+loadingHtml+'</div>');
							}
						}
					});
					event.preventDefault();
					return false;
				});
				
			}

			// Внутрение функции обработки стилей для вспл. окна
			function _hideWindows(element){
				if(hideScrollBar == true) $("body").css('overflow','auto');
				$(element).fadeOut();
				$('.'+boxWinBG).fadeOut();
			}
			function _showWindows(element){
				if(hideScrollBar == true) $("body").css('overflow','hidden');
				$(element).fadeIn();
				$('.'+boxWinBG).fadeIn();
			}

			// Запуск екшенов закрытия вспл. окна
	        bodySelector.on('click', '.'+boxWinClose, function() {
				var hideWin = $('.'+boxWin);
				_hideWindows(hideWin);
			});
	        bodySelector.on('click', '.'+boxWinBG, function() {
				var hideWin = $('.'+boxWin);
				_hideWindows(hideWin);
			});

        };
        
        return this.each(handler);
    };

})( jQuery );