<header class="navbar navbar-inverse" ng-class="{'navbar-fixed-top': style_fixedHeader, 'navbar-static-top': !style_fixedHeader}" role="banner">
    <a id="leftmenu-trigger" tooltip-placement="right" tooltip="Basculer la Barre de Menu" ng-click="toggleLeftBar()"></a>

    <div class="navbar-header pull-left">
        <a class="navbar-brand" href="#/" style="color: #fff">Logiciel de Statistiques</a>
    </div>

    <ul class="nav navbar-nav pull-right toolbar">
        <li class="dropdown" ng-show="isLoggedIn">
            <a href="#" class="dropdown-toggle username"><span class="hidden-xs">John Doe</span><img src="assets/demo/avatar/dangerfield.png" alt="Dangerfield" /></a>
            <ul class="dropdown-menu userinfo arrow">
            <li class="userlinks">
                <ul class="dropdown-menu">
                    <li><a href="" class="text-right" ng-click="logOut()"><i class="pull-left fa fa-fw fa-sign-out"></i> Déconnexion</a></li>
                </ul>
            </li>
            </ul>
        </li>
        <li ng-click="showHeaderBar($event)">
            <a href="" id="headerbardropdown"><span><i class="fa fa-plus"></i> Ajouter</span></a>
        </li>
    </ul>
</header>