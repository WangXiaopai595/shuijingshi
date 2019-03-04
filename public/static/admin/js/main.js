layui.config({
	base: "js/"
}).use(['form', 'element', 'layer', 'jquery'], function() {
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		element = layui.element(),
		$ = layui.jquery;
	$(".panel a").on("click", function() {
		window.parent.addTab($(this));
	})
	$.get("../json/newsList.json", function(data) {
		var waitNews = [];
		$(".allNews span").text(data.length);
		for(var i = 0; i < data.length; i++) {
			var newsStr = data[i];
			if(newsStr["newsStatus"] == "待审核") {
				waitNews.push(newsStr);
			}
		}
		$(".waitNews span").text(waitNews.length);
		var hotNewsHtml = '';
		for(var i = 0; i < 5; i++) {
			hotNewsHtml += '<tr>' +
				'<td align="left">' + data[i].newsName + '</td>' +
				'<td>' + data[i].newsTime + '</td>' +
				'</tr>';
		}
		$(".hot_news").html(hotNewsHtml);
	})
	$.get("../json/images.json", function(data) {
		$(".imgAll span").text(data.length);
	})
	$.get("../json/usersList.json", function(data) {
		$(".userAll span").text(data.length);
	})
	$.get("../json/message.json", function(data) {
		$(".newMessage span").text(data.length);
	})
	$(".panel span").each(function() {
		$(this).html($(this).text() > 9999 ? ($(this).text() / 10000).toFixed(2) + "<em>万</em>" : $(this).text());
	})
	if(window.sessionStorage.getItem("systemParameter")) {
		var systemParameter = JSON.parse(window.sessionStorage.getItem("systemParameter"));
		fillParameter(systemParameter);
	} else {
		$.ajax({
			url: "../json/systemParameter.json",
			type: "get",
			dataType: "json",
			success: function(data) {
				fillParameter(data);
			}
		})
	}

	function fillParameter(data) {
		function nullData(data) {
			if(data == '' || data == "undefined") {
				return "未定义";
			} else {
				return data;
			}
		}
		$(".version").text(nullData(data.version));
		$(".author").text(nullData(data.author));
		$(".homePage").text(nullData(data.homePage));
		$(".server").text(nullData(data.server));
		$(".dataBase").text(nullData(data.dataBase));
		$(".maxUpload").text(nullData(data.maxUpload));
		$(".userRights").text(nullData(data.userRights));
	}
})