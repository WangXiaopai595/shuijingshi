$(function(){
			$(".AboutUs").mouseover(function(){
                // $(".myabout").removeClass("hide").addClass("show");
                $(".header").css("background","rgba(0,0,0,1)");
                var AboutH=$('.About').height();
                $('.About').animate({
					height:'175px'
				})
                if(AboutH > 0){
                    $('.About').stop().animate();
                }
			});
			$(".AboutUs").mouseout(function(){
                $(".header").css("background","");
                // $(".myabout").removeClass("show").addClass("hide");
                $('.About').animate({
                    height:'0px'
                })
			});
			$(".ExcellentCourse").mouseover(function(){
				// $(".bg_hover").removeClass("hide").addClass("show");
				$(".header").css("background","rgba(0,0,0,1)");
				var bg_hoverH=$('.bg_hover').height();
                    $('.bg_hover').animate({
                        height:'204px'
                    })


                if(bg_hoverH > 0){
                    $('.bg_hover').stop().animate();
                }
			});
			$(".ExcellentCourse").mouseout(function(){
				// $(".bg_hover").removeClass("show").addClass("hide");
				$(".header").css("background","");
                $('.bg_hover').animate({
                    height:'0px'
                })
			});
			// $('.rightFixedImg').mouseenter(function () {
			// 	$('FixedHover').css('width','0px');
			// 	$('FixedHover').animate({
			// 		width:'170px',
			// 	})
			// 	let fixedHoverW=$('.FixedHover').width();
			// 	if(fixedHoverW > 0){
			// 		$('FixedHover').stop().animate();
			// 	}
            // })
			// $('.rightFixedImg').mouseleave(function () {
			// 	$('FixedHover').animate({
			// 		width:'0px',
			// 		background:'rgba(0,0,0,1)'
			// 	})
			// })
			$(".rightFixedImg").each(function(){
				$(this).mouseenter(function(){
					$(this).find(".FixedHover").animate({
                        width:'170px',
                    })
                    var fixedHoverW=$('.FixedHover').width();
                    if(fixedHoverW > 0){
                        $('FixedHover').stop().animate();
                    }
				});
                $(this).mouseleave(function(){
                    $(this).find(".FixedHover").animate({
                        width:'0px'
                    })
                });
			});



			//$('.CollegeMb1').click(function(){
			//	$('.CollegeMb1').removeClass('show').addClass('hide');
			//});
			$('body').css('font-family','微软雅黑');
    		$('html').css('font-family','微软雅黑');
			$('.BigPic').click(function(){
				$('.BigPic').removeClass('show').addClass('hide');
			});
			$('.topIcons').each(function(){
				$(this).mouseenter(function(){
					$(this).find('.qRCode').removeClass('hide').addClass('show');
				})
				$(this).mouseleave(function(){
					$(this).find('.qRCode').removeClass('show').addClass('hide');
				})
			})
			var mywidth=window.innerWidth;
			var myWidth=mywidth+10;
			$('.CollegeMb1').css('width',''+myWidth+'px');
			$('.BigPic').css('width',''+myWidth+'px');
			$('.returnTop').click(function () {
                window.scrollTo(0,0);
            })
	})