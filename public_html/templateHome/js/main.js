AOS.init({
    easing: 'ease-out-back',
    duration: 1000
});

$(function() {
    $("img.lazy").lazyload({
        effect : "fadeIn",
        threshold: '300'
    });
});
// 应用场景
var Swiper1 = new Swiper('.usage-nav-container', {
    // autoplay: 15000,
    speed: 600,
    slidesPerView: 'auto',

    centeredSlides: true,
    loop: true,
    paginationClickable: true,
    prevButton: '.usage-prev',
    nextButton: '.usage-next',


});
var Swiper2 = new Swiper('.usage-main-container', {

    speed: 600,
    slidesPerView: 'auto',
    effect: 'fade',
    prevButton: '.usage-prev',
    nextButton: '.usage-next',
    centeredSlides: true,
    loop: true,
    swipeHandler: '.swipe-handler',
});

var Swiper3 = new Swiper('.usage-container', {

    pagination: '.usage-pag',
    paginationClickable: true,
    slidesPerView: 1,
    observer: true, //修改swiper自己或子元素时，自动初始化swiper
    observeParents: true, //修改swiper的父元素时，自动初始化swiper
    clickable: true,


});
// Swiper1.params.control = Swiper2;//需要在Swiper2初始化后，Swiper1控制Swiper2
// Swiper2.params.control = Swiper1;//需要在Swiper1初始化后，Swiper2控制Swiper1

// $(function () {

//     $(".usage-main").css("marginLeft", $(".ind-usage .content").offset().left);

// });


//    $('.usage-main').slick({

//            slidesToShow: 1,
//             slidesToScroll: 1,
//             dots: false,
//             arrows: false,
//             asNavFor: '.usage-nav',
//             fade: true,
//             infinite: false,
//             // touchMove: false,

//             // swipeToSlide: false,
//             adaptiveHeight: true, //高度自由
// });
// $('.usage-con').slick({

//            slidesToShow: 1,
//             slidesToScroll: 1,
//             dots: true,

//             arrows: false,
//             // asNavFor: '.usage-nav',
//             // fade: true,
//             infinite: false,
//             // touchMove: false,
//             swipeToSlide: false,
//             adaptiveHeight: true, //高度自由
// });


$('.scenariosMain').slick({
    dots: true,

    slidesToShow: 6,
    slidesToScroll: 1,
    fade: false,
    infinite: false,
    arrows: false,
    responsive: [

        {
            breakpoint: 960,
            settings: {
                slidesToShow: 3,

            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 2,

            }
        }
    ]

});
// 新增视频切换
$('.ind-videoPic').slick({
    dots: false,

    slidesToShow: 1,
    slidesToScroll: 1,
    fade: false,
    infinite: false,
    arrows: true,


});
// 视频
$(function () {
    $(".videoop").click(function () {
        var _link = $(this).attr("fileurl");
        $(".popup video").attr("src", _link);
        $(".popup").show();
        $(".popup video").trigger('play')

    });
    $(".videopop .close").click(function () {
        $(".popup").hide();
        $('.popup video').trigger("pause");
    });
    $(".popup .bg").click(function () {
        $(".popup").hide();
        $('.popup video').trigger("pause");
    });
});

$(function () {
    var wow = new WOW({
        offset: 20
    });
    wow.init();
});

$(function () {
    $(".ind-row2 .box:odd").addClass("on");

});

$(function () {
    $(function () {
        $(".banicon").click(function () {
            $("html,body").stop().animate({scrollTop: 900}, 400);
        });

    });
});
// 合作伙伴
$(function(){
    $(".partnerUl li").click(function(){
        $(this).addClass('on').siblings('li').removeClass('on');

        var _index = $(this).index();
        $('.partnerBox').eq(_index).show().addClass('on').siblings('.partnerBox').removeClass('on');
    });

});
// 路线图
$(function () {
    $(".scenarios .swiper-slide:odd .con").addClass('con2');
});
var mySwiper = new Swiper('.scenarios', {
    // autoplay: 8000,
    slidesPerView: 'auto',
    autoplayDisableOnInteraction: false, //鼠标滑动后继续自动滑动
    pagination: '.mg-pag',
    paginationType: 'progress',
    paginationClickable: true,
    prevButton: '.mg-prev',
    nextButton: '.mg-next',
    slidesPerView: 5,
    breakpoints: {

        1400: {
            slidesPerView: 4,

        },
        1200: {
            slidesPerView: 3,

        },
        767: {
            slidesPerView: 2,

        },
        320: {
            slidesPerView: 1,

        },
    }

});

// 团队
var mySwiper = new Swiper('.team-con', {
    autoplay: 5000,//可选选项，自动滑动
    slidesPerView: 3,
    slidesPerColumn: 2,
    // slidesPerColumnFill : 'row',
    prevButton: '.team-prev',
    nextButton: '.team-next',
    breakpoints: {

        960: {
            slidesPerView: 2,

        },

        640: {
            slidesPerView: 1,

        }
    }
});


var mySwiper = new Swiper('.partner-con', {
    autoplay: 8000,
    slidesPerView: 3,
    slidesPerColumn: 5,
    pagination: '.partner-pag',
    paginationClickable: true,
    // slidesPerColumnFill : 'row',
    calculateHeight: true, //高度自适应
    resizeReInit: true, ///宽度改变初始化
    observer: true, //修改swiper自己或子元素时，自动初始化swiper
    observeParents: true, //修改swiper的父元素时，自动初始化swiper
    resizeReInit: true, ///宽度改变初始化

    breakpoints: {

        1200: {},

        640: {}
    }
});


;(function (window) {
    function Dotline(option) {
        this.opt = this.extend({
            dom: 'J_dotLine',//画布id
            cw: 1920,//画布宽
            ch: 500,//画布高
            ds: 100,//点的个数
            r: 0.5,//圆点半径
            cl: '#000',//颜色
            dis: 100//触发连线的距离
        }, option);
        this.c = document.getElementById(this.opt.dom);//canvas元素id
        this.ctx = this.c.getContext('2d');
        this.c.width = this.opt.cw;//canvas宽
        this.c.height = this.opt.ch;//canvas高
        this.dotSum = this.opt.ds;//点的数量
        this.radius = this.opt.r;//圆点的半径
        this.disMax = this.opt.dis * this.opt.dis;//点与点触发连线的间距
        this.color = this.color2rgb(this.opt.cl);//设置粒子线颜色
        this.dots = [];
        //requestAnimationFrame控制canvas动画
        var RAF = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
            window.setTimeout(callback, 1000 / 60);
        };
        var _self = this;
        //增加鼠标效果
        var mousedot = {x: null, y: null, label: 'mouse'};
        this.c.onmousemove = function (e) {
            var e = e || window.event;
            mousedot.x = e.clientX - _self.c.offsetLeft;
            mousedot.y = e.clientY - _self.c.offsetTop;
        };
        this.c.onmouseout = function (e) {
            mousedot.x = null;
            mousedot.y = null;
        }
        //控制动画
        this.animate = function () {
            _self.ctx.clearRect(0, 0, _self.c.width, _self.c.height);
            _self.drawLine([mousedot].concat(_self.dots));
            RAF(_self.animate);
        };
    }

    //合并配置项，es6直接使用obj.assign();
    Dotline.prototype.extend = function (o, e) {
        for (var key in e) {
            if (e[key]) {
                o[key] = e[key]
            }
        }
        return o;
    };
    //设置线条颜色(参考{抄袭}张鑫旭大大，http://www.zhangxinxu.com/wordpress/2010/03/javascript-hex-rgb-hsl-color-convert/)
    Dotline.prototype.color2rgb = function (colorStr) {
        var red = null,
            green = null,
            blue = null;
        var cstr = colorStr.toLowerCase();//变小写
        var cReg = /^#[0-9a-fA-F]{3,6}$/;//确定是16进制颜色码
        if (cstr && cReg.test(cstr)) {
            if (cstr.length == 4) {
                var cstrnew = '#';
                for (var i = 1; i < 4; i++) {
                    cstrnew += cstr.slice(i, i + 1).concat(cstr.slice(i, i + 1));
                }
                cstr = cstrnew;
            }
            red = parseInt('0x' + cstr.slice(1, 3));
            green = parseInt('0x' + cstr.slice(3, 5));
            blue = parseInt('0x' + cstr.slice(5, 7));
        }
        return red + ',' + green + ',' + blue;
    }
    //画点
    Dotline.prototype.addDots = function () {
        var dot;
        for (var i = 0; i < this.dotSum; i++) {//参数
            dot = {
                x: Math.floor(Math.random() * this.c.width) - this.radius,
                y: Math.floor(Math.random() * this.c.height) - this.radius,
                ax: (Math.random() * 2 - 1) / 1.5,
                ay: (Math.random() * 2 - 1) / 1.5
            }
            this.dots.push(dot);
        }
    };
    //点运动
    Dotline.prototype.move = function (dot) {
        dot.x += dot.ax;
        dot.y += dot.ay;
        //点碰到边缘返回
        dot.ax *= (dot.x > (this.c.width - this.radius) || dot.x < this.radius) ? -1 : 1;
        dot.ay *= (dot.y > (this.c.height - this.radius) || dot.y < this.radius) ? -1 : 1;
        //绘制点
        this.ctx.beginPath();
        this.ctx.arc(dot.x, dot.y, this.radius, 0, Math.PI * 2, true);
        this.ctx.stroke();
    };
    //点之间画线
    Dotline.prototype.drawLine = function (dots) {
        var nowDot;
        var _that = this;
        //自己的思路：遍历两次所有的点，比较点之间的距离，函数的触发放在animate里
        this.dots.forEach(function (dot) {

            _that.move(dot);
            for (var j = 0; j < dots.length; j++) {
                nowDot = dots[j];
                if (nowDot === dot || nowDot.x === null || nowDot.y === null) continue;//continue跳出当前循环开始新的循环
                var dx = dot.x - nowDot.x,//别的点坐标减当前点坐标
                    dy = dot.y - nowDot.y;
                var dc = dx * dx + dy * dy;
                if (Math.sqrt(dc) > Math.sqrt(_that.disMax)) continue;
                // 如果是鼠标，则让粒子向鼠标的位置移动
                if (nowDot.label && Math.sqrt(dc) > Math.sqrt(_that.disMax) / 2) {
                    dot.x -= dx * 0.02;
                    dot.y -= dy * 0.02;
                }
                var ratio;
                ratio = (_that.disMax - dc) / _that.disMax;
                _that.ctx.beginPath();
                _that.ctx.lineWidth = ratio / 2;
                _that.ctx.strokeStyle = 'rgba(' + _that.color + ',' + parseFloat(ratio + 0.2).toFixed(1) + ')';
                _that.ctx.moveTo(dot.x, dot.y);
                _that.ctx.lineTo(nowDot.x, nowDot.y);
                _that.ctx.stroke();//不描边看不出效果

                //dots.splice(dots.indexOf(dot), 1);
            }
        });
    };
    //开始动画
    Dotline.prototype.start = function () {
        var _that = this;
        this.addDots();
        setTimeout(function () {
            _that.animate();
        }, 100);
    }
    window.Dotline = Dotline;
}(window));
//调用
window.onload = function () {
        var dotline = new Dotline({
            dom: 'J_dotLine',//画布id
            cw: 1920,//画布宽
            ch: 500,//画布高
            ds: 200,//点的个数
            r: 0.8,//圆点半径
            cl: '#d4e2ea',//粒子线颜色
            dis: 100//触发连线的距离
        }).start();
    }


var waves = new SineWaves({
    el: document.getElementById('waves'),

    speed: 4,

    width: function () {
        return $(window).width();
    },

    height: function () {
        return $(window).height();
    },

    ease: 'SineInOut',

    wavesWidth: '100%',

    waves: [{
        timeModifier: 4,
        lineWidth: 2,
        amplitude: -100,
        wavelength: 30
    }, {
        timeModifier: 4,
        lineWidth: 1,
        amplitude: -50,
        wavelength: 25
    },
        {
            timeModifier: 4,
            lineWidth: 0.7,
            amplitude: -40,
            wavelength: 30
        }
    ],

    // Called on window resize
    resizeEvent: function () {
        var gradient = this.ctx.createLinearGradient(0, 0, this.width, 0);
        gradient.addColorStop(0, "rgba(1,166,227,1)");

        var index = -1;
        var length = this.waves.length;
        while (++index < length) {
            this.waves[index].strokeStyle = gradient;
        }

        // Clean Up
        index = void 0;
        length = void 0;
        gradient = void 0;
    }

});

$.ajax({url:"https://pchain.org/getPlatform",success:function(result){
        $('.wallet').attr('href',result.url)
    }});
