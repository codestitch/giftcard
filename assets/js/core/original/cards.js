var myApp = angular.module('myApp', []);
myApp.controller('MyController', MyController);  

console.log("ready");
 
 
function MyController ($scope) {  
  

  $scope.initCart = function(){   
      console.log("initCart");

      $scope.data = GetCart();
      console.log($scope.data); 
      $scope.totalrow = (!$scope.data) ? 0 : $scope.data.length;  

  }  

}