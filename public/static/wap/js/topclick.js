$(function(){
			var optionMenu = function(){
				let nowState = $('.iconMore').attr('data-state');
				
				var state = nowState == 'hide' ? 'show' : 'hide';
				
				$('.iconMore').attr('data-state', state);
				$("#menu").removeClass('hide');
				$("#menu").removeClass('show');
				$("#menu").addClass(state);
			}
			$(".iconMore,.closeIconMore").on('click', function(){
				optionMenu();
				
			});
			$('.closeIconMore').click(function(){
				$('#menu').removeClass('show').addClass('hide');
			})
	})