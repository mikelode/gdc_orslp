// Creación del módulo
var angularRoutingApp = angular.module('sigadApp', ['ngRoute']);

// Configuración de las rutas
angularRoutingApp.config(function($routeProvider) {
    $routeProvider
        .when('/inicio', {
            templateUrl	: 'angular-app/js/templates/procesos/inicio.html',
            controller 	: 'inicioController'
        })
        .when('/registro', {
            templateUrl	: 'angular-app/js/templates/procesos/registro.html',
            controller 	: 'registroController'
        })
        .when('/bandeja', {
            templateUrl : 'angular-app/js/templates/procesos/bandeja.html',
            controller 	: 'bandejaController'
        })
            .when('/bandeja/nuevoMensaje', {
                controller 	: 'nuevoMensajeController'
            })

        .when('/tramites', {
            templateUrl : 'angular-app/js/templates/procesos/tramites.html',
            controller 	: 'tramitesController'
        })
        .when('/tareas', {
            templateUrl : 'angular-app/js/templates/procesos/tareas.html',
            controller 	: 'tareasController'
        })
        .when('/calendario', {
            templateUrl : 'angular-app/js/templates/procesos/calendario.html',
            controller 	: 'calendarioController'
        })
        .when('/archivos', {
            templateUrl : 'angular-app/js/templates/procesos/archivos.html',
            controller 	: 'archivosController'
        })
        .when('/contactos', {
            templateUrl : 'angular-app/js/templates/procesos/contactos.html',
            controller  : 'contactosController'
        })


        .when('/clientes/peps', {
            templateUrl : 'angular-app/js/templates/clientes/peps.html',
            controller 	: 'ClientesPepsController'
        })

        .otherwise({
            templateUrl	: 'angular-app/js/templates/error/404.html'
        });
});

/* --- Controller Generales --- */
angularRoutingApp.controller('generalController', function($scope) {
});
/* --- --- */

/* --- Controller Generales --- */
angularRoutingApp.controller('inicioController', function($rootScope) {
    $rootScope.activeInicio = true;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = false;
});
/* --- --- */

/* --- Controller Bandeja --- */

angularRoutingApp.controller('ClientesPepsController', function($scope, $rootScope, bandejaService){

});

angularRoutingApp.controller('bandejaController', function($scope, $rootScope, bandejaService){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = true;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = false;

    $scope.modelMensaje = {
        id : '1',
        titulo: 'Nuevo Mensaje',
        deValue: '',
        paraValue: '',
        copiaValue: '',
        asuntoValue: '',
        mensajeValue:''
    };

    $scope.enviarMensaje = function (){
        bandejaService.nuevoMensaje($scope.modelMensaje);
    };
});

angularRoutingApp.controller('nuevoMensajeController', function($scope, $http){
    alert("asdf");
});
/* --- --- */

angularRoutingApp.controller('tramitesController', function($rootScope){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = true;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = false;
});

angularRoutingApp.controller('tareasController', function($rootScope){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = true;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = false;
});

angularRoutingApp.controller('calendarioController', function($rootScope){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = true;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = false;
});

angularRoutingApp.controller('archivosController', function($rootScope){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = true;
    $rootScope.activeContactos = false;
});

angularRoutingApp.controller('contactosController', function($rootScope){
    $rootScope.activeInicio = false;
    $rootScope.activeBandeja = false;
    $rootScope.activeTramites = false;
    $rootScope.activeTareas = false;
    $rootScope.activeCalendario = false;
    $rootScope.activeArchivos = false;
    $rootScope.activeContactos = true;
});

angularRoutingApp.controller('/', function() {
});

angularRoutingApp.service('bandejaService', function($http, $q, $location){
    return({
        nuevoMensaje: nuevoMensaje
    });

    function nuevoMensaje(modelMensaje) {
        var request = $http({
            method: "post",
            url: "mensajes/nuevoMensaje",
            data: {
                de: modelMensaje.deValue,
                para: modelMensaje.paraValue,
                cc: modelMensaje.copiaValue,
                asunto: modelMensaje.asuntoValue,
                mensaje: modelMensaje.mensajeValue
            }
        }).success(function(data, status, headers, config){
            console.log(data.para);
        }).error(function (data, status, headers, config){
            alert("error");
        });
        //return( request.then( handleSuccess, handleError ) );
    }

    function handleError( response ) {
        if (
            ! angular.isObject( response.data ) ||
                ! response.data.message
            ) {
            return( $q.reject( "An unknown error occurred." ) );
        }

        return( $q.reject( response.data.message ) );
    }

    function handleSuccess( response ) {
        return( response.data );
    }
});
