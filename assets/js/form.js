  jQuery.fn.sortLowPrice = function() {
    this
      .children()
      .sort((a,b) => jQuery(a).data("price") - jQuery(b).data("price") || -1)
      .appendTo(this);

    return this;
  }

  jQuery.fn.sortHighPrice = function() {
    this
      .children()
      .sort((a,b) => jQuery(b).data("price") - jQuery(a).data("price") || -1)
      .appendTo(this);

    return this;
  }

  jQuery.fn.sortStarRating = function() {
    this
      .children()
      .sort((a,b) => jQuery(b).data("star") - jQuery(a).data("star") || -1)
      .appendTo(this);

    return this;
  }



  function checkPasswordStrength() {
            var number = /([0-9])/;
            var alphabets = /([a-zA-Z])/;
            var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
            if (jQuery('#password').val().length < 6) {
                jQuery('.porgress_wrap').removeClass('strong-password medium-password');
                jQuery('.porgress_wrap').addClass('weak-password');
                jQuery('.progress_label').html("Weak");
            } else {
                if (jQuery('#password').val().match(number) && jQuery('#password').val().match(alphabets) && jQuery('#password').val().match(special_characters)) {
                    jQuery('.porgress_wrap').removeClass('weak-password medium-password');
                    jQuery('.porgress_wrap').addClass('strong-password');
                    jQuery('.progress_label').html("Strong");
                } else {
                    jQuery('.porgress_wrap').removeClass('weak-password strong-password');
                    jQuery('.porgress_wrap').addClass('medium-password');
                    jQuery('.progress_label').html("Medium");
                }
            }
        }

  

   jQuery( function() {
    var country_option = '<option value="">Select Country</option>';
    jQuery('#um_field_general_user_email').remove();
    jQuery.each( api.countries, function( key, value ) {
      country_option += '<option value="'+key+'">'+value+'</option>';
    });
    jQuery('.choose_country').html(country_option);
    jQuery(document).on('change','.choose_country',function(e){
     
      var country = jQuery(this).val();
      
      jQuery.ajax({
          url: api.ajaxurl,
          type: 'POST',
          data:{'country':country,'action':'get_state'},
          beforeSend: function() {
             // btn.text('processing');
          },
          success: function( data ){
            var response = JSON.parse(data);

           var state_option = '<option value="">Select State</option>';
            jQuery.each( response, function( key, value ) {
             
              state_option += '<option value="'+key+'">'+value+'</option>';
            });
            jQuery('.choose_state').html(state_option);
          }
      });
   
    });


        jQuery("#dialog").dialog({
            modal: true,
            autoOpen: false,
            // title: "jQuery Dialog",
            width: 1420,
            height: 840,
            open: function (event, ui) {
                jQuery(".ui-widget-overlay").click(function () {
                    jQuery('#dialog').dialog('close');
                });
            }
        });
        jQuery("#btnShow").click(function () {
            jQuery('#dialog').dialog('open');
        });
    
    jQuery('#password, #confirm_password').on('keyup', function () {
      if (jQuery('#password').val() == jQuery('#confirm_password').val()) {
        jQuery('#message').html('Matching').css('color', 'green');
      } else 
        jQuery('#message').html('Not Matching').css('color', 'red');
    });
    jQuery('#lowest-price').on('click',function(){
      jQuery(".filter_results-list").sortLowPrice();
    });
   
    jQuery('#highest-price').on('click',function(){
      jQuery(".filter_results-list").sortHighPrice();
    });

    jQuery('#star-rating').on('click',function(){
      jQuery(".filter_results-list").sortStarRating();
    });

    jQuery("#filter_hotel_name").on("keyup", function() {
      var value = jQuery(this).val().toLowerCase();
      jQuery(".filter_results-list li").filter(function() {
        jQuery(this).toggle(jQuery(this).find('.hotel_name a').text().toLowerCase().indexOf(value) > -1);
      });
    });
    jQuery('.book_con').on('click',function(e){
      e.preventDefault();
      jQuery('.tab-part').slideToggle();
    });

    jQuery('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 100,
        itemMargin: 5,
        asNavFor: '#slider'
      });

      jQuery('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
      });
      jQuery('.galleryslider').flexslider({
       animation: "slide",
       controlNav: false,
      });
    jQuery('.date_range').daterangepicker({
      autoApply: true,
      setDate:null,
      minDate: moment(),
      autoUpdateInput: false,
      locale :{
        format : 'YYYY-MM-DD',
        separator: ' - ',
      }

  }, function(start, end, label) {
    var days = end.diff(start, 'days');
    jQuery('.booking_nights').html(days);
    jQuery('#booking_nights').val(days);

    //if(GetParameterValues('rooms')){
      setTimeout(function(){ count_infants(); }, 500);

    //}

  });
    jQuery('.date_range').on('apply.daterangepicker', function(ev, picker) {
      jQuery(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
  });
    jQuery(document).on('change','.hotel_rooms',function(){
      var room = jQuery(this).val(),
          existing_room = jQuery('.room_c').length;
      if(room >= existing_room){
      var rooms = '';
      for (var i = 2 ; i <= room; i++) {
          rooms +='<div class="room_'+i+' room_c"><div class="room-input">';
              rooms +='<span class="room_num">Room '+parseInt(i)+'</span>';
            rooms +='<select class="form-control" name="rooms['+i+'][adults]">';
              rooms +='<option value="1">1 Adult</option>';
              rooms +='<option value="2">2 Adults</option>';
              rooms +='<option value="3">3 Adults</option>';
              rooms +='<option value="4">4 Adults</option>';
            rooms +='</select>';
            rooms +='<select class="form-control s_num_child" room="'+i+'">';
              rooms +='<option value="0">No Children</option>';
              rooms +='<option value="1">1 Children</option>';
              rooms +='<option value="2">2 Childrens</option>';
              rooms +='<option value="3">3 Childrens</option>';
              rooms +='<option value="4">4 Childrens</option>';
            rooms +='</select>';
            rooms +='</div><div class="children_'+i+'" style="display:none;"><label class="chil-age">Children Age<span> (1-17, enter 0 for infants)</span></label><div class="chil-input"></div></div></div>';
          }
        jQuery('.rooms').append(rooms);
      }else{
        var rooms = existing_room - room;
        jQuery('.room_c').slice(-rooms).remove();
      }
      /* jQuery(".extra_rooms").html(rooms);*/
    });
  jQuery(document).on('change','.num_child',function(){
      var child = jQuery(this).val();
      var room = jQuery(this).attr('room');
      var children = '<label class="chil-age">Children Age </label><div class="chil-input">';
      for (var i = 1 ; i <= child; i++) {
          children +='<input type="number" class="form-control"  name="rooms['+room+'][children][]" required>';
      }
      children +='</div>';
      jQuery(".children_"+room).html(children);
    });
  jQuery(document).on('click','.room_tabs',function(e){
      e.preventDefault();
      var target = jQuery(this).data('room');
      jQuery('.room_tabs').removeClass('active');
      jQuery(this).addClass('active');
      jQuery('.single_room_'+target).show().siblings().hide();
    });
    jQuery(document).on('change','.single_hotel_rooms',function(){
      var room = jQuery(this).val(),
          existing_room = jQuery('.room_tabs').length;
      if(room >= existing_room){
          var rooms = '';
          var tabs = '';
          for (var i = existing_room + 1; i <= room; i++) {
            tabs += '<li class=" room_tabs" data-room="'+i+'"><a href="javascript:void(0);">Room '+i+'</a></li>';
            rooms +='<div class="single_room_'+i+' single_rooms" style="display:none;">';
              rooms +='<select name="rooms['+i+'][adults]" class="adults count_infants">';
                rooms +='<option value="1">1 adults</option>';
                rooms +=' <option value="2">2 adults</option>';
                rooms +=' <option value="3">3 adults</option>';
                rooms +='  <option value="4">4 adults</option>';
                rooms +=' <option value="5">5 adults</option>';
                rooms +=' <option value="6">6 adults</option>';
              rooms +='</select>';
              rooms +='<select name="" room="'+i+'" class="s_num_child count_infants">';
                rooms +=' <option value="0">No Children</option>';
                rooms +=' <option value="1">1 Childrens</option>';
                rooms +=' <option value="2">2 Childrens</option>';
                rooms +='<option value="3">3 Childrens</option>';
                rooms +='<option value="4">4 Childrens</option>';
              rooms +='</select>';
              rooms +='<div class="children_'+i+'"  style="display:none;"><label class="chil-age">Children Age<span> (1-17, enter 0 for infants)</span></label><div class="chil-input"></div></div>';
           rooms +=' </div>';
          }
          jQuery('.tab-part').find('.nav-tabs').append(tabs);
          jQuery('.tab-part').find('.b-part').append(rooms);
      }else{
        var rooms = existing_room - room;
        jQuery('.tab-part').find('.b-part > .single_rooms').slice(-rooms).remove();
        jQuery('.tab-part').find('.nav-tabs li').slice(-rooms).remove();
      }
    });
    jQuery(document).on('change','.count_infants',function(){
      count_infants()
    });
    jQuery(document).on('change','.s_num_child',function(){
      var child = jQuery(this).val(),
          chroom = jQuery(this).attr('room'),
          existing_child = jQuery(".children_"+chroom).find('input').length,
          children = '';
          if(child == 0){
            jQuery(".children_"+chroom).hide();
            jQuery(".children_"+chroom).find('input').remove();
            return;
          }
          //children = '<label class="chil-age">Children Age</label><div class="chil-input">';
          if(child >= existing_child){
            for (var i = existing_child+1 ; i <= child; i++) {
              children +='<input type="number" class=""  name="rooms['+chroom+'][children][]" required>';
            }
            //children +='</div>';
            jQuery(".children_"+chroom+" .chil-input").append(children);
            jQuery(".children_"+chroom).show();
          }else{
            var rooms = existing_child - child;
            jQuery(".children_"+chroom).find('input').slice(-rooms).remove();

          }



    });
    var cardNumber = jQuery('#cardNumber');
    var cardNumberField = jQuery('#card-number-field');
    var CVV = jQuery("#cvv");
    var mastercard = jQuery("#mastercard");
    var visa = jQuery("#visa");
    var amex = jQuery("#amex");
    cardNumber.payform('formatCardNumber');
    CVV.payform('formatCardCVC');
    cardNumber.keyup(function() {

        amex.removeClass('transparent');
        visa.removeClass('transparent');
        mastercard.removeClass('transparent');

        if (jQuery.payform.validateCardNumber(cardNumber.val()) == false) {
            cardNumberField.addClass('has-error');
        } else {
            cardNumberField.removeClass('has-error');
            cardNumberField.addClass('has-success');
        }

        if (jQuery.payform.parseCardType(cardNumber.val()) == 'visa') {
            mastercard.addClass('transparent');
            amex.addClass('transparent');
        } else if (jQuery.payform.parseCardType(cardNumber.val()) == 'amex') {
            mastercard.addClass('transparent');
            visa.addClass('transparent');
        } else if (jQuery.payform.parseCardType(cardNumber.val()) == 'mastercard') {
            amex.addClass('transparent');
            visa.addClass('transparent');
        }
    });
    populateCountries("country", "state");

    jQuery('.checkoutform').submit(function(e){
      var btn = jQuery('.book_submit');
      e.preventDefault();
      jQuery.ajax({
          url: api.ajaxurl,
          type: 'POST',
          data:jQuery(this).serialize(),
          beforeSend: function() {
             // btn.text('processing');
          },
          success: function( data ){
           // window.location.href = "https://www.getme.pro";
          }
      });
    });
  } );

  jQuery('.changepassword').submit(function(e){
      e.preventDefault();
      jQuery.ajax({
          url: api.ajaxurl,
          type: 'POST',
          data:jQuery(this).serialize(),

          beforeSend: function() {
            jQuery("#idv").html();
             // btn.text('processing');
          },
          success: function(data){
           // window.location.href = "https://www.getme.pro";
           var text = JSON.parse(data);
           
           jQuery("#idv").html(text.message);
          }
      });
    });

  function count_infants(){
    var room = jQuery('.single_hotel_rooms').val();
    var adults = 0;
    var childs = 0;
    jQuery('.adults').each(function(){
      adults += Number(jQuery(this).val());
    });
    jQuery('.s_num_child').each(function(){
      childs += Number(jQuery(this).val());
    });

    childs = (childs == 0)?'No':childs;
    var dates = jQuery('.date_range').val().split(' - '),
        end = moment(dates[1]),
        start = moment(dates[0]),
        days = end.diff(start, 'days');
    var infants = room+" Rooms - "+adults+" Adults - "+childs+" Childrens - "+days+" Nights";
    jQuery('.book_con a').text(infants);
  }



let els = document.getElementsByClassName('step');
let steps = [];
Array.prototype.forEach.call(els, (e) => {
  steps.push(e);
  e.addEventListener('click', (x) => {
    progress(x.target.id);
  });
});

function progress(stepNum) {
  let p = stepNum * 30;
  document.getElementsByClassName('percent')[0].style.width = `${p}%`;
  steps.forEach((e) => {
    if (e.id === stepNum) {
      e.classList.add('selected');
      e.classList.remove('completed');
    }
    if (e.id < stepNum) {
      e.classList.add('completed');
    }
    if (e.id > stepNum) {
      e.classList.remove('selected', 'completed');
    }
  });
}


