<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.head')
</head>

<body>
    <div style="display: none;" ng-controller="AlertsController">
        <button id="userNotFoundErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'L'adresse email et le mot de passe entrés ne correspondent !')"></button>
        <button id="internalServerErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur interne du serveur  est survenue !')"></button>
    </div>

    <div class="container-fluid">
        <div class="verticalcenter">
            <div class="panel panel-primary" style="width:100%; max-width: 400px; margin: auto">
                <div class="panel-body">
                    <h3 class="text-center" style="margin-bottom: 25px;"><b>Logiciel de Statistiques</b><br>Connectez-vous ici !</h3>
                        <form id="loginForm" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email" class="control-label col-sm-4" style="text-align: left;">Email</label>
                                <div class="col-sm-8">
                                    <input type="text" required class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label col-sm-4" style="text-align: left;">Mot de passe</label>
                                <div class="col-sm-8">
                                    <input type="password" required class="form-control" id="password" name="password" placeholder="Mot de passe">
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="text-center">
                                    <label><input type="checkbox" name="remember_me"> Se souvenir de moi</label>
                                </div>
                            </div>
                            <button id="loginFormSubmitButton" type="submit" class="btn btn-primary btn-block">Connexion</button>
                        </form>
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        Pas de compte ?<a href="/register" class="btn btn-link" style="padding-right:0">Créer un compte</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.scripts')

    <script>
        $(document).ready(function(){
            $('#loginForm').submit(function(e){
                $.ajax({
                    url: 'login',
                    type:'POST',
                    data: $('#loginForm').serialize(),
                    success: function (rep) { },
                    error: function (res,status,err) { 
                        console.log(status);
                        console.log(err);
                        if(status == 500)
                            $("#internalServerErrorNotifButton").click();
                    },
                    complete: function () {}
                });
            });
        });
    </script>
</body>
</html>
