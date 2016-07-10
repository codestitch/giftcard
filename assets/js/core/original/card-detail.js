var myApp = angular.module('myApp', []);
myApp.controller('MyController', MyController);  

var SelectedImage = "",
    SelectedName = "";

function MyController ($scope) {  
  

  $scope.initCart = function(){   
      console.log("initCart");

      $scope.data = GetCart();
      console.log($scope.data); 
      $scope.totalrow = (!$scope.data) ? 0 : $scope.data.length;  

  }  

}

$(document).ready(function() {
  
  console.log("card-details");
  var owl = $("#owl-demo"); 

  owl.owlCarousel({ 
    items : 6, //10 items above 1000px browser width
    itemsDesktop : [1000,6], //5 items between 1000px and 901px
    jsonPath : 'data/cards.json',
    jsonSuccess : GenerateCards
    // itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
    // itemsTablet: [600,2], //2 items between 600 and 0;
    // itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option 
    // navigation: false
  });


  function GenerateCards(data) { 

    var content = "";
    var isfirst = true;
    for(var i in data){
       
       var img = data[i].img; 
 
       content += (isfirst) ? " <a class='item link '> "+
            " <img class='thumbimg' src=\"" +img+ "\"  id=\"" +data[i].name+ "\"> "+
            " </a>"
            :
            " <a class='item link'> "+
            " <img class='thumbimg' src=\"" +img+ "\" id=\"" +data[i].name+ "\">  "+
            " </a>";

        isfirst = false;
    }
    SelectedImage = data[0].img;
    SelectedName = data[0].name;
    $(".imgctr").attr({"src" : SelectedImage});

    $("#owl-demo").html(content); 
  } 

});




$('#owl-demo').on('click', 'div.owl-item', function(event){
  var $this = $(this);   
  console.log($this);
  $( ".owl-item" ).each(function( index ) { 
    if ($( this ).hasClass('selected')) { 
      $( this ).removeClass('selected');
    }

  });
 
  $this.addClass('selected');  
  SelectedImage = $this[0].children[0].firstElementChild.currentSrc; 
  SelectedName = $this[0].children[0].firstElementChild.id;

  $(".imgctr").attr('src', SelectedImage);  
}); 


$("#amountOpt").on("click", function(){
  $("#choiceamtField").hide();
  $("#useramtField").show();
  $("#amtTitle").text("Choose your desired Amount");
});

$("#gotoselamt").on("click", function(){
  $("#choiceamtField").show();
  $("#useramtField").hide();
  $("#amtTitle").text("Choose an Amount");
});

$("#cardTypeField").on("change", function(){

  var choice = $("#cardTypeField").val();

  $("#virtualField").show();
  $("#physicalField").hide();

  if (choice == "physical") {
    $("#physicalField").show();
  }
  else{
    $("#physicalField").hide();
  }

}); 


$("#addCartBtn").on("click", function(){

  var amount = ( $("#choiceamtField").is(":visible")  ) ? 
    $('input[name=amountField]:checked').val() :
    $("#desiredamtField").val(); 
  var cardtype = "virtual"; // $("#cardTypeField").val(); 
  var quantity = $("#quantityField").val();  

  if (parseInt(amount) < 10 ) {
    toastr['warning']("Oops! Amount must be at least ₱10", "Invalid Entry");
    return;
  }
  if (cardtype == "") {
    toastr['warning']("Oops! You seem to forgot to select a Card Type.", "Invalid Entry");
    return;
  }

  // card validation 
  var name = $("#nameField").val(),
      toemail = $("#toemailField").val(),
      email = $("#emailField").val(),
      message = $("#messageField").val();

  // alert(message);
  // console.log(message);

  if (name == "") {
    toastr['warning']("Oops! You seem to forgot to give your name", "Invalid Entry");
    return;
  }
  if (toemail == "") {
    toastr['warning']("Oops! You seem to forgot to give your recipients email", "Invalid Entry");
    return;
  }
  if (email == "") {
    toastr['warning']("Oops! You seem to forgot to give your email", "Invalid Entry");
    return;
  } 
  if (!validateEmail(email) || !validateEmail(toemail)) {
    toastr['warning']("Oops! You seem to have entered a wrong email.", "Invalid Entry");
    return;
  }  

  if (parseInt(quantity) < 1) {
    toastr['warning']("Oops! You seem to forgot to give the quantity of cards to purchase.", "Invalid Entry");
    return;    
  } 

  console.log(ItemExist(SelectedName));
  if (ItemExist(SelectedName)) {
    toastr['warning']("Oops! You've already added that card to your cart. Just view the cart and update the quantity.", "Invalid Entry");
    return; 
  }


  AddtoCart(SelectedName, cardtype, parseInt(amount), SelectedImage, parseInt(quantity), toemail, email, message);
  window.location = "cart.php?name="+SelectedName;

}); 
 

$("#quantityField").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});