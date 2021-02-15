<div class="footer__graphic" style="background-image: url({{ asset('images/svg/mountains.svg') }})"></div>

<div class="footer"> 
    <div class="container"> 
        <div class="row">

            <div class="col-md-4">
                <div class="f-about" style='color: #fff;'>
                   
                   <h4>About as</h4>
                   
                   {!!$site -> short_description!!}
                   <a href="{{route('about_us_page')}}">More</a>
                   
               </div>
           </div>

           <div class="col-md-4">
               <div class="f-links">
                   <h4>Links</h4>
                   <ul>
                     <li> <a href="{{route('shop_index')}}">@lang('shop.shop menu products')</a> </li>
                     <li> <a href="{{route('shop_index')}}">@lang('shop.shop menu about us')</a> </li>
                     <li> <a href="{{route('login')}}">@lang('shop.shop login')</a> </li>
                   </ul>
               </div>
           </div>

           <div class="col-md-4">
               <div class="flick">
                   <h4>Share</h4>
               </div>
           </div>
            
        </div>
    </div>
</div>

{{-- <script type="text/javascript">
    // beck to top
    $('body').append('<div id="backToTop" class="btn btn-lg"><span class="glyphicon glyphicon-chevron-up"></span></div>');
        $(window).scroll(function () {
            if ($(this).scrollTop() <= 200) {
                $('#backToTop').fadeOut();
            } else {
                $('#backToTop').fadeIn();
            }
        }); 
    $('#backToTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
    // and back to top
</script>     --}}
    
<!--analytics.google.com-->

<!-- Global site tag (gtag.js) - Google Analytics -->
<!--     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-1"></script>
        <script>
          //window.dataLayer = window.dataLayer || [];
          //function gtag(){dataLayer.push(arguments);}
          //gtag('js', new Date());
        
          //gtag('config', 'UA-101870435-1');
    </script> -->
<!-- And analytics.google.com-->