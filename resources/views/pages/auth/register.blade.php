<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.head')
</head>

<body ng-app="themesApp" ng-controller="MainController">
    <div style="display: none;" ng-controller="AlertsController">
        <button id="successNotifButton" ng-click="simpleSuccessWithParams('Succès','Votre compte a été créé avec succès !')"></button>
        <button id="passwordsNotEqualsErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Les mots de passe entrés ne correspondent pas !')"></button>
        <button id="userAlreadExistsErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Cet utilisateur existe déjà dans notre Base de Données !')"></button>
        <button id="internalServerErrorNotifButton" ng-click="simpleErrorWithParams('Erreur', 'Une erreur interne du serveur  est survenue !')"></button>
    </div>

    <div class="container-fluid">
        <div class="verticalcenter">
            <div class="panel panel-primary" style="width:100%; max-width: 400px; margin: auto">
                <div class="panel-body">
                    <h3 class="text-center" style="margin-bottom: 25px;"><b>Logiciel de Statistiques</b><br>Créez un compte ici !</h3>
                        <form id="registerForm" method="POST" class="form-horizontal" style="margin-bottom: 0px !important;">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input required type="text" class="form-control" id="name" name="name" value="" placeholder="Votre nom">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                        <input required type="email" class="form-control" id="email" name="email" value="" placeholder="Votre adresse email">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input required type="password" class="form-control" id="password" name="password" value="" placeholder="Entrez un mot de passe">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input required type="password" class="form-control" id="password2" name="password2" value="" placeholder="Confirmez le mot de passe">
                                    </div>
                                </div>
                            </div>
                            <button id="registerFormSubmitButton" type="submit" class="btn btn-success btn-block">Créer un compte</button>
                        </form>
                </div>
                <div class="panel-footer">
                    <span class="text-center" style="text-align: center;">
                        Vous avez déjà un compte ? <br>
                        <a href="/login" class="btn btn-link" style="padding-right:0">Connectez-vous</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.scripts')

    <script>
        $(document).ready(function(){
            $('#registerForm').submit(function(e){
                if($('#password').val() == $('#password2').val()){
                    $.ajax({
                        url: 'register',
                        type:'POST',
                        data: $('#registerForm').serialize(),
                        success: function (rep) { 
                            $("#successNotifButton").click();
                            window.location.href = "login";
                        },
                        error: function (res,status,err) { 
                            if (res.responseJSON.message.indexOf("Integrity constraint violation: 1062 Duplicata du champ") >= 0)
                                $("#userAlreadExistsErrorNotifButton").click();
                            else
                                $("#internalServerErrorNotifButton").click();
                        },
                        complete: function () {}
                    });
                }else
                    $("#passwordsNotEqualsErrorNotifButton").click();
            });
        });
    </script>
</body>
</html>
