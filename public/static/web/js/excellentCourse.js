$(function(){
			$(".ExcellentCourse").mouseover(function(){
				$(".bg_hover").removeClass("hide").addClass("show");
				
			});
			$(".ExcellentCourse").mouseleave(function(){
				$(".bg_hover").removeClass("show").addClass("hide");
			});
		});