<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.head')
</head>

<body class=""
  ng-app="themesApp"
  ng-controller="MainController"
  ng-class="{
              'static-header': !style_fixedHeader,
              'focusedform': style_fullscreen,
              'layout-horizontal': style_layoutHorizontal,
              'fixed-layout': style_layoutBoxed,
              'collapse-leftbar': style_leftbarCollapsed && !style_leftbarShown,
              'show-rightbar': style_rightbarCollapsed,
              'show-leftbar': style_leftbarShown
            }"
  ng-click="hideSearchBar();hideHeaderBar();"
  ng-cloak
>
    <div style="display: none;" ng-controller="AlertsController">
        <button id="successNotifButton" ng-click="simpleSuccessWithParams('Succès','Elément enregistré avec succès !')"></button>
        <button id="errorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur est survenue !')"></button>
        <button id="internalServerErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur interne du serveur  est survenue !')"></button>
    </div>
    
    @include('layouts.modals')

    <div id="headerbar" ng-class="{topNegative1000: style_headerBarHidden, topZero: !style_headerBarHidden}">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6 col-sm-2">
                    <button class="shortcut-tiles btn btn-brown" data-toggle="modal" data-target="#ajouterRouteurModal">
                        <i class="fa fa-plus-circle"></i> Ajouter un Routeur
                    </button>
                </div>
                <div class="col-xs-6 col-sm-2">
                    <button class="shortcut-tiles btn btn-indigo" data-toggle="modal" data-target="#ajouterBaseModal">
                        <i class="fa fa-plus-circle"></i> Ajouter une Base
                    </button>
                </div>
                <div class="col-xs-6 col-sm-2">
                    <button class="shortcut-tiles btn btn-primary" data-toggle="modal" data-target="#ajouterAnnonceurModal">
                        <i class="fa fa-plus-circle"></i> Ajouter un Annonceur
                    </button>
                </div>
                <div class="col-xs-6 col-sm-2">
                    <button class="shortcut-tiles btn btn-midnightblue" data-toggle="modal" data-target="#ajouterCampagneModal">
                        <i class="fa fa-plus-circle"></i> Ajouter une Campagne
                    </button>
                </div>
                <div class="col-xs-6 col-sm-2">
                    <button class="shortcut-tiles btn btn-orange" data-toggle="modal" data-target="#planifierEnvoiModal">
                        <i class="fa fa-plus-circle"></i> Planifier un envoi
                    </button>
                </div>      
            </div>
        </div>
    </div>

    @include('layouts.header')
    
	<nav class="navbar navbar-default ng-hide" role="navigation" ng-show="style_layoutHorizontal">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <i class="fa fa-reorder"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse" ng-class="{'large-icons-nav': style_layoutHorizontalLargeIcons}" id="horizontal-navbar">
              <ul ng-controller="NavigationController" id="top-nav" class="nav navbar-nav">
                  <li ng-repeat="item in menu"
                      ng-if="!item.hideOnHorizontal"
                      ng-class="{ hasChild: (item.children!==undefined),
                                    active: item.selected,
                                      open: (item.children!==undefined) && item.open }"
                      ng-include="'templates/nav_renderer_horizontal.html'"
                    ></li>
              </ul>
        </div>
    </nav>
	
    <div id="page-container" class="clearfix">
        <nav id="page-leftbar" role="navigation">
            <div>
              <ul ng-controller="NavigationController" id="sidebar" sticky-scroll="40">
                  <li ng-repeat="item in menu"
                      ng-class="{ hasChild: (item.children!==undefined),
                                    active: item.selected,
                                      open: (item.children!==undefined) && item.open }"
                      ng-include="'templates/nav_renderer.html'"
                    ></li>
              </ul>
            </div>
            <!-- END SIDEBAR MENU -->
        </nav>

        <div id="page-content" class="clearfix" fit-height>
            <div id="wrap" ng-view="" class="mainview-animation">
                
            </div>
        </div>

        @include('layouts.footer')

    </div>

    @include('layouts.scripts')

    <script>
        $(document).ready(function(){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            if(dd<10) dd='0'+dd;
            if(mm<10) mm='0'+mm;

            today = yyyy+'-'+mm+'-'+dd;
            
            $('#delai_paiement').attr("min", today);
            $('#date_envoi').attr("min", today);

            $('#ajouterBaseModal').on('show.bs.modal', function () {
                $.ajax({
                    url:'api/routeurs',
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#routeursPourBase').empty();
                            $.each(rep.body, function (index, value) {
                                $('#routeursPourBase').append($('<option/>', { 
                                    value: value.id,
                                    text : value.nom 
                                }));
                            });      
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });
            });

            $('#ajouterCampagneModal').on('show.bs.modal', function () {
                $.ajax({
                    url:'api/annonceurs',
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#annonceursPourCampagne').empty();
                            $.each(rep.body, function (index, value) {
                                $('#annonceursPourCampagne').append($('<option/>', { 
                                    value: value.id,
                                    text : value.nom 
                                }));
                            });      
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });
            });

            $('#planifierEnvoiModal').on('show.bs.modal', function () {
                $.ajax({
                    url:'api/annonceurs',
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#annonceursPourPlanning').empty();
                            $.each(rep.body, function (index, value) {
                                $('#annonceursPourPlanning').append($('<option/>', { 
                                    value: value.id,
                                    text : value.nom 
                                }));
                            });      
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });

                $.ajax({
                    url:'api/routeurs',
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#routeursPourPlanning').empty();
                            $.each(rep.body, function (index, value) {
                                $('#routeursPourPlanning').append($('<option/>', { 
                                    value: value.id,
                                    text : value.nom 
                                }));
                            });      
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });
            });

            $('#annonceursPourPlanning').change(function(){
                $("#campagnesPourPlanning option").removeAttr("selected");
                $('#campagnesPourPlanning').empty();
                var url = 'api/campagnes/parAnnonceur/'+$(this).val();
                $.ajax({
                    url: url,
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $.each(rep.body, function (index, value) {
                                $('#campagnesPourPlanning').append($('<option/>', { 
                                    value: value.id,
                                    text : value.nom 
                                }));
                            });
                            $('#campagnesPourPlanning').attr("disabled", false);
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });
            });

            $('#routeursPourPlanning').change(function(){
                $("#basesPourPlanning option").removeAttr("selected");
                $('#basesPourPlanning').empty();
                var url = 'api/bases/parRouteur/'+$(this).val();
                $.ajax({
                    url: url,
                    type:'GET',
                    success: function (rep) {
                        if(rep.code == 200){
                            $.each(rep.body, function (index, value) {
                                if(index == 0){
                                    $('#basesPourPlanning').append($('<option/>', { 
                                        value: value.id,
                                        text : value.nom,
                                        selected: true
                                    }));
                                }else{
                                    $('#basesPourPlanning').append($('<option/>', { 
                                        value: value.id,
                                        text : value.nom 
                                    }));
                                }
                                
                            });
                            $('#basesPourPlanning').attr("disabled", false);
                        }
                    },
                    error: function (res,status,err) { console.log(err) },
                    complete: function () {}
                });
            });

            $('#ajouterRouteurForm').submit(function(){
                $.ajax({
                    url: 'api/routeurs',
                    type:'POST',
                    data: $('#ajouterRouteurForm').serialize(),
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#ajouterRouteurModal').modal('hide');
                            $("#successNotifButton").click();
                            $('#ajouterRouteurForm')[0].reset();
                        }else
                            $("#errorNotifButton").click();
                    },
                    error: function (res,status,err) { console.log(err); $("#errorNotifButton").click(); },
                    complete: function () {}
                });
            });

            $('#ajouterBaseForm').submit(function(){
                $.ajax({
                    url: 'api/bases',
                    type:'POST',
                    data: $('#ajouterBaseForm').serialize(),
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#ajouterBaseModal').modal('hide');
                            $("#successNotifButton").click();
                            $('#ajouterBaseForm')[0].reset();
                        }else
                            $("#errorNotifButton").click();
                    },
                    error: function (res,status,err) { console.log(err); $("#errorNotifButton").click(); },
                    complete: function () {}
                });
            });

            $('#ajouterAnnonceurForm').submit(function(){
                $.ajax({
                    url: 'api/annonceurs',
                    type:'POST',
                    data: $('#ajouterAnnonceurForm').serialize(),
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#ajouterAnnonceurModal').modal('hide');
                            $("#successNotifButton").click();
                            $('#ajouterAnnonceurForm')[0].reset();
                        }else
                            $("#errorNotifButton").click();
                    },
                    error: function (res,status,err) { console.log(err); $("#errorNotifButton").click(); },
                    complete: function () {}
                });
            });

            $('#ajouterCampagneForm').submit(function(){
                $.ajax({
                    url: 'api/campagnes',
                    type:'POST',
                    data: $('#ajouterCampagneForm').serialize(),
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#ajouterCampagneModal').modal('hide');
                            $("#successNotifButton").click();
                            $('#ajouterCampagneForm')[0].reset();
                        }else
                            $("#errorNotifButton").click();
                    },
                    error: function (res,status,err) { console.log(err); $("#errorNotifButton").click(); },
                    complete: function () {}
                });
            });

            $('#planifierEnvoiForm').submit(function(){
                $.ajax({
                    url: 'api/plannings',
                    type:'POST',
                    data: $('#planifierEnvoiForm').serialize(),
                    success: function (rep) {
                        if(rep.code == 200){
                            $('#planifierEnvoiModal').modal('hide');
                            $("#successNotifButton").click();
                            $('#planifierEnvoiForm')[0].reset();
                        }else
                            $("#errorNotifButton").click();
                    },
                    error: function (res,status,err) { console.log(err); $("#errorNotifButton").click(); },
                    complete: function () {}
                });
            });
        });
    </script>
</body>
</html>
