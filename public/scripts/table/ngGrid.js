'use strict'

angular
    .module('theme.tables-ng-grid', [])
    .controller('TablesAdvancedController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        };
        $scope.totalServerItems = 0;
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };
        $scope.setPagingData = function (data, page, pageSize) {
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.myData = pagedData;
            $scope.totalServerItems = data.length;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('assets/demo/ng-data.json').success(function (largeLoad) {
                        data = largeLoad.filter(function (item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data, page, pageSize);
                    });
                } else {
                    $http.get('assets/demo/ng-data.json').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad, page, pageSize);
                    });
                }
            }, 100);
        };

        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);

        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);

        $scope.gridOptions = {
            data: 'myData',
            enablePaging: true,
            showFooter: true,
            totalServerItems: 'totalServerItems',
            pagingOptions: $scope.pagingOptions,
            filterOptions: $scope.filterOptions
        };
    }])
    .controller('IndexController', ['$scope', '$http', function ($scope, $http) {
        $scope.stats = null;
        $http.get('statisticsForDashboard').success(function (resp) { $scope.stats = resp });
    }])
    .controller('RouteursController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;

        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.routeurs = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('routeurs/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('routeurs/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);

        $scope.saveRouteur = function(data, id) {
            $http.put('routeurs/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Routeur', 'Routeur modifié avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Routeur', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };

        $scope.removeRouteur = function(id) {
            $http.delete('routeurs/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Routeur', 'Routeur supprimé avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Routeur', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };

        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('BasesController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.routeurs = [];
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.bases = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };
        
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('bases/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('bases/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        $http.get('routeurs').success(function (resp) { $scope.routeurs = resp.body; });
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.saveBase = function(data, id) {
            $http.put('bases/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Base', 'Base modifiée avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Base', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };
        
        $scope.removeBase = function(id) {
            $http.delete('bases/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Base', 'Base supprimée avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Base', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('AnnonceursController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;

        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.annonceurs = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('annonceurs/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('annonceurs/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.saveAnnonceur = function(data, id) {
            $http.put('annonceurs/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Annonceur', 'Annonceur modifié avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Annonceur', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };
        
        $scope.removeAnnonceur = function(id) {
            $http.delete('annonceurs/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Annonceur', 'Annonceur supprimé avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Annonceur', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('CampagnesController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.annonceurs = [];
        $scope.totalCurrentPageItems = 0;

        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.campagnes = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('campagnes/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('campagnes/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        $http.get('annonceurs').success(function (resp) { $scope.annonceurs = resp.body; });
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.saveCampagne = function(data, id) {
            if(data.annonceur)
            $http.put('campagnes/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Campagne', 'Campagne modifiée avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Campagne', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };
        
        $scope.removeCampagne = function(id) {
            $http.delete('campagnes/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Campagne', 'Campagne supprimée avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Campagne', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('PlanningsController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.routeurs = [];
        $scope.bases = [];
        $scope.annonceurs = [];
        $scope.campagnes = [];
        $scope.campagnesPourPlanning = [];
        $scope.basesPourPlanning = [];

        $scope.annonceur = null;
        $scope.campagne = null;
        $scope.routeur = null;
        $scope.base = null;

        $scope.filter_data = {
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment(),
            ranges: {
                'Ce Jour': [moment(), moment()],
                'Cette Semaine': [moment().startOf('week').add('days', 1), moment().endOf('week').add('days', 1)],
                'Ce mois': [moment().startOf('month'), moment().endOf('month')]
            }
        };

        $scope.annonceurChange = function(){
            $http.get('campagnes/parAnnonceur/'+$scope.annonceur).success(function (rep) {
                $scope.campagnesPourPlanning = rep.body
            });
        }

        $scope.routeurChange = function(){
            $http.get('bases/parRouteur/'+$scope.routeur).success(function (rep) {
                $scope.basesPourPlanning = rep.body
            });
        }

        $scope.validerFiltre = function (){
            $http.post('plannings/applyFilter/'+$scope.pagingOptions.pageSize+'?page='+$scope.pagingOptions.currentPage, $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            })
            .error(function (res,status,err) {
                $scope.notif('Plannings', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }

        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };

        $scope.setPagingData = function(data){  
            $scope.plannings = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('plannings/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('plannings/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        $http.get('routeurs').success(function (resp) { $scope.routeurs = resp.body; });
        $http.get('bases').success(function (resp) { $scope.bases = resp.body; });
        $http.get('annonceurs').success(function (resp) { $scope.annonceurs = resp.body; });
        $http.get('campagnes').success(function (resp) { $scope.campagnes = resp.body; });
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.savePlanning = function(data, id) {
            $http.put('plannings/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Planning', 'Planning modifié avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Planning', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };
        
        $scope.removePlanning = function(id) {
            $http.delete('plannings/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Planning', 'Planning supprimé avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Planning', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('ResultatsController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        };

        $scope.filter_data = {
            filtre_routeur: null,
            filtre_base: null,
            filtre_annonceur: null,
            filtre_campagne: null,
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.routeurs = [];
        $scope.bases = [];
        $scope.annonceurs = [];
        $scope.campagnes = [];

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment()
        };

        $scope.validerFiltre = function (){
            $http.post('resultats/applyFilter/'+$scope.pagingOptions.pageSize+'?page='+$scope.pagingOptions.currentPage, $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            })
            .error(function (res,status,err) {
                $scope.notif('Résultat', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }

        $scope.viderLesChamps = function (){
            $scope.filter_data.filtre_routeur= null;
            $scope.filter_data.filtre_base= null;
            $scope.filter_data.filtre_annonceur= null;
            $scope.filter_data.filtre_campagne= null;
        }
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.resultats = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('resultats/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('resultats/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        $http.get('routeurs').success(function (resp) { $scope.routeurs = resp.body; });
        $http.get('bases').success(function (resp) { $scope.bases = resp.body; });
        $http.get('annonceurs').success(function (resp) { $scope.annonceurs = resp.body; });
        $http.get('campagnes').success(function (resp) { $scope.campagnes = resp.body; });
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.saveResultat = function(data, id) {
            $http.put('resultats/'+id, data)
                .success(function (resp) {
                    $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                    $scope.notif('Résultat', 'Résultat modifié avec succès !', 'success');
                })
                .error(function (res,status,err) {
                    $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                    $scope.notif('Résultat', 'Une erreur est survenue durant la mise à jour !', 'error');
                });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])
    .controller('UsersController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.roles = [];
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };  
        $scope.setPagingData = function(data){  
            $scope.users = data.body;
            $scope.totalServerItems = data.total;
            $scope.totalCurrentPageItems = data.total_current_page;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('users/paginate/'+pageSize+'/searchText/'+searchText+'?page='+page).success(function (resp) {        
                        $scope.setPagingData(resp);
                    });
                } else {
                    $http.get('users/paginate/'+pageSize+'?page='+page).success(function (resp) {
                        $scope.setPagingData(resp);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        $http.get('roles').success(function (resp) { $scope.roles = resp.body; });
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.saveUser = function(data, id) {
            $http.put('users/'+id, data)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Utilisateur', 'Utilisateur modifié avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Utilisateur', 'Une erreur est survenue durant la mise à jour !', 'error');
            });
        };
        
        $scope.removeUser = function(id) {
            $http.delete('users/'+id)
            .success(function (resp) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Utilisateur', 'Utilisateur supprimé avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
                $scope.notif('Utilisateur', 'Une erreur est survenue durant la suppression !', 'error');
            });
        };
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };

        $scope.refreshTab = function () {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };
    }])
    .controller('StatsParAnnonceursController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.tab = [];

        $scope.filter_data = {
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment(),
            ranges: {
                '30 derniers jours': [moment().subtract('days', 30), moment()],
                '60 derniers jours': [moment().subtract('days', 60), moment()],
                '90 derniers jours': [moment().subtract('days', 90), moment()]
            }
        };
        
        $scope.validerFiltre = function (){
            $http.post('annonceurs/applyFilterForStatistics', $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                $scope.notif('Statistiques par Annonceurs', 'Filtrage effectué avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.notif('Statistiques par Annonceurs', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };

        $scope.setPagingData = function(resp, page, pageSize){  
            var pagedData = resp.data.body.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = resp.data.body.length;
            $scope.totalVolume = resp.totalVolume;
            $scope.totalPA = resp.totalPA;
            $scope.totalCA = resp.totalCA;
            $scope.totalMarge = resp.totalMarge;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('annonceurs/forStatistics/searchText/'+searchText).success(function (resp) {        
                        $scope.setPagingData(resp, page, pageSize);
                    });
                } else {
                    $http.get('annonceurs/forStatistics/').success(function (resp) {
                        $scope.setPagingData(resp, page, pageSize);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])
    .controller('StatsParBasesController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.tab = [];

        $scope.filter_data = {
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment(),
            ranges: {
                '30 derniers jours': [moment().subtract('days', 30), moment()],
                '60 derniers jours': [moment().subtract('days', 60), moment()],
                '90 derniers jours': [moment().subtract('days', 90), moment()]
            }
        };
        
        $scope.validerFiltre = function (){
            $http.post('bases/applyFilterForStatistics', $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                $scope.notif('Statistiques par Bases', 'Filtrage effectué avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.notif('Statistiques par Bases', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };

        $scope.setPagingData = function(resp, page, pageSize){  
            var pagedData = resp.data.body.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = resp.data.body.length;
            $scope.totalVolume = resp.totalVolume;
            $scope.totalPA = resp.totalPA;
            $scope.totalCA = resp.totalCA;
            $scope.totalMarge = resp.totalMarge;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('bases/forStatistics/searchText/'+searchText).success(function (resp) {        
                        $scope.setPagingData(resp, page, pageSize);
                    });
                } else {
                    $http.get('bases/forStatistics/').success(function (resp) {
                        $scope.setPagingData(resp, page, pageSize);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])
    .controller('StatsParRouteursController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.tab = [];
        
        $scope.filter_data = {
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment(),
            ranges: {
                '30 derniers jours': [moment().subtract('days', 30), moment()],
                '60 derniers jours': [moment().subtract('days', 60), moment()],
                '90 derniers jours': [moment().subtract('days', 90), moment()]
            }
        };
        
        $scope.validerFiltre = function (){
            $http.post('routeurs/applyFilterForStatistics', $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                $scope.notif('Statistiques par Routeurs', 'Filtrage effectué avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.notif('Statistiques par Routeurs', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };

        $scope.setPagingData = function(resp, page, pageSize){  
            var pagedData = resp.data.body.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = resp.data.body.length;
            $scope.totalVolume = resp.totalVolume;
            $scope.totalPA = resp.totalPA;
            $scope.totalCA = resp.totalCA;
            $scope.totalMarge = resp.totalMarge;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('routeurs/forStatistics/searchText/'+searchText).success(function (resp) {        
                        $scope.setPagingData(resp, page, pageSize);
                    });
                } else {
                    $http.get('routeurs/forStatistics/').success(function (resp) {
                        $scope.setPagingData(resp, page, pageSize);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])
    .controller('StatsParCampagnesController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        
        $scope.totalServerItems = 0;
        $scope.totalCurrentPageItems = 0;
        $scope.tab = [];
        $scope.totalVolume = 0;
        $scope.totalPA = 0;
        $scope.totalCA = 0;
        $scope.totalMarge = 0;

        $scope.filter_data = {
            filtre_date_debut : moment().subtract('days', 30).format('D MMMM YYYY'),
            filtre_date_fin : moment().format('D MMMM YYYY')
        };

        $scope.filtre_date_options = {
            opens: 'right',
            startDate: moment().subtract('days', 30),
            endDate: moment(),
            ranges: {
                '30 derniers jours': [moment().subtract('days', 30), moment()],
                '60 derniers jours': [moment().subtract('days', 60), moment()],
                '90 derniers jours': [moment().subtract('days', 90), moment()]
            }
        };
        
        $scope.validerFiltre = function (){
            $http.post('campagnes/applyFilterForStatistics', $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                $scope.notif('Statistiques par Campagnes', 'Filtrage effectué avec succès !', 'success');
            })
            .error(function (res,status,err) {
                $scope.notif('Statistiques par Campagnes', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }
        
        $scope.pagingOptions = {
            pageSizes: [15, 25, 50, 100],
            pageSize: 15,
            currentPage: 1,
            maxSize: 5
        };

        $scope.setPagingData = function(resp, page, pageSize){  
            var pagedData = resp.data.body.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = resp.data.body.length;
            $scope.totalVolume = resp.totalVolume;
            $scope.totalPA = resp.totalPA;
            $scope.totalCA = resp.totalCA;
            $scope.totalMarge = resp.totalMarge;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };

        $scope.pageSizeChange = function(){  
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        };

        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('campagnes/forStatistics/searchText/'+searchText).success(function (resp) {        
                        $scope.setPagingData(resp, page, pageSize);
                    });
                } else {
                    $http.get('campagnes/forStatistics/').success(function (resp) {
                        $scope.setPagingData(resp, page, pageSize);
                    });
                }
            }, 100);
        };
        
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
        
        $scope.$watch('pagingOptions', function (newVal, oldVal) {
            if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            }
        }, true);
        $scope.$watch('filterOptions', function (newVal, oldVal) {
            if (newVal !== oldVal) {
              $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
            }
        }, true);
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])