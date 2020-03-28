<style>
    i.fa{
        line-height: 1.5;
    }
</style>
<header class="navbar navbar-inverse" ng-class="{'navbar-fixed-top': style_fixedHeader, 'navbar-static-top': !style_fixedHeader}" role="banner">
    <a id="leftmenu-trigger" tooltip-placement="right" tooltip="Basculer la Barre de Menu" ng-click="toggleLeftBar()"></a>

    <div class="navbar-header pull-left">
        <a class="navbar-brand" href="/" style="color: #fff">Logiciel de Statistiques</a>
    </div>

    <ul class="nav navbar-nav pull-right toolbar userinfo">
        <li>
            <a href="" class="username">
                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                <img src="assets/demo/avatar/avatar.jpg" alt="Avatar" />
            </a>
        </li>
        <li>
            <a style="border-left: 1px solid #0e1216;" href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="pull-right">
                <i class="pull-right fa fa-fw fa-sign-out"></i> 
                <span>Déconnexion</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li> 
    </ul>
</header>