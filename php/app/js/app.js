function DashboardCtrl($scope, $route, $routeParams) {
    $scope.todos = [{
        text: 'learn angular',
        done: true
    }, {
        text: 'build an angular app',
        done: false
    }];

    $scope.addTodo = function() {
        $scope.todos.push({
            text: $scope.todoText,
            done: false
        });
        $scope.todoText = '';
    };

    $scope.remaining = function() {
        var count = 0;
        angular.forEach($scope.todos, function(todo) {
            count += todo.done ? 0 : 1;
        });
        return count;
    };

    $scope.archive = function() {
        var oldTodos = $scope.todos;
        $scope.todos = [];
        angular.forEach(oldTodos, function(todo) {
            if (!todo.done) $scope.todos.push(todo);
        });
    };

    $scope.$on('$locationChangeStart', function(next, current) {
        alert('here');
    });

    $scope.date = "2-13";

    var data1 = [
        [
            [0, 10],
            [1, 50],
            [2, 20]
        ]
    ],
        data2 = [
            [
                [0, 4],
                [1, 2],
                [2, 4]
            ]
        ],
        curr = 1;

    $scope.data = data1;
    

    $scope.change = function() {
        if (curr === 1) {
            $scope.data = data2;
            curr = 2;
        } else {
            $scope.data = data1;
            curr = 1;
        }
    };

}

angular.module('prsApp', [])
// Register the 'myCurrentTime' directive factory method.
// We inject $timeout and dateFilter service since the factory method is DI.
.directive('barchart', function() {
    return {
        restrict: 'A',
        require: '?ngModel',
        link: function(scope, element, attr, controller) {

            var options = {};

            var getOptions = function() {
                return angular.extend({}, options, scope.$eval(attr.fltBarchartoptions));
            };

            var init = function(v) {
                if (controller) {
                    controller.$render = function() {
                        var temp_options = getOptions();
                        // need to add this due to it not being parsed from string correctly - probably due to angular security
                        Flotr.draw(element[0], v, temp_options);
                    };
                }

                if (controller) {
                    // Force a render to override
                    controller.$render();
                }
            };

            var data = scope[attr.ngModel];

            scope.$watch('data', init);
        }
    };
});