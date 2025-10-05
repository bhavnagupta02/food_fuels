function checkAll(chobj){
	frm = chobj.form;
	for(var i=0; i<frm.elements.length; i++){
		if(frm.elements[i].type == "checkbox" && frm.elements[i].name != "gold"){
			frm.elements[i].checked = chobj.checked;
		}
	}
}

function submit_form(frmobj,comb)
{
	var comb = document.getElementById('action').value;
	if(comb=='')
	{
		alert("Please select an action.");
		return false;
	}

	var checked = 0;

	if ((frmobj.elements['delIDs[]'] != null) && (frmobj.elements['delIDs[]'].length == null))
	{
		if (frmobj.elements['delIDs[]'].checked)
		{
			checked = 1;
		}
	}
	else
	{
		for (var i=0; i < frmobj.elements['delIDs[]'].length; i++)
		{
			if (frmobj.elements['delIDs[]'][i].checked)
			 {
				checked = 1;
				break;
			 }
		}
	}

	if (checked == 0)
	{
		alert("Please select checkboxes to do any operation.");
		return false;
	}


	if(comb == 'Delete')
	{
		if(confirm ("Are you sure you want to delete record(s)?"))
		{
			frmobj.listingAction.value = 'Delete';
			frmobj.submit();
		}
		else
		{
			return false;
		}
	}
	else
	{
		frmobj.listingAction.value = comb;
		frmobj.submit();
	}
}

$(function() {
  $('input[type="text"]').each(function(){
    $(this).attr('spellcheck',true);
  });

  $('input[type="textarea"]').each(function(){
    $(this).attr('spellcheck',true);
  });

  $('[data-toggle="tooltip"]').tooltip(); 
  
  if($(".success_msg").length){
    setTimeout(function()
    {
      $('.success_msg').delay(10000).fadeOut('slow');
    }, 1000);  
  }

  if($(".error_msg").length){
    setTimeout(function() {
          $('.error_msg').delay(10000).fadeOut('slow');
    }, 1000);  
  }

  $(window).scroll(function () {
      if($(this).scrollTop() > 100)
      {
         if(!$('.scrollup').hasClass('show'))
         {
            $('.scrollup').addClass('show');  
         }
      }
      else
      {
        if($('.scrollup').hasClass('show'))
         {
            $('.scrollup').removeClass('show');  
         }
      } 
  });

  $('.scrollup').click(function () {
      $("html, body").animate({
          scrollTop: 0
      }, 600);

      $('.scrollup').fadeOut();
      
      return false;
  });

	$("#reset").click(function(){
		 $(this).closest('form').find('input[type=text], textarea, select').val('');
	});

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
});


function initialize(location_id, map_canvas_id) {

  var markers = [];
   var latLng = new google.maps.LatLng(51.044270, -114.062019);
  var map = new google.maps.Map(document.getElementById(map_canvas_id), {

    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false

  });

  var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(51.044270, -114.062019),
      new google.maps.LatLng(51.044270, -114.062019));
  map.fitBounds(defaultBounds);
  // Create the search box and link it to the UI element.
  var input = /** @type {HTMLInputElement} */(
      document.getElementById(location_id));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);



  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));

  // [START region_getplaces]
  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

  map.setOptions({ scrollwheel: true });
      // Create a marker for each place.
      var marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
  });
  // [END region_getplaces]


  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
    if (map.getZoom() > 10) map.setZoom(8);
  });

	/*var marker = new google.maps.Marker({map: map, position: point, clickable: true});

	marker.info = new google.maps.InfoWindow({
	  content: '<b>Speed:</b> ' + values.inst + ' knots'
	});

	google.maps.event.addListener(marker, 'click', function() {
	  marker.info.open(map, marker);
	});*/
}
