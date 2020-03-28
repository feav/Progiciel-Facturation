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
    .controller('RouteursController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
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
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.routeurs = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('routeurs').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('routeurs').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        $scope.routeurs = [];
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.bases = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('bases').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('bases').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.annonceurs = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('annonceurs').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('annonceurs').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.campagnes = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('campagnes').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('campagnes').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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

        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.plannings = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('plannings').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('plannings').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
            $http.post('resultats/applyFilter', $scope.filter_data)
            .success(function (resp) {
                $scope.setPagingData(resp.body, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            })
            .error(function (res,status,err) {
                $scope.notif('Résultat', 'Une erreur est survenue durant le filtrage !', 'error');
            });
        }
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.resultats = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('resultats').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('resultats').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        $scope.roles = [];
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.users = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('users').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('users').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        $scope.tab = [];
        $scope.copyTab = [];
        $scope.filtre_delai_debut = null;
        $scope.filtre_delai_fin = null;

        $scope.validerFiltre = function (){
            if($scope.filtre_delai_debut < 0 || $scope.filtre_delai_fin < 0){
                $scope.notif('Résultats par Annonceurs', 'Aucune des deux valeurs ne doit être inférieure à 0 !', 'error');
            }else if($scope.filtre_delai_debut > $scope.filtre_delai_fin){
                $scope.notif('Résultats par Annonceurs', 'Le premier nombre doit être inférieur au deuxième !', 'error');
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin == null){
                $scope.setPagingData($scope.copyTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin != null && $scope.filtre_delai_fin > 0){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut != null && $scope.filtre_delai_debut > 0 && $scope.filtre_delai_fin == null){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else{
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut && $scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                // var data_to_send = {
                //     filtre_delai_debut: $scope.filtre_delai_debut,
                //     filtre_delai_fin: $scope.filtre_delai_fin
                // }
                // $http.post('annonceurs/applyFilter', data_to_send)
                // .success(function (resp) {
                //     $scope.setPagingData(resp.body, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
                //     console.log(resp);
                //     $scope.notif('Statistiques par Annonceurs', 'Filtrage effectué avec succès !', 'success');
                // })
                // .error(function (res,status,err) {
                //     $scope.notif('Statistiques par Annonceurs', 'Une erreur est survenue durant le filtrage !', 'error');
                // });
            }
        }
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('annonceurs/forStatistics').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.copyTab = data;
                        $scope.setPagingData(data, page, pageSize);
                    });            
                } else {
                    $http.get('annonceurs/forStatistics').success(function (largeLoad) {
                        $scope.copyTab = largeLoad.body;
                        $scope.setPagingData(largeLoad.body, page, pageSize);
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
        $scope.tab = [];
        $scope.copyTab = [];
        $scope.filtre_delai_debut = null;
        $scope.filtre_delai_fin = null;

        $scope.validerFiltre = function (){
            if($scope.filtre_delai_debut < 0 || $scope.filtre_delai_fin < 0){
                $scope.notif('Résultats par Bases', 'Aucune des deux valeurs ne doit être inférieure à 0 !', 'error');
            }else if($scope.filtre_delai_debut > $scope.filtre_delai_fin){
                $scope.notif('Résultats par Bases', 'Le premier nombre doit être inférieur au deuxième !', 'error');
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin == null){
                $scope.setPagingData($scope.copyTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin != null && $scope.filtre_delai_fin > 0){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut != null && $scope.filtre_delai_debut > 0 && $scope.filtre_delai_fin == null){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else{
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut && $scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }
        }
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('bases/forStatistics').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.copyTab = data;
                        $scope.setPagingData(data, page, pageSize);
                    });            
                } else {
                    $http.get('bases/forStatistics').success(function (largeLoad) {
                        $scope.copyTab = largeLoad.body;
                        $scope.setPagingData(largeLoad.body, page, pageSize);
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
        $scope.tab = [];
        $scope.copyTab = [];
        $scope.filtre_delai_debut = null;
        $scope.filtre_delai_fin = null;

        $scope.validerFiltre = function (){
            if($scope.filtre_delai_debut < 0 || $scope.filtre_delai_fin < 0){
                $scope.notif('Résultats par Routeurs', 'Aucune des deux valeurs ne doit être inférieure à 0 !', 'error');
            }else if($scope.filtre_delai_debut > $scope.filtre_delai_fin){
                $scope.notif('Résultats par Routeurs', 'Le premier nombre doit être inférieur au deuxième !', 'error');
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin == null){
                $scope.setPagingData($scope.copyTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut == null && $scope.filtre_delai_fin != null && $scope.filtre_delai_fin > 0){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else if($scope.filtre_delai_debut != null && $scope.filtre_delai_debut > 0 && $scope.filtre_delai_fin == null){
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }else{
                var tempTab = [];
                for(var i=0; i<$scope.copyTab.length; i++)
                    if($scope.copyTab[i].annonceur.delai_paiement >= $scope.filtre_delai_debut && $scope.copyTab[i].annonceur.delai_paiement <= $scope.filtre_delai_fin)
                        tempTab.push($scope.copyTab[i]);
                $scope.setPagingData(tempTab, $scope.pagingOptions.currentPage, $scope.pagingOptions.pageSize);
            }
        }
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('routeurs/forStatistics').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.copyTab = data;
                        $scope.setPagingData(data, page, pageSize);
                    });            
                } else {
                    $http.get('routeurs/forStatistics').success(function (largeLoad) {
                        $scope.copyTab = largeLoad.body;
                        $scope.setPagingData(largeLoad.body, page, pageSize);
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
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])
    .controller('StatsGlobalesController', ['$scope', '$filter', '$http', 'pinesNotifications', function ($scope, $filter, $http, pinesNotifications) {
        $scope.filterOptions = {
            filterText: "",
            useExternalFilter: true
        }; 
        $scope.totalServerItems = 0;
        $scope.tab = [];
        $scope.filtre_delai_debut = null;
        $scope.filtre_delai_fin = null;

        $scope.validerFiltre = function (){
            console.log("validerFiltre");
            console.log("filtre_delai_debut: "+$scope.filtre_delai_debut);
            console.log("filtre_delai_fin: "+$scope.filtre_delai_fin);
            // $http.get('resultats/'+id)
            // .success(function (resp) {
            //     $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            //     $scope.notif('Résultat', 'Filtrage effectué avec succès !', 'success');
            // })
            // .error(function (res,status,err) {
            //     $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);
            //     $scope.notif('Résultat', 'Une erreur est survenue durant le filtrage !', 'error');
            // });
            $scope.notif('Statistiques Globales', 'Filtrage effectué avec succès !', 'success');
        }
        
        $scope.pagingOptions = {
            pageSizes: [25, 50, 100],
            pageSize: 25,
            currentPage: 1
        };  
        $scope.setPagingData = function(data, page, pageSize){  
            var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
            $scope.tab = pagedData;
            $scope.totalServerItems = data.length;
            $scope.enablePaging = true;
            $scope.showFooter = true;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
        };
        $scope.getPagedDataAsync = function (pageSize, page, searchText) {
            setTimeout(function () {
                var data;
                if (searchText) {
                    var ft = searchText.toLowerCase();
                    $http.get('routeurs/forStatistics').success(function (largeLoad) {        
                        data = largeLoad.body.filter(function(item) {
                            return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                        });
                        $scope.setPagingData(data,page,pageSize);
                    });            
                } else {
                    $http.get('routeurs/forStatistics').success(function (largeLoad) {
                        $scope.setPagingData(largeLoad.body,page,pageSize);
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
        
        $scope.notif = function (titre, message, type) {
            pinesNotifications.notify({
              title: titre,
              text: message,
              type: type
            });
        };
    }])