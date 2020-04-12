<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.head')
</head>

<body>
    <div style="display: none;" ng-controller="AlertsController">
        <button id="internalServerErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur interne du serveur  est survenue !')"></button>
    </div>

    <div class="container-fluid">
        <div class="verticalcenter"style="text-align: center;">
            <img src="images/logo-noir.png" alt="" width="150px" height="50px" style="margin: 10px auto;">
            <div class="panel panel-inverse" style="width:100%; max-width: 400px; margin: auto">
                <div class="panel-body">
                    <h3 class="text-center" style="margin-bottom: 25px;">Connectez-vous ici !</h3>
                    @if($errors->any())
                        <h5 class="text-center text-danger" style="margin: 15px auto; font-weight: 600">L'adresse email et le mot de passe ne correspondent pas !</h3>
                    @endif
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
                        <button id="loginFormSubmitButton" type="submit" class="btn btn-brown btn-block">Connexion</button>
                    </form>
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
                    success: function (rep) {
                        if(resp.code == 300)
                            $("#userNotFoundErrorNotifButton").click();
                     },
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
