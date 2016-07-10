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
   } 

}

$("#orderBtn").on("click", function(){ 
  window.location = "thankyou.php";
});