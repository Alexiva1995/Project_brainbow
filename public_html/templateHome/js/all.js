
$(function(){
      $('.nav, .navdown, .footer,.photo-footer ').find('a[href*=#]:not([href=#])').on('click', function () {
          if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                  $('html,body').animate({
                      scrollTop: (target.offset().top - 40)
                  }, 1000);
                 
                  return false;
              }
          }     
      });
});

// 头部

  function initTop(){
    if($(window).scrollTop() > 40){
      $(".hd").addClass('on');
     

    }
    else{$(".hd").removeClass('on');}
  };
   

$(function(){
  $(window).scroll(function(){
     initTop();
  });
  initTop();
});


// 返回顶部
$(function(){
  $(window).scroll(function(){
    if($(window).scrollTop() > 1600){$(".goTop").show()}
      else{$(".goTop").hide();}
  });
  $(".goTop").click(function(){
    $("html,body").stop().animate({scrollTop:0},1000)
  });
});

$(function(){
   // 导航
    // $('.nav ul li').mouseenter(function() {
    //     $(this).find('.con').stop(true,true).slideDown().end().siblings("li").find(".con").slideUp();
    // });
    // $(".nav").mouseleave(function(){
    //     $(this).find(".con").slideUp();
    // })
   $(".nav ul li").hover(function(){
     $(this).children('.con').stop().fadeToggle();
   }); 
   $(".language").hover(function(){
     $(this).children('.con').stop().fadeToggle();
   });

    
});




// 移动的导航
$(function(){
    $(".monav").click(function(){
        $(".monav span").toggleClass('on');
        $(".navdown").slideToggle();
        // $(".motop").addClass('on');
    });

    $(".navdown>ul>li").click(function(){
        $(this).children('.ul2').slideToggle();
        $(this).siblings().children('.ul2').slideUp();
        $(this).find("i").toggleClass("on");
       $(this).siblings("li").find("i").addClass("on").removeClass("on")
      

    });
    $(".navdown .ul2 li").bind("click",function(e){
          e.stopPropagation();//阻止事件冒泡；

    });
    $(".navdown .ul2 li a,.navdown>ul>li .link").click(function(){
      $(".navdown").slideUp();
       $(".monav span").removeClass('on');
    });
});

// 移动底部
$(function(){
    $(".photo-footer>ul>li").click(function(e){
        
        $(this).children('.ul2').slideToggle();
        $(this).siblings("li").find(".ul2").slideUp();
         $(this).find("i").toggleClass("on");
       $(this).siblings("li").find("i").addClass("on").removeClass("on")
    });
     $(".photo-footer>ul>li>ul>li").bind("click",function(e){
         e.stopPropagation();//阻止事件冒泡；

 });
});

// pc底部
$(function(){
  $(".wxqr").hover(function(){
    $(this).children('.con').stop().toggle();
  });
});


