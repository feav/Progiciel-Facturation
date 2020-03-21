<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.head')
</head>

<body class=""
>
    <div style="display: none;" ng-controller="AlertsController">
        <button id="successNotifButton" ng-click="simpleSuccessWithParams('Succès','Elément enregistré avec succès !')">Notification SUCCES</button>
        <button id="errorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur est survenue !')">Notification ERREUR</button>
    </div>
    @verbatim
    <div class="container-fluid">
        <div class="verticalcenter">
            <div class="panel panel-primary" style="width:100%; max-width: 400px; margin: auto">
                <div class="panel-body">
                    <h3 class="text-center" style="margin-bottom: 25px;">Interface de connexion au <br><b>Logiciel de Statistiques</b></h3>
                        <form action="#" class="form-horizontal">
                            <div class="form-group">
                                <label for="email" class="control-label col-sm-4" style="text-align: left;">Email</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label col-sm-4" style="text-align: left;">Mot de passe</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" placeholder="Mot de passe">
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="pull-right"><label><input type="checkbox" checked> Se souvenir de moi</label></div>
                            </div>
                            <button class="btn btn-primary btn-block" ng-click="logIn()" data-pulsate="{reach:10, pause: 2000}">Connexion</button>
                        </form>
                </div>
                <div class="panel-footer">
                    <button class="pull-left btn btn-link" style="padding-left:0">Mot de passe oublié ?</button>
                </div>
            </div>
        </div>
    </div>
    @endverbatim                      
            

    @include('layouts.scripts')

    <script>
        $(document).ready(function(){
            
        });
    </script>
</body>
</html>
