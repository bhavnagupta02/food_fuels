/*==sticky-nav==*/ 
 // $('.nav').stickyNavbar({
    // activeClass: "active",        
    // sectionSelector: "scrollto",  
    // animDuration: 250,            
    // // startAt: 0,                   
    // easing: "linear",             
    // animateCSS: true,             
    // animateCSSRepeat: false,      
    // cssAnimation: "fadeInDown",   
    // jqueryEffects: false,         
    // jqueryAnim: "slideDown",      
    // // selector: "a",                
    // mobile: false,                
    // mobileWidth: 480,             
    // zindex: 9999,                 
    // stickyModeClass: "sticky",    
    // unstickyModeClass: "unsticky" 
  // });
  
  $(document).ready(function() {
var stickyNavTop = $('.nav').offset().top;
 
var stickyNav = function(){
var scrollTop = $(window).scrollTop();
      
if (scrollTop > stickyNavTop) { 
    $('.nav').addClass('sticky');
} else {
    $('.nav').removeClass('sticky'); 
}
};
 
stickyNav();
 
$(window).scroll(function() {
    stickyNav();
});
});


/*===tabs==*/
 $( "#tabs" ).tabs();
 
 
 /*==testimonail-slider==*/
  $('.testimonial-slider').bxSlider({
	     slideWidth: 1170,
		minSlides: 1,
		maxSlides: 1,
		slideMargin: 0,
		auto: true,
		pager: false
		
	  
  });

  /*==Responsive-nav==*/
 $('#responsive-menu').click(function(){
	$('.nav-menu').slideToggle(); 
 });
 
 $('.notification').click(function(){
	$('.right-sidebar').toggleClass('show') 
 });


$(window).on('load resize', function() {
	if($(window).width() > 979 ){
		$(".nav-menu").removeAttr('style');
				
	}
	
});

$( "#accordion" ).accordion({collapsible: true,});


/*==filter==*/
$('#filterOptions li a').click(function() {
		// fetch the class of the clicked item
		var ourClass = $(this).attr('class');
		
		// reset the active class on all the buttons
		$('#filterOptions li').removeClass('active');
		// update the active state on our clicked button
		$(this).parent().addClass('active');
		
		if(ourClass == 'all') {
			// show all our items
			$('#ourHolder').children('div.item').show();	
		}
		else {
			// hide all elements that don't share ourClass
			$('#ourHolder').children('div:not(.' + ourClass + ')').hide();
			// show all elements that do share ourClass
			$('#ourHolder').children('div.' + ourClass).show();
		}
		return false;
	});
	
	
/*== popup ==*/

$('#login_popup').popup({
  transition: 'all 0.3s',
  scrolllock: true,
});

$('#user_reset_popup').popup({
  transition: 'all 0.3s',
  scrolllock: true,
});

$('#signup_popup').popup({
  transition: 'all 0.3s',
  scrolllock: true, // optional
});

$('#forgot_popup').popup({
  transition: 'all 0.3s',
  scrolllock: true, // optional
});

if($(".message.success").length){
	setTimeout(function()
	{
	  $('.message.success').delay(10000).fadeOut('slow');
	}, 1000);  
}

if($(".message.error").length){
	setTimeout(function() {
	      $('.message.error').delay(10000).fadeOut('slow');
	}, 1000);  
}

if($(".tooltips").length){
	setTimeout(function()
	{
	  $('.tooltips').delay(5000).fadeOut('slow');
	}, 1000);  
}


$('.login_here').click(function(){
	$('#signup_popup').popup('hide');
	$('#login_popup').popup('show');
});

$('.signup_here').click(function(){
	$('#login_popup').popup('hide');
	$('#signup_popup').popup('show');
});

$('.forget-pass').click(function(){
	$('#login_popup').popup('hide');
	$('#forgot_popup').popup('show');
});

if($('#UserSignupForm').length){
	$('#UserSignupForm').submit(function(event){
		event.preventDefault();
		if(!$('input[name="terms"]').is(':checked')){
			$('#signupTooltip span').html('Please accept the Terms.');
			$('#signupTooltip').show();
			return false;
		}

		$.ajax({
            'url'		: 	base_url+'users/register',
            'type'		: 	'post',
            'dataType'	:   'json',
            'data'		: 	$(this).serialize(),
            'success'	: 	function(data){ 
            	if(data.status == 1){
            		/* $('.signup_popup_close').click();
					$('.login_popup_open').click();
					$('#loginTooltip span').html(data.message);
					$('#loginTooltip').show();  */
					window.location = data.url;
				}
				else{
					$('#signupTooltip span').html(data.message);
					$('#signupTooltip').show();
				}
				$('.tooltips').delay(5000).fadeOut('slow');
	        }
        });
	});
}

if($('#UserLoginForm').length){
	$('#UserLoginForm').submit(function(event){
		event.preventDefault();
		$.ajax({
            'url'		: 	base_url+'users/login',
            'type'		: 	'post',
            'dataType'	:   'json',
            'data'		: 	$(this).serialize(),
            'success'	: 	function(data){ 
            	if(data.status == 0){
            		$('#loginTooltip span').html(data.message);
					$('#loginTooltip').show();
					$('.tooltips').delay(5000).fadeOut('slow');
				}
				else{
					window.location = data.url;
				}
	        }
        });
	});
}

if($('#UserForgotForm').length){
	$('#UserForgotForm').submit(function(event){
		event.preventDefault();
		$.ajax({
            'url'		: 	base_url+'users/forgot_password',
            'type'		: 	'post',
            'dataType'	:   'json',
            'data'		: 	$(this).serialize(),
            'success'	: 	function(data){ 
            	//if(data.status == 0){
            		$('#forgotTooltip span').html(data.message);
					$('#forgotTooltip').show();
					$('.tooltips').delay(5000).fadeOut('slow');
				//}
	        }
        });
	});
}

$(".numeric").keydown(function (e) {
  // Allow: backspace, delete, tab, escape, enter and .
  if(e.which){
    if ($.inArray(e.which, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.which == 65 && e.ctrlKey === true) || 
         // Allow: home, end, left, right, down, up
        (e.which >= 35 && e.which <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.which < 48 || e.which > 57)) && (e.which < 96 || e.which > 105)) {
        e.preventDefault();
    }
  }
});

$(".alphanumeric").keydown(function (e) {
   // Allow: backspace, delete, tab, escape, enter and .
  if(e.which){
    if ($.inArray(e.which, [32, 37, 39, 46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A
        (e.which == 65 && e.ctrlKey === true) || 
         // Allow: home, end, left, right, down, up
        (e.which >= 65 && e.which <= 90) || 
         // Allow: A-Z
        (e.which >= 97 && e.which <= 122) || 
         // Allow: a-z
        (e.which >= 35 && e.which <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.which < 48 || e.which > 57)) && (e.which < 96 || e.which > 105)) {
      e.preventDefault();
    }
  }
});

$(".countCheck").keydown(function (e) {
  var totalCount = $(this).attr('maxlength');
  var remainChar = totalCount-parseInt($(this).val().length);
  $(this).siblings('.char-info').html(remainChar+' characters');
  $(this).val($(this).val().substr(0, totalCount));
});