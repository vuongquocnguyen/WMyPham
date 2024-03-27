(function($) {
    'use strict';

    jQuery(document).ready(function() {

    	var bargainModalLoop = 0;
    	localStorage.setItem("bargainModalLoop",  bargainModalLoop );

    	var exit_intent_handler_bargain = 0;
    	var scroll_open_handler_bargain = 0;
    	var exit_intent_once_time_bargain = 0;
    	var auto_open_handler_bargain = 0;
    	var re_target_handler_bargain = 0;


    	//bargain modal initiate function
	    $(document).on('click', '.woo_minimum_accept_price-bargin-modal', function(e){
	    	e.preventDefault();
	    	$('.woo-chatbot-product-bargain-price').val('');
	    	$('.woo-chatbot-product-bargain-msg').html('');
            $.magnificPopup.open({items: {src: '#wpbot-map-product-modal'},type: 'inline'}, 0);

            $('#wpbot-map-product-modal').closest('.mfp-wrap').addClass('wpbot-map-wraps');

            setTimeout(function() { 

            	$(".woo-chatbot-product-bargain-price").focus();

            }, 1000);
		
	    });


    	//bargain modal initiate function
	    $(document).on('click', '.woo-chatbot-product-bargain-submit', function(e){
	    	//e.preventDefault();
	    	var product_id = $(this).attr('product-id');
	    	var price = $('.woo-chatbot-product-bargain-price').val();
	    	if( price == '') {
	    		$('.woo-chatbot-product-bargain-price').addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$('.woo-chatbot-product-bargain-price').removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}
            var price = price.match(/\d+(?:\.\d+)?/g).map(Number);

	    	$('.woo-chatbot-product-bargain-price').prop("disabled", true);
	        $('.woo-chatbot-product-bargain-msg').show();
	    	$('.woo-chatbot-product-bargain-msg').html('<p class="woo-chatbot-product-bargain-price_nego">'+ qcld_map_price_negotiating_test +'</p>');
	    	$('.woo-chatbot-product-bargain-submit').hide();


	    	var data = {'action':'qcld_woo_bargin_product_modal_price',
				    	'qcld_woo_map_product_id':product_id, 
				    	'price': parseFloat(price),
			            'security': qcld_map_pro_get_ajax_nonce
				    };
            
	    	setTimeout(function(){
	        jQuery.post(qcld_map_ajaxurl, data, function (response) {
	        	//console.log(response);


	        	if(response.confirm == 'agree'){
	        	
	        		$('.woo-chatbot-products-bargain-wrap').html('');
	        		$('.woo-chatbot-products-bargain-wrap').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><input type="text" class="woo-chatbot-bargain-qty" name="qty" value="1"><span class="qcld-modal-bargin-add-to-cart" product-id="'+product_id+'" variation-id=""  price="'+response.sale_price+'" > '+qcld_minimum_accept_modal_yes_button_text+' </span> <span> '+qcld_minimum_accept_modal_or_button_text+' </span><span class="qcld-modal-bargin-confirm-btn-no" product-id="'+product_id+'" confirm-data="no"> '+qcld_minimum_accept_modal_no_button_text+' </span></div>');


	        		var bargainModalLoop = 0;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );
	        		//console.log(bargainModalLoop);

	        	}else if(response.confirm == 'success'){

	        		var bargainModalLoop = parseFloat(localStorage.getItem("bargainModalLoop")) + 1;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		if(localStorage.getItem("bargainModalLoop") == 3){

                        var your_low_price_alert  = qcld_map_your_low_price_alert;
                        var confirmBtn1  = your_low_price_alert.replace("{offer price}", price + qcld_map_currency_symbol);
                        var your_too_low_price_alert  = qcld_map_your_too_low_price_alert;
                        var restWarning  = your_too_low_price_alert.replace("{minimum amount}", response.sale_price + qcld_map_currency_symbol);

	        			$('.woo-chatbot-products-bargain-wrap').html('');
	        			$('.woo-chatbot-products-bargain-wrap').html('<div class="woo-chatbot-product-bargain-msg-success"></div>');
	        			$('.woo-chatbot-product-bargain-msg-success').html('<p>' + confirmBtn1 + '</p>');
	        			$('.woo-chatbot-product-bargain-msg-success').append('<p>' +restWarning+ '</p>');
	        			$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><input type="text" class="woo-chatbot-bargain-qty" name="qty" value="1"><span class="qcld-modal-bargin-add-to-cart" product-id="'+product_id+'" variable-id="" price="'+response.sale_price+'" > '+qcld_minimum_accept_modal_yes_button_text+' </span> <span> '+qcld_minimum_accept_modal_or_button_text+' </span><span class="qcld-modal-bargin-confirm-btn-no" product-id="'+product_id+'" confirm-data="no"> '+qcld_minimum_accept_modal_no_button_text+' </span></div>');

	        			var bargainModalLoop = 0;
	        			localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		}else{

	        			$('.woo-chatbot-product-bargain-msg').html(response.html);
	        			$('.woo-chatbot-product-bargain-msg').show();
	    				$('.woo-chatbot-product-bargain-price').prop("disabled", false);
	    	    		$('.woo-chatbot-product-bargain-submit').show();


	        		}

	        		

	        	}else{

	        		var bargainModalLoop = 0;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		$('.woo-chatbot-product-bargain-msg').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg').show();


	    			$('.woo-chatbot-product-bargain-price').prop("disabled", false);
	    	    	$('.woo-chatbot-product-bargain-submit').show();

	        	}
	            
	        });

            }, 5000);

	    	$('.woo-chatbot-product-bargain-price').prop( "disabled", false );

            
		
	    });

	    $(document).on("keypress", ".woo-chatbot-product-bargain-price", function (e) {

		    var key = e.which;
			if(key == 13){

		       	$( ".woo-chatbot-product-bargain-submit" ).click();
			    return false;  

			}

		});

	    $(document).on("click", ".qcld-modal-bargin-confirm-btn-yes", function (e) {

	    	var product_id = $(this).attr('product-id');
	    	var price 	   = $(this).attr('price');

		   var data = {
				   	'action':'qcld_woo_bargin_product_modal_price',
				   	'qcld_woo_map_product_id':product_id, 
				   	'price': price,
			        'security': qcld_map_pro_get_ajax_nonce
		   		};
            
	    	setTimeout(function(){
		        jQuery.post(qcld_map_ajaxurl, data, function (response) {
		        	
		        	$('.woo-chatbot-products-bargain-wrap').html('');
		        	$('.woo-chatbot-products-bargain-wrap').html(response.html);

		        	var bargainModalLoop = 0;
		        	localStorage.setItem("bargainModalLoop",  bargainModalLoop );

				});
			});


    	});

	    $(document).on("click", ".qcld-modal-bargin-confirm-btn-no", function (e) {

	    	var product_id  = $(this).attr('product-id');
            
	    	setTimeout(function(){

	            $('.woo-chatbot-products-bargain-wrap').html('');
	        	$('.woo-chatbot-products-bargain-wrap').html('<div class="woo-chatbot-product-bargain-msg-success"></div>');
	        	$('.woo-chatbot-product-bargain-msg-success').append('<p>' + qcld_map_talk_to_boss + '</p>');
	        	$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-products-bargain-info"><input type="number" name="woo-chatbot-product-bargain-price" class="woo-chatbot-product-bargain-offer-price" placeholder="'+qcld_map_currency_symbol+'"><button type="button" class="woo-modal-product-bargain-price-submit" product-id="'+product_id+'">'+ qcld_map_modal_submit_button +'</button></div>');

			});


            setTimeout(function() { 

            	$(".woo-chatbot-product-bargain-offer-price").focus();

            }, 1000);


    	});

	    $(document).on("click", ".woo-modal-product-bargain-price-submit", function (e) {

	    	var product_id  = $(this).attr('product-id');

	    	var offer_price = $('.woo-chatbot-product-bargain-offer-price').val();
	    	if( offer_price == '' ) {
	    		$('.woo-chatbot-product-bargain-offer-price').addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$('.woo-chatbot-product-bargain-offer-price').removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}
           
            var offer_price = offer_price.match(/\d+(?:\.\d+)?/g).map(Number);
            
	    	setTimeout(function(){

	            $('.woo-chatbot-products-bargain-wrap').html('');
	        	$('.woo-chatbot-products-bargain-wrap').html('<div class="woo-chatbot-product-bargain-msg-success"></div>');
	        	$('.woo-chatbot-product-bargain-msg-success').append('<p>' + qcld_map_get_email_address + '</p>');
	        	$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-products-bargain-info"><input type="email" name="woo-chatbot-product-bargain-email" class="woo-modal-product-bargain-offer-email"><button type="button" class="woo-modal-product-bargain-email-submit" product-id="'+product_id+'" price="'+parseFloat(offer_price)+'">'+ qcld_map_modal_submit_button +'</button></div>');

			});

			
            setTimeout(function() { 

            	$(".woo-modal-product-bargain-offer-email").focus();

            }, 1000);


    	});



	    $(document).on("keypress", ".woo-chatbot-product-bargain-offer-price", function (e) {

		    var key = e.which;
			if(key == 13){

		       	$( ".woo-modal-product-bargain-price-submit" ).click();
			    return false;  

			}

		});


	    $(document).on("click", ".woo-modal-product-bargain-email-submit", function (e) {

	    	var product_id  = $(this).attr('product-id');
	    	var offer_price  = $(this).attr('price');

	    	var email_address = $('.woo-modal-product-bargain-offer-email').val();
	    	if( email_address == '') {
	    		$('.woo-modal-product-bargain-offer-email').addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$('.woo-modal-product-bargain-offer-email').removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}else{

	    		var expr = new RegExp(/^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i);

			    if (!expr.test(email_address)) {

		    		$('.woo-modal-product-bargain-offer-email').addClass('woo-chatbot-shake');

		    		setTimeout(function(){
		    			$('.woo-modal-product-bargain-offer-email').removeClass('woo-chatbot-shake');
	                }, 700);

		    		return;

			    }

	    	}

		    var data = {
				    	'action':'qcld_woo_bargin_product_modal_disagree',
				    	'qcld_woo_map_product_id':product_id, 
				    	'offer_price': offer_price, 
				    	'email': email_address,
			            'security': qcld_map_pro_get_ajax_nonce
		    		};
            
	    	setTimeout(function(){
		        jQuery.post(qcld_map_ajaxurl, data, function (response) {

	                $('.woo-chatbot-products-bargain-wrap').html('');
	        		$('.woo-chatbot-products-bargain-wrap').html('<div class="woo-chatbot-product-bargain-msg-success"></div>');
	        		$('.woo-chatbot-product-bargain-msg-success').append('<h6>' + qcld_map_thanks_test + '</h6>');
	        		
				});
			});


    	});


	    $(document).on("keypress", ".woo-modal-product-bargain-offer-email", function (e) {

		    var key = e.which;
			if(key == 13){

		       	$( ".woo-modal-product-bargain-email-submit" ).click();
			    return false;  

			}

		});




		//bargain modal initiate function for variable product
	    $(document).on('click', '.woo_minimum_accept_price-bargin-varialbe-modal', function(e){
	    	e.preventDefault();
	    	
	    	var product_id = $(this).attr('product_id');
	    	var variation_id = $('.variation_id').val();

	    	if( variation_id == '0' || variation_id == '' ) {

	    		alert('Please select some product options before adding this product to your cart.');
	    		return false;

	    	}

	    	$('.woo_minimum_accept_price-bargin-varialbe-modal').addClass('woo-chatbot-product-variation-move');

	    	var data = {
	    		'action':'qcld_woo_bargin_product_variation',
	    		'qcld_woo_map_product_id':product_id, 
	    		'qcld_woo_map_variation_id': variation_id,
	    		'security': qcld_map_pro_get_ajax_nonce
	    	};
            
	    	setTimeout(function(){
	        	jQuery.post(qcld_map_ajaxurl, data, function (response) {

	        		$('.woo_minimum_accept_price-bargin-varialbe-modal').removeClass('woo-chatbot-product-variation-move');

	        		//console.log(response);
	        		$('.woo_minimum_accept_price-field-wrapper').after(response.html);
            		$.magnificPopup.open({items: {src: '#wpbot-map-product-modal'},type: 'inline'}, 0);
           
            		$('#wpbot-map-product-modal').closest('.mfp-wrap').addClass('wpbot-map-wraps');

            		setTimeout(function() { 

            			$(".woo-chatbot-product-bargain-price").focus();

            		}, 1000);
            		
		
	    		});
	    	});


	    });




    	//bargain modal initiate function
	    $(document).on('click', '.woo-chatbot-product-bargain-variation-submit', function(e){
	    	//e.preventDefault();
	    	var product_id = $(this).attr('product-id');
	    	var variation_id = $(this).attr('variation-id');
	    	var price = $('.woo-chatbot-product-bargain-price').val();
	    	if( price == '') {
	    		$('.woo-chatbot-product-bargain-price').addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$('.woo-chatbot-product-bargain-price').removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}
            var price = price.match(/\d+(?:\.\d+)?/g).map(Number);

	    	$('.woo-chatbot-product-bargain-price').prop("disabled", true);
	        $('.woo-chatbot-product-bargain-msg').show();
	    	$('.woo-chatbot-product-bargain-msg').html('<p class="woo-chatbot-product-bargain-price_nego">'+ qcld_map_price_negotiating_test +'</p>');
	    	$('.woo-chatbot-product-bargain-variation-submit').hide();


	    	var data = {
	    		'action':'qcld_woo_bargin_product_modal_variation_price', 
	    		'qcld_woo_map_product_id': product_id, 
	    		'qcld_woo_map_variation_id': variation_id, 
	    		'price': parseFloat(price),
	    		'security': qcld_map_pro_get_ajax_nonce
	    	};
            
	    	setTimeout(function(){
	        jQuery.post(qcld_map_ajaxurl, data, function (response) {
	        	//console.log(response);

	        	if(response.confirm == 'agree'){
	        	
	        		$('.woo-chatbot-products-bargain-wrap').html('');
	        		$('.woo-chatbot-products-bargain-wrap').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><input type="text" class="woo-chatbot-bargain-qty" name="qty" value="1"><span class="qcld-modal-bargin-confirm-add-to-cart" product-id="'+product_id+'" variation-id="'+variation_id+'"  price="'+response.sale_price+'" > '+qcld_minimum_accept_modal_yes_button_text+' </span> <span> '+qcld_minimum_accept_modal_or_button_text+' </span><span class="qcld-modal-bargin-confirm-btn-no" product-id="'+product_id+'" confirm-data="no"> '+qcld_minimum_accept_modal_no_button_text+' </span></div>');

	        		var bargainModalLoop = 0;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        	}else if(response.confirm == 'success'){

	        		var bargainModalLoop = parseFloat(localStorage.getItem("bargainModalLoop")) + 1;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		if(localStorage.getItem("bargainModalLoop") == 3){

                        var your_low_price_alert  = qcld_map_your_low_price_alert;
                        var confirmBtn1  = your_low_price_alert.replace("{offer price}", price + qcld_map_currency_symbol);
                        var your_too_low_price_alert  = qcld_map_your_too_low_price_alert;
                        var restWarning  = your_too_low_price_alert.replace("{minimum amount}", response.sale_price + qcld_map_currency_symbol);

	        			$('.woo-chatbot-products-bargain-wrap').html('');
	        			$('.woo-chatbot-products-bargain-wrap').html('<div class="woo-chatbot-product-bargain-msg-success"></div>');
	        			$('.woo-chatbot-product-bargain-msg-success').html('<p>' + confirmBtn1 + '</p>');
	        			$('.woo-chatbot-product-bargain-msg-success').append('<p>' +restWarning+ '</p>');
	        			$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><input type="text" class="woo-chatbot-bargain-qty" name="qty" value="1"><span class="qcld-modal-bargin-confirm-add-to-cart" product-id="'+product_id+'"  variation-id="'+variation_id+'" price="'+response.sale_price+'" > '+qcld_minimum_accept_modal_yes_button_text+' </span> <span> '+qcld_minimum_accept_modal_or_button_text+' </span><span class="qcld-modal-bargin-confirm-btn-no" product-id="'+product_id+'" confirm-data="no"> '+qcld_minimum_accept_modal_no_button_text+' </span></div>');

	        			var bargainModalLoop = 0;
	        			localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		}else{

	        			$('.woo-chatbot-product-bargain-msg').html(response.html);
	        			$('.woo-chatbot-product-bargain-msg').show();
	    				$('.woo-chatbot-product-bargain-price').prop("disabled", false);
	    	    		$('.woo-chatbot-product-bargain-variation-submit').show();

	        		}

	        	}else{

	        		var bargainModalLoop = 0;
	        		localStorage.setItem("bargainModalLoop",  bargainModalLoop );

	        		$('.woo-chatbot-product-bargain-msg').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg').show();

	    			$('.woo-chatbot-product-bargain-price').prop("disabled", false);
	    	    	$('.woo-chatbot-product-bargain-variation-submit').show();

	        	}
	    			
	            
	        });

            }, 5000);

	    	$('.woo-chatbot-product-bargain-price').prop( "disabled", false );
		
	    });


	    $(document).on("keypress", ".woo-chatbot-product-bargain-price", function (e) {

		    var key = e.which;
			if(key == 13){

		       	$( ".woo-chatbot-product-bargain-variation-submit" ).click();
			    return false;  
			}

		});


		//bargain variable product add to cart
	    $(document).on('click', '.qcld-modal-bargin-confirm-add-to-cart', function(e){
	    	
	    	var product_id 		= $(this).attr('product-id');
	    	var variation_id 	= $(this).attr('variation-id');
	    	var price 			= $(this).attr('price');
	    	var product_qty 	= $(this).attr('product-qty');

	    	var product_qty = product_qty || 1;

	    	if( product_id == '' ) {
	    		$(this).addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$(this).removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}

	        $('.woo-chatbot-product-bargain-msg-success').show();
	    	$('.woo-chatbot-product-bargain-msg-success').html('<p class="woo-chatbot-product-bargain-price_nego">'+ qcld_map_pro_added_to_cart_msg +'</p>');

	    	if( variation_id == '' ) {

		    	var data = {
		    		'action':'qcld_woo_bargin_product_add_to_cart', 
		    		'product_id': product_id,  
		    		'price': price,
		    		'product_qty': product_qty,
		    		'security': qcld_map_pro_get_ajax_nonce
		    	};
		    	
		    }else{

		    	var data = {
		    		'action':'qcld_woo_bargin_product_variation_add_to_cart', 
		    		'product_id': product_id, 
		    		'variation_id': variation_id, 
		    		'price': price,
		    		'product_qty': product_qty,
		    		'security': qcld_map_pro_get_ajax_nonce
		    	};

		    }
            
	    	setTimeout(function(){
	        	jQuery.post(qcld_map_ajaxurl, data, function (response) {
	        	
	        		$('.woo-chatbot-product-bargain-msg-success').html('');
	        		$('.woo-chatbot-product-bargain-msg-success').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><a href="'+qcld_map_pro_get_checkout_url +'" class="qcld-modal-bargin-confirm-btn-checkout"> '+qcld_map_pro_checkout_now_button_text+' </a></div>');


	        		jQuery("[name='update_cart']").removeAttr('disabled');
	        		jQuery('[name="update_cart"]').trigger('click');

    			});
    		});
    	});

		//bargain simple product add to cart
	    $(document).on('click', '.qcld-modal-bargin-add-to-cart', function(e){
	    	
	    	var product_id 		= $(this).attr('product-id');
	    	var price 			= $(this).attr('price');

	    	var product_qty 	= $(this).attr('product-qty');
	    	var product_qty 	= product_qty || 1;

	    	if( product_id == '' ) {
	    		$(this).addClass('woo-chatbot-shake');

	    		setTimeout(function(){
	    			$(this).removeClass('woo-chatbot-shake');
                }, 700);

	    		return;
	    	}

	        $('.woo-chatbot-product-bargain-msg-success').show();
	    	$('.woo-chatbot-product-bargain-msg-success').html('<p class="woo-chatbot-product-bargain-price_nego">'+ qcld_map_pro_added_to_cart_msg +'</p>');

	    	var data = {
	    		'action':'qcld_woo_bargin_product_add_to_cart', 
	    		'product_id': product_id,  
	    		'price': price,
	    		'product_qty': product_qty,
	    		'security': qcld_map_pro_get_ajax_nonce
	    	};
            
	    	setTimeout(function(){
	        	jQuery.post(qcld_map_ajaxurl, data, function (response) {
	        	
	        		$('.woo-chatbot-product-bargain-msg-success').html('');
	        		$('.woo-chatbot-product-bargain-msg-success').html(response.html);
	        		$('.woo-chatbot-product-bargain-msg-success').append('<div class="woo-chatbot-product-bargain-btn"><a href="'+qcld_map_pro_get_checkout_url +'" class="qcld-modal-bargin-confirm-btn-checkout"> '+qcld_map_pro_checkout_now_button_text+' </a></div>');


	        		jQuery("[name='update_cart']").removeAttr('disabled');
	        		jQuery('[name="update_cart"]').trigger('click');

    			});
    		});
    	});

		//bargain exit intent confirm
	    $(document).on('click', '.qcld-modal-bargin-confirm-exit-intent', function(e){

	    	$.magnificPopup.close();
            
	    	setTimeout(function(){

	    		$('.woo_minimum_accept_price-bargin').click();
                $('.woo_minimum_accept_price-bargin-modal').click();
                $('.woo_minimum_accept_price-bargin-varialbe-modal').click();
	        	
    		}, 1000);

    	});

	    $(document).on("keyup", ".woo-chatbot-bargain-qty", function (e) {

	    	e.preventDefault();
	    	var qcld_qty = $(this).val();

	        var min_val = parseInt(qcld_qty) ? parseInt(qcld_qty) : 0;
	        if (min_val > 0) {
	    		$('.qcld-modal-bargin-add-to-cart').attr('product-qty', qcld_qty);
	    		$('.qcld-modal-bargin-confirm-add-to-cart').attr('product-qty', qcld_qty);

	        }else{
	        	$(this).val(1);
	    		$('.qcld-modal-bargin-add-to-cart').attr('product-qty', 1);
	    		$('.qcld-modal-bargin-confirm-add-to-cart').attr('product-qty', 1);
	        }



    	});






    });

})(jQuery);