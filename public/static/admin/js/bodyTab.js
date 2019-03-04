var tabFilter, menu = [], liIndex, curNav, delMenu;
layui.define(["element", "jquery"], function (exports) {
    var element = layui.element(), $ = layui.jquery, layId, Tab = function () {
        this.tabConfig = {closed: true, openTabNum: 10, tabFilter: "bodyTab"}
    };
    if ($(".navBar").html() == '') {
        var _this = this;
        $(".navBar").html(navBar(navs)).height($(window).height() - 230);
        element.init();
        $(window).resize(function () {
            $(".navBar").height($(window).height() - 230);
        })
    }
    Tab.prototype.set = function (option) {
        var _this = this;
        $.extend(true, _this.tabConfig, option);
        return _this;
    };
    Tab.prototype.getLayId = function (title) {
        $(".layui-tab-title.top_tab li").each(function () {
            if ($(this).find("cite").text() == title) {
                layId = $(this).attr("lay-id");
            }
        })
        return layId;
    }
    Tab.prototype.hasTab = function (title) {
        var tabIndex = -1;
        $(".layui-tab-title.top_tab li").each(function () {
            if ($(this).find("cite").text() == title) {
                tabIndex = 1;
            }
        })
        return tabIndex;
    }
    var tabIdIndex = 0;
    Tab.prototype.tabAdd = function (_this) {
        if (window.sessionStorage.getItem("menu")) {
            menu = JSON.parse(window.sessionStorage.getItem("menu"));
        }
        var that = this;
        var closed = that.tabConfig.closed, openTabNum = that.tabConfig.openTabNum;
        tabFilter = that.tabConfig.tabFilter;
        if (_this.find("i.iconfont,i.layui-icon").attr("data-icon") != undefined) {
            var title = '';
            if (that.hasTab(_this.find("cite").text()) == -1 && _this.siblings("dl.layui-nav-child").length == 0) {
                if ($(".layui-tab-title.top_tab li").length == openTabNum) {
                    layer.msg('只能同时打开' + openTabNum + '个选项卡哦。不然系统会卡的！');
                    return;
                }
                tabIdIndex++;
                if (_this.find("i.iconfont").attr("data-icon") != undefined) {
                    title += '<i class="iconfont ' + _this.find("i.iconfont").attr("data-icon") + '"></i>';
                } else {
                    title += '<i class="layui-icon">' + _this.find("i.layui-icon").attr("data-icon") + '</i>';
                }
                title += '<cite>' + _this.find("cite").text() + '</cite>';
                title += '<i class="layui-icon layui-unselect layui-tab-close" data-id="' + tabIdIndex + '">&#x1006;</i>';
                element.tabAdd(tabFilter, {
                    title: title,
                    content: "<iframe src='" + _this.attr("data-url") + "' data-id='" + tabIdIndex + "'></frame>",
                    id: new Date().getTime()
                })
                var curmenu = {
                    "icon": _this.find("i.iconfont").attr("data-icon") != undefined ? _this.find("i.iconfont").attr("data-icon") : _this.find("i.layui-icon").attr("data-icon"),
                    "title": _this.find("cite").text(),
                    "href": _this.attr("data-url"),
                    "layId": new Date().getTime()
                }
                menu.push(curmenu);
                window.sessionStorage.setItem("menu", JSON.stringify(menu));
                window.sessionStorage.setItem("curmenu", JSON.stringify(curmenu));
                element.tabChange(tabFilter, that.getLayId(_this.find("cite").text()));
            } else {
                var curmenu = {
                    "icon": _this.find("i.iconfont").attr("data-icon") != undefined ? _this.find("i.iconfont").attr("data-icon") : _this.find("i.layui-icon").attr("data-icon"),
                    "title": _this.find("cite").text(),
                    "href": _this.attr("data-url"),
                    "layId": new Date().getTime()
                }
                window.sessionStorage.setItem("curmenu", JSON.stringify(curmenu));
                element.tabChange(tabFilter, that.getLayId(_this.find("cite").text()));
            }
        }
    }
    $("body").on("click", ".top_tab li", function () {
        var curmenu = '';
        var menu = JSON.parse(window.sessionStorage.getItem("menu"));
        curmenu = menu[$(this).index() - 1];
        if ($(this).index() == 0) {
            window.sessionStorage.setItem("curmenu", '');
        } else {
            window.sessionStorage.setItem("curmenu", JSON.stringify(curmenu));
            if (window.sessionStorage.getItem("curmenu") == "undefined") {
                if (curNav != JSON.stringify(delMenu)) {
                    window.sessionStorage.setItem("curmenu", curNav);
                } else {
                    window.sessionStorage.setItem("curmenu", JSON.stringify(menu[liIndex - 1]));
                }
            }
        }
        element.tabChange(tabFilter, $(this).attr("lay-id")).init();
    })
    $("body").on("click", ".top_tab li i.layui-tab-close", function () {
        liIndex = $(this).parent("li").index();
        var menu = JSON.parse(window.sessionStorage.getItem("menu"));
        delMenu = menu[liIndex - 1];
        var curmenu = window.sessionStorage.getItem("curmenu") == "undefined" ? "undefined" : window.sessionStorage.getItem("curmenu") == "" ? '' : JSON.parse(window.sessionStorage.getItem("curmenu"));
        if (JSON.stringify(curmenu) != JSON.stringify(menu[liIndex - 1])) {
            curNav = JSON.stringify(curmenu);
        } else {
            if ($(this).parent("li").length > liIndex) {
                window.sessionStorage.setItem("curmenu", curmenu);
                curNav = curmenu;
            } else {
                window.sessionStorage.setItem("curmenu", JSON.stringify(menu[liIndex - 1]));
                curNav = JSON.stringify(menu[liIndex - 1]);
            }
        }
        menu.splice((liIndex - 1), 1);
        window.sessionStorage.setItem("menu", JSON.stringify(menu));
        element.tabDelete("bodyTab", $(this).parent("li").attr("lay-id")).init();
    })
    var bodyTab = new Tab();
    exports("bodyTab", function (option) {
        return bodyTab.set(option);
    });
})