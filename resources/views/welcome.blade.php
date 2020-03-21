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
        <button id="successNotifButton" ng-click="simpleSuccessWithParams('Succès','Elément enregistré avec succès !')">Notification SUCCES</button>
        <button id="errorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur est survenue !')">Notification ERREUR</button>
    </div>
    <div class="modal" id="ajouterRouteurModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulaire d'ajout d'un Routeur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajouterRouteurForm" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="nomRouteur" class="col-sm-3 col-form-label">Nom du Routeur *</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control" id="nomRouteur" name="nom">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prixRouteur" class="col-sm-3 col-form-label">Prix du Routeur *</label>
                            <div class="col-sm-9">
                                <input type="number" required min="0" class="form-control" id="prixRouteur" placeholder="en €" name="prix">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="ajouterBaseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulaire d'ajout d'une Base</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajouterBaseForm" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="nomBase" class="col-sm-3 col-form-label">Nom de la Base *</label>
                            <div class="col-sm-9">
                                <input type="text" required class="form-control" id="nomBase" name="nom">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="routeursPourBase" class="col-sm-3 col-form-label">Sélectionnez un Routeur *</label>
                            <div class="col-sm-9">
                                <select ui-select2 ng-model="routeurPourBase" required id="routeursPourBase" placeholder="Sélectionnez un routeur..." name="routeur" style="width:100%">
                                </select>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="ajouterAnnonceurModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulaire d'ajout d'un Annonceur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajouterAnnonceurForm" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="nomAnnonceur" class="col-sm-4 col-form-label">Nom de l'Annonceur *</label>
                            <div class="col-sm-8">
                                <input type="text" required class="form-control" id="nomAnnonceur" name="nom">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="urlAnnonceur" class="col-sm-4 col-form-label">URL de l'Annonceur</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="urlAnnonceur" name="url">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="adresse_facturation" class="col-sm-4 col-form-label">Adresse de Facturation *</label>
                            <div class="col-sm-8">
                                <input type="text" required class="form-control" id="adresse_facturation" name="adresse_facturation">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email_comptabilite" class="col-sm-4 col-form-label">Email Comptabilité</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email_comptabilite" name="email_comptabilite">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email_direction" class="col-sm-4 col-form-label">Email Direction</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email_direction" name="email_direction">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email_production" class="col-sm-4 col-form-label">Email Production</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email_production" name="email_production">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="delai_paiement" class="col-sm-4 col-form-label">Délai de Paiement *</label>
                            <div class="col-sm-8">
                                <input type="date" required class="form-control" id="delai_paiement" name="delai_paiement">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="ajouterCampagneModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulaire d'ajout d'une Campagne</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajouterCampagneForm" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="nomCampagne" class="col-sm-4 col-form-label">Nom de la Campagne *</label>
                            <div class="col-sm-8">
                                <input type="text" required class="form-control" id="nomCampagne" name="nom">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type_remuneration" class="col-sm-4 col-form-label">Type de rémuneration *</label>
                            <div class="col-sm-8">
                                <input type="text" required class="form-control" id="type_remuneration" name="type_remuneration">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remuneration" class="col-sm-4 col-form-label">Rémuneration *</label>
                            <div class="col-sm-8">
                                <input type="number" min="0" required placeholder="en €" class="form-control" id="remuneration" name="remuneration">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="annonceursPourCampagne" class="col-sm-4 col-form-label">Sélectionnez un Annonceur *</label>
                            <div class="col-sm-8">
                                <select ui-select2 ng-model="annonceurPourCampagne" required id="annonceursPourCampagne" name="annonceur" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="planifierEnvoiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulaire de planification d'un envoi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="planifierEnvoiForm" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="annonceursPourPlanning" class="col-sm-5 col-form-label">Sélectionnez un Annonceur *</label>
                            <div class="col-sm-7">
                                <select ui-select2 ng-model="annonceurPourPlanning" required id="annonceursPourPlanning" name="annonceur" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="campagnesPourPlanning" class="col-sm-5 col-form-label">Sélectionnez une Campagne *</label>
                            <div class="col-sm-7">
                                <select ui-select2 disabled ng-model="campagnePourPlanning" required id="campagnesPourPlanning" name="campagne" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="routeursPourPlanning" class="col-sm-5 col-form-label">Sélectionnez un Routeur *</label>
                            <div class="col-sm-7">
                                <select ui-select2 ng-model="routeurPourPlanning" required id="routeursPourPlanning" name="routeur" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="basesPourPlanning" class="col-sm-5 col-form-label">Sélectionnez une Base *</label>
                            <div class="col-sm-7">
                                <select ui-select2 disabled ng-model="basePourPlanning" required id="basesPourPlanning" name="base" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="volume" class="col-sm-5 col-form-label">Volume envoyé *</label>
                            <div class="col-sm-7">
                                <input type="number" min="0" required class="form-control" id="volume" name="volume">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date_envoi" class="col-sm-5 col-form-label">Date d'envoi *</label>
                            <div class="col-sm-7">
                                <input type="date" required class="form-control" id="date_envoi" name="date_envoi">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
