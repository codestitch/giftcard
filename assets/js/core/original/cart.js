var myApp = angular.module('myApp', []);
myApp.controller('MyController', MyController);  

console.log("ready");
 

myApp.filter('mysum', function() {
  return function(items) {
    var sum = 0;
    items.forEach(function(item) {
      if (item.quantity) {
         sum += parseInt(item.amount) * parseInt(item.quantity);      
      }
    })
    return FormatNumber(sum);
  }
})

function MyController ($scope) {  

	$scope.data = null;     

  $scope.initCart = function(){   
      console.log("initCart");

      $scope.data = GetCart();
      console.log($scope.data); 
      $scope.totalrow = $scope.data.length;   

  }  

  $scope.Summation = function(items) {
    var sum = 0;
    items.forEach(function(item) {
      if (item.quantity) {
         sum += parseInt(item.amount) * parseInt(item.quantity);      
      }
    })
    return FormatNumber(sum);
  }

  $scope.Remove= function(index){  

    DeleteItem($scope.data, index);

    $scope.data = GetCart();
    console.log($scope.data.length);
    $scope.totalrow = $scope.data.length;    
  };


  $scope.Checkout = function(){    
    UpdateCart($scope.data);
    window.location = "checkout.php?name="+$("#cardnamelabel").text().trim();
      
  } 

  $scope.change = function(){    
    console.log("changed");
    UpdateCart($scope.data);
  } 

  // $("#quantityField").on("change", function(){
  //   console.log("changed");
  //   UpdateCart($scope.data);

  // });
 

}


