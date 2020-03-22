<header class="navbar navbar-inverse" ng-class="{'navbar-fixed-top': style_fixedHeader, 'navbar-static-top': !style_fixedHeader}" role="banner">
    <a id="leftmenu-trigger" tooltip-placement="right" tooltip="Basculer la Barre de Menu" ng-click="toggleLeftBar()"></a>

    <div class="navbar-header pull-left">
        <a class="navbar-brand" href="/" style="color: #fff">Logiciel de Statistiques</a>
    </div>

    <ul class="nav navbar-nav pull-right toolbar">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle username">
                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                <img src="assets/demo/avatar/avatar.jpg" alt="Avatar" />
            </a>
            <ul class="dropdown-menu userinfo arrow">
                <li class="userlinks">
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="pull-right"><i class="pull-left fa fa-fw fa-sign-out"></i> Déconnexion</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li ng-click="showHeaderBar($event)">
            <a href="" id="headerbardropdown"><span><i class="fa fa-plus"></i> Ajouter</span></a>
        </li>
        {{--  <li>
            <a href="" class="username">
                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                <img src="assets/demo/avatar/avatar.jpg" alt="Avatar" />
            </a>
        </li>
        <li class="userlinks">
            <a style="border-left: 1px solid #0e1216;" href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="pull-right"><i class="pull-left fa fa-fw fa-sign-out"></i> Déconnexion</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>  --}}
    </ul>
</header>