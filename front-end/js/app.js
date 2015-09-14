var app = angular.module('chimpanze', ['ui.bootstrap']);

app.controller('MainController', function($scope, $rootScope){
    $scope.current_page = 'intro';

    $scope.manage_templates = function(){
        $scope.current_page = 'manage_templates';
        $rootScope.$broadcast('load_templates');
    };

    $scope.manage_emails = function(){
        $scope.current_page = 'manage_emails';
        $rootScope.$broadcast('load_emails');
    };
});

app.controller('TemplateController', function($scope, $http){ 
    $scope.templates = [];
    $scope.size = 12;
    $scope.selected = undefined;
    $scope.success = undefined;

    $scope.$on('load_templates', function(){
        $http.get(url_base + 'template/').then(function(data){
            $scope.templates = data.data;
        });
    });

    $scope.select = function(template){
        $scope.size = 4;
        $scope.selected = template;
    }

    $scope.show_title = function(template){
        if(template.id === undefined){
            return 'Você ainda não salvou esse template';
        }
    }

    $scope.add = function(){
        template = {
            title:  '',
            body: ''    
        }
        $scope.templates.push(template);
        $scope.select(template);
    }

    $scope.delete = function(template){
        if(template.id === undefined){
            var index = $scope.templates.indexOf(template);
            if(index > -1)
                $scope.templates.splice(index, 1);
        }

        var url = url_base + 'template/' + template.id + '/';
        $http.delete(url).then(function(){
            var index = $scope.templates.indexOf(template);
            if(index > -1)
                $scope.templates.splice(index, 1);
        });
    }
    
    $scope.save = function(){
        var fn = $http.post;
        var url = url_base + 'template/';

        if($scope.selected.id !== undefined){
            fn = $http.put;
            url += $scope.selected.id + '/';
        }

        fn(url, $scope.selected).then(function(data){
           $scope.selected.id = data.data.id;
           $scope.success = "Template salvo com sucesso!";
        });
    }
});

app.controller('EmailController', function($scope, $http){ 
    $scope.emails = [];
    $scope.templates = [];
    $scope.size = 12;
    $scope.selected = undefined;
    $scope.success = undefined;

    $scope.$on('load_emails', function(){
        $http.get(url_base + 'email/').then(function(data){
            $scope.emails = data.data;
        });
        $http.get(url_base + 'template/').then(function(data){
            $scope.templates = data.data;
        });
    });

    $scope.select = function(email){
        $scope.size = 4;
        $scope.selected = email;
    }

    $scope.show_title = function(email){
        if(email.id === undefined){
            return 'Você ainda não salvou esse email';
        }
    }

    $scope.add = function(template){
        email = {
            title:  '',
            body: '',
            template_id: template.id,
            template: template
        };
        $scope.emails.push(email);
        $scope.select(email);
    }

    $scope.delete = function(email){
        if(email.id === undefined){
            var index = $scope.emails.indexOf(email);
            if(index > -1)
                $scope.emails.splice(index, 1);
        }

        var url = url_base + 'email/' + email.id + '/';
        $http.delete(url).then(function(){
            var index = $scope.emails.indexOf(email);
            if(index > -1)
                $scope.emails.splice(index, 1);
        });
    }
    
    $scope.save = function(){
        var fn = $http.post;
        var url = url_base + 'email/';

        if($scope.selected.id !== undefined){
            fn = $http.put;
            url += $scope.selected.id + '/';
        }

        fn(url, $scope.selected).then(function(data){
           $scope.selected.id = data.data.id;
           $scope.success = "Template salvo com sucesso!";
        });
    }
});

var widgets = {
    'text': function($rootScope, $compile, $scope, element, attrs){
        var validate_text = function(){
            if($scope.text === undefined || $scope.text.trim().length == 0){
                $scope.text = 'Sem texto';
            }
        }

        validate_text();

        element.html("{{ text }}");
        var tpl = $("#popover-widget-text").html();

        $scope.$watch('text', function(new_value, old_value){
            // element.attr('popover-template', "'popover-widget-text.html'");
            $compile(element.contents())($scope);
            $(element).popover({
                html: true,
                placement: "top",
                container: "body",
                content: function(){
                    return $compile(tpl)($scope)
                }
            });
        });
    },
    'image': function($rootScope, $compile, $scope, element, attrs){
        element.css({
            width: '300px',
            height: '300px',
            display: 'inline-block',
        });
        $scope.image = undefined;
        $scope.image_file = undefined;
        
        element.find('img').attr('src', '{{ image }}')
        var tpl = $("#popover-widget-image").html();

        $scope.$watch('image', function(new_value, old_value){
            $compile(element.contents())($scope);
            $(element).popover({
                html: true,
                placement: "top",
                container: "body",
                content: function(){
                    return $compile(tpl)($scope)
                }
            });
        });
        $scope.upload_file = function(e){
            var file = e.files[0];
            var reader  = new FileReader();

            reader.onloadend = function () {
                $scope.image = reader.result;
                $scope.$apply();
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                $scope.image = undefined;
            }
        }
    }
}

app.directive('widget', function ($rootScope, $compile) {
    var linker = function (scope, element, attrs) {
        widgets[scope.type]($rootScope, $compile, scope, element, attrs);
    };

    return {
        restrict: 'AEC',
        link: linker,
        scope: {
            key: '@',
            type: '@'
        }
    };
});

app.directive('emailTemplate', function ($compile) {
    var linker = function (scope, element, attrs) {
        scope.$watch("email", function(new_value, old_value){
            if(scope.email){
                element.html(scope.email.template.body);
                $compile(element.contents())(scope);
            }
        });
    };

    return {
        restrict: 'AEC',
        link: linker,
        scope: {
            email: '='
        }
    };
});
