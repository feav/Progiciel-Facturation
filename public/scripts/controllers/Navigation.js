'use strict'

angular
  .module('theme.navigation-controller', [])
  .controller('NavigationController', ['$scope', '$location', '$timeout', '$global', function ($scope, $location, $timeout, $global) {
    $scope.menu = [
        {
            label: 'Tableau de Bord',
            iconClasses: 'fa fa-home',
            url: '#/'
        },
        {
            label: 'Routeurs',
            iconClasses: 'fa fa-sitemap',
            url: '#/routeurs'
        },
        {
            label: 'Bases',
            iconClasses: 'fa fa-building',
            url: '#/bases'
        },
        {
            label: 'Annonceurs',
            iconClasses: 'fa fa-flag',
            url: '#/annonceurs'
        },
        {
            label: 'Campagnes',
            iconClasses: 'fa fa-briefcase',
            url: '#/campagnes'
        },
        {
            label: 'Plannings',
            iconClasses: 'fa fa-calendar',
            url: '#/plannings'
        },
        {
            label: 'Résultats',
            iconClasses: 'fa fa-tasks',
            url: '#/resultats'
        },
        {
            label: 'Statistiques',
            iconClasses: 'fa fa-bar-chart-o',
            children: [
                {
                    label: 'Par Routeurs',
                    iconClasses: 'fa fa-sitemap',
                    url: '#/statistiques-par-routeurs'
                },
                {
                    label: 'Par Bases',
                    iconClasses: 'fa fa-building',
                    url: '#/statistiques-par-bases'
                },
                {
                    label: 'Par Annonceurs',
                    iconClasses: 'fa fa-users',
                    url: '#/statistiques-par-annonceurs'
                },
                {
                    label: 'Par Campagnes',
                    iconClasses: 'fa fa-briefcase',
                    url: '#/statistiques-par-campagnes'
                },
            ]
        },
        {
            label: 'Gestion des Utilisateurs',
            iconClasses: 'fa fa-users',
            url: '#/users'
        }
    ];

    $scope.menu2 = [
        {
            label: 'Tableau de Bord',
            iconClasses: 'fa fa-home',
            url: '#/'
        },
        {
            label: 'Routeurs',
            iconClasses: 'fa fa-sitemap',
            url: '#/routeurs'
        },
        {
            label: 'Bases',
            iconClasses: 'fa fa-building',
            url: '#/bases'
        },
        {
            label: 'Annonceurs',
            iconClasses: 'fa fa-flag',
            url: '#/annonceurs'
        },
        {
            label: 'Campagnes',
            iconClasses: 'fa fa-briefcase',
            url: '#/campagnes'
        },
        {
            label: 'Plannings',
            iconClasses: 'fa fa-calendar',
            url: '#/plannings'
        },
        {
            label: 'Résultats',
            iconClasses: 'fa fa-tasks',
            url: '#/resultats'
        },
        {
            label: 'Statistiques',
            iconClasses: 'fa fa-bar-chart-o',
            children: [
                {
                    label: 'Par Routeurs',
                    iconClasses: 'fa fa-sitemap',
                    url: '#/statistiques-par-routeurs'
                },
                {
                    label: 'Par Bases',
                    iconClasses: 'fa fa-building',
                    url: '#/statistiques-par-bases'
                },
                {
                    label: 'Par Annonceurs',
                    iconClasses: 'fa fa-users',
                    url: '#/statistiques-par-annonceurs'
                },
                {
                    label: 'Globales',
                    iconClasses: 'fa fa-tasks',
                    url: '#/statistiques-globales'
                },
            ]
        }
    ];
    
    var setParent = function (children, parent) {
        angular.forEach(children, function (child) {
            child.parent = parent;
            if (child.children !== undefined) {
                setParent (child.children, child);
            }
        });
    };

    $scope.findItemByUrl = function (children, url) {
      for (var i = 0, length = children.length; i<length; i++) {
        if (children[i].url && children[i].url.replace('#', '') == url) return children[i];
        if (children[i].children !== undefined) {
          var item = $scope.findItemByUrl (children[i].children, url);
          if (item) return item;
        }
      }
    };
    
    setParent ($scope.menu, null);
    setParent ($scope.menu2, null);
    
    $scope.openItems = [];
    $scope.selectedItems = [];
    $scope.selectedFromNavMenu = false;
    
    $scope.select = function (item) {
        // close open nodes
        if (item.open) {
            item.open = false;
            return;
        }
        for (var i = $scope.openItems.length - 1; i >= 0; i--) {
            $scope.openItems[i].open = false;
        };
        $scope.openItems = [];
        var parentRef = item;
        while (parentRef !== null) {
            parentRef.open = true;
            $scope.openItems.push(parentRef);
            parentRef = parentRef.parent;
        }

        // handle leaf nodes
        if (!item.children || (item.children && item.children.length<1)) {
            $scope.selectedFromNavMenu = true;
            for (var j = $scope.selectedItems.length - 1; j >= 0; j--) {
                $scope.selectedItems[j].selected = false;
            };
            $scope.selectedItems = [];
            var parentRef = item;
            while (parentRef !== null) {
                parentRef.selected = true;
                $scope.selectedItems.push(parentRef);
                parentRef = parentRef.parent;
            }
        };
    };

    $scope.$watch(function () {
      return $location.path();
    }, function (newVal, oldVal) {
      if ($scope.selectedFromNavMenu == false) {
        var item = $scope.findItemByUrl ($scope.menu, newVal);
        var item2 = $scope.findItemByUrl ($scope.menu2, newVal);
        if (item)
            $timeout (function () { $scope.select (item); });
        if (item2)
            $timeout (function () { $scope.select (item2); });
      }
      $scope.selectedFromNavMenu = false;
    });

    // searchbar
    $scope.showSearchBar = function ($e) {
        $e.stopPropagation();
        $global.set('showSearchCollapsed', true);
    }
    $scope.$on('globalStyles:changed:showSearchCollapsed', function (event, newVal) {
      $scope.style_showSearchCollapsed = newVal;
    });
    $scope.goToSearch = function () {
        $location.path('/extras-search')
    };
  }])











