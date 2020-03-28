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
                @if (Auth::user()->role_id == 1)
                    <ul ng-controller="NavigationController" id="sidebar" sticky-scroll="40">
                        <li ng-repeat="item in menu"
                            ng-class="{ hasChild: (item.children!==undefined),
                                            active: item.selected,
                                            open: (item.children!==undefined) && item.open }"
                            ng-include="'templates/nav_renderer.html'"
                            >
                        </li>
                    </ul>
                @else
                    <ul ng-controller="NavigationController" id="sidebar" sticky-scroll="40">
                        <li ng-repeat="item in menu2"
                            ng-class="{ hasChild: (item.children!==undefined),
                                            active: item.selected,
                                            open: (item.children!==undefined) && item.open }"
                            ng-include="'templates/nav_renderer.html'"
                            >
                        </li>
                    </ul>
                @endif
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

</body>
</html>
