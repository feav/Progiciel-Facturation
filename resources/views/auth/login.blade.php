<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Connexion</title>

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="#" target='_blank' style="padding: 5px;">
                        Logiciel de Statistiques
                    </a>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Interface de connexion au Logiciel de Statistiques</div>
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="/login">
                                {{ csrf_field() }}
        
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">Adresse E-Mail: </label>
        
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <label for="password" class="col-md-4 control-label">Mot de passe: </label>
        
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> Se souvenir de moi?
                                            </label>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Se Connecter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/app.js"></script>
</body>
</html>
