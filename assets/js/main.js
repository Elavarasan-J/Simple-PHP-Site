// Feather Icons
feather.replace();

// Show arrow when scroll
$(window).scroll(function(){
   if($(this).scrollTop() > 100) {
       $('nav.navbar').addClass('fixed');
   }else{
       $('nav.navbar').removeClass('fixed');
   }
});

// Top search
$(document).on('click','.search-icon >a',function(e){
    e.preventDefault();
    $('.top-search-wrapper').addClass('search-in');
    //$('body').css('overflow','visible');
});

$(document).on('click','.btn-search-close',function(e){
    e.preventDefault();
    $('.top-search-wrapper').removeClass('search-in');
    //$('body').css('overflow','visible');
});

// Show arrow when scroll
$(window).scroll(function(){
    if($(this).scrollTop() > 400){
        $('.back-to-top').addClass('in');
    }
    else{
        $('.back-to-top').removeClass('in');
    }
});

// Scroll to top
$(document).on('click','.back-to-top', function(e){
    e.preventDefault();
    $('body,html').animate({
            scrollTop: 0
    }, 1000);
});

// Cart
$(document).on('click','.shopping-bag >a',function(e){
    e.preventDefault();
    $('.mcart-sidebar, .backdrop').addClass('in');
    $('body').css('overflow','hidden');
});

$(document).on('click','.mcart-close, .backdrop',function(e){
    e.preventDefault();
    $('.mcart-sidebar, .backdrop').removeClass('in');
    $('body').css('overflow','auto');
});

// Shades
$(document).on('click','.shade-list >li',function(e){
    e.preventDefault();
    var productSKU, productIMG;
   $('.shade-list >li').removeClass('active');
   
   $(this).addClass('active');
   productSKU = $(this).data('sku');
   productIMG = $(this).data('img');
   
   $('.shade-select').val(productSKU);
   $('.product-image .img-responsive').attr('src', productIMG);
   
});

// Shade Select
$('.shade-select').on('change', function() {
    var currVal = $(this).val();
    $('.shade-list >li').removeClass('active');
    $('.shade-list >li[data-sku="'+currVal+'"]').addClass('active');
    var productIMG = $('.shade-list >li[data-sku="'+currVal+'"]').data('img');
   
   $('.product-image .img-responsive').attr('src', productIMG);
});


// Increment & Decrement

$('.plus').on('click',function(e){
    e.preventDefault();
    $.fn.updateValue(this, +1) ;
});

$('.minus').on('click',function(e){
    e.preventDefault();
    $.fn.updateValue(this, -1) ;
});
 
$.fn.updateValue = function(btn, operator){
  
    var inputBefore, inputAfter;
    inputBefore = $(btn).siblings('.in-val').children();
    inputAfter = parseInt(inputBefore.val(), 10) + operator;
    inputBefore.val(Math.max(inputAfter, 1));
};
 

// Collapse
$(document).on('click','.collapse-link',function(e){
    
    $(".collapse-content.collapse.in").collapse("hide");
    
    $('.sproduct-list >li').removeClass('active');
    $(this).parent().addClass('active');
    $(this).next().collapse('show');
    
});


// File upload
$(document).on('click','.upload-file',function(e){
    e.preventDefault();
    $(this).next().trigger('click');
});

$(document).on('change','.input-file',function(e){
   
   var selectedFile = e.target.value;
    $('.file-name').html(selectedFile);
});
