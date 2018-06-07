var module = angular.module("PresentationApp", ['ngRoute']);
module.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
            .when('/browse', {
                templateUrl: "browse.php",
                controller: 'browseCtrl'
            }).when('/create', {
                templateUrl: "create.php",
                controller: 'userController'
            }).when('/favourites', {
                templateUrl: "favourites.php",
                controller: 'userController'
            }).when('/profile',{
                templateUrl:"profile.php",
                controller:'userController'
            }).when('/viewPresentation', {
                templateUrl:'viewPresentation.php',
                controller: 'presController'
            }).otherwise({
                redirectTo: ""
            });
}]);
module.controller('userController', function ($scope) {
    $scope.userActionSuccess = {
        status: "false",
        message: ""
    }

    $scope.loginUser = function (formOrigin) {
        $scope.validateFields(formOrigin);
    }
    $scope.signupUser = function (formOrigin) {
        if ($scope.validateFields(formOrigin))
            $scope.userActionSuccess.message = "You have now successfully created a profile. Please use the link below to go to the login screen.";

    }
});

module.controller('browseCtrl', function ($scope) {
    $scope.spList = [
        {
            'firstName': 'Dan',
            'lastName': 'Taylor',
            'topic': 'AngularJs',
            'jobTitle': 'Software Engineer',
            'twitterHandle': 'polski_al',
            'tags': 'HTML5, CSS3, JS, AngularJs'
        },
        {
            'firstName': 'Jake',
            'lastName': 'Stimpson',
            'twitterHandle': 'polski_al',
            'jobTitle': 'Web Developer',
            'topic': 'PHP'
        },
        {
            'firstName': 'Dari',
            'lastName': 'Mama',
            'jobTitle': 'The Greatest Mum in the World',
            'twitterHandle': 'polski_al',
            'topic': 'HTML'
        },
        {
            'firstName': 'Aleksander',
            'lastName': 'Tati',
            'jobTitle': 'The Greatest Dad in the World, inventor',
            'twitterHandle': 'polski_al',
            'topic': 'HTML'
        }, {
            'firstName': 'Dan',
            'lastName': 'Taylor',
            'topic': 'An experts view of the long journey of learning AngularJs',
            'jobTitle': 'Software Engineer',
            'twitterHandle': 'polski_al',
            'tags': 'HTML5, CSS3, JS, AngularJs'
        },
        {
            'firstName': 'Jake',
            'lastName': 'Stimpson',
            'twitterHandle': 'polski_al',
            'jobTitle': 'Web Developer',
            'topic': 'PHP'
        },
        {
            'firstName': 'Dari',
            'lastName': 'Mama',
            'jobTitle': 'The Greatest Mum in the World',
            'twitterHandle': 'polski_al',
            'topic': 'HTML'
        },
        {
            'firstName': 'Aleksander',
            'lastName': 'Tati',
            'jobTitle': 'The Greatest Dad in the World, inventor',
            'twitterHandle': 'polski_al',
            'topic': 'HTML'
        }
   ];
});