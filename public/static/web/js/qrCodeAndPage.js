$("#bottomNav li").each(function(){
				$(this).mouseover(function(){
					$(this).find(".code").removeClass("hide").addClass("show").siblings(".code").removeClass("show").addClass("hide")
				});
				$(this).mouseout(function(){
					$(this).find(".code").removeClass("show").addClass("hide")
				});
			})
$('.fyNav a li').each(function(){
				$(this).click(function(){
					$(this).addClass("myactive").parent().siblings().children().removeClass("myactive");
				});
			});