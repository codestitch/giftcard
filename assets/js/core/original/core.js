$(function() {

    /********** Preloader Screen **********/
	$(window).load(function() {
        window.setTimeout(function() {
        	$('body').css({'overflow': 'auto'});

        	if ((basename == '404') || (basename == '500')) {
        		$('body').css({'overflow-x': 'hidden'});
        	}

            $('.preloader-container').fadeOut();
			$('.preloader-wrapper').delay(50).fadeOut('fast');

			if ((basename == '404') || (basename == '500')) {
        		$('.number').addClass('rubberBand animated');
        	} else if (basename == 'login') {
        		$('#logo-img').addClass('rubberBand animated');
    		}
        }, 2000);
	});

	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "positionClass": "toast-bottom-left",
	  "onclick": null,
	  "showDuration": "1000",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}

	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// Demo.init(); // init demo features
	// UIToastr.init();
	if (basename != 'login') {
		// UIIdleTimeout.init(); // initialize session timeout settings
	}

});

var MERCHANT_DOMAIN = "http://boscoffee.appsolutely.ph/";
var REPORTS_LINK = "http://boscoffee.appsolutely.ph/reportsdata.php?";
var EXPORT_LINK = "http://boscoffee.appsolutely.ph/exportreportsdata.php?";

function show_loading(destination) {
	Metronic.blockUI({
        target: destination
    });
    return;
}

function hide_loading(destination) {
    window.setTimeout(function() {
        Metronic.unblockUI(destination);
    }, 2000);
}

function validate_email_address(email){
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (reg.test(email) == false) {
    	return "Invalid";
    } else {
    	return "Valid";
    }
}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}

function numberFormat(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}



function GetToday () {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 

    return mm+'/'+dd+'/'+yyyy;
}

function StartPreloader() {  
    $("#preloader").show(); 
}

function StopPreloader() {
    $("#preloader").hide(); 
}

 
function GetCart() { 

    // Retrieve the object from storage
    var retrievedObject = localStorage.getItem('CardsArray');
 
    return JSON.parse(retrievedObject);
}


function AddtoCart(cardname, cardtype, amount, image, quantity, recipientemail, senderemail, message) {

  // get
  var oldCards = JSON.parse(localStorage.getItem('CardsArray')) || [];

  // init
  var newCard = { 'cardname': cardname, 'cardtype': cardtype, 'amount': amount, 'quantity': quantity, 
                'image': image, 'recipientemail': recipientemail, 'senderemail': senderemail, 'message' : message };

  // append
  oldCards.push(newCard);

  // set
  localStorage.setItem('CardsArray', JSON.stringify(oldCards));

}

function ItemExist(_cardname) {
  
  var cards = GetCart();
  var isFound = false;

  angular.forEach(cards, function(value, key) {  
    if (_cardname == value.cardname) { 
      isFound = true;
    } 
  });  

  return isFound; 
}

function UpdateCart(dataObject){
  var cards = JSON.parse(localStorage.getItem('CardsArray'));

  angular.forEach(cards, function(value, key) { 

    if (dataObject[key].cardname == value.cardname) {
        value.quantity = dataObject[key].quantity;
    }

  }); 
  
  localStorage.setItem('CardsArray', JSON.stringify(cards)); 
}


function DeleteItem(dataObject, index){
    var cards = JSON.parse(localStorage.getItem('CardsArray'));
    
   angular.forEach(cards, function(value, key) {  

    if (key == index) {
        console.log("slicing: "+dataObject[key]);
         cards.splice(index, 1);
    }

  });  
  
  localStorage.setItem('CardsArray', JSON.stringify(cards)); 
}



function ClearCart() { 
  localStorage.clear();
}

function FormatNumber(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}