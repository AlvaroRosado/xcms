{**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 *}

<div id="%=id%" class="browser-window %=class%">
<div class="browser-window-content">
<div class="hbox browser-hbox">
<div ng-controller="XTreeCtrl" ng-mouseleave="hideTree()"  id="angular-tree"
     class="hbox-panel-container hbox-panel-container-0 hbox-panel-hideable noselect">
    {literal}
        <script id="template/tabs/tabset.html" type="text/ng-template">
            <div class="hbox-panel">
                <div class="xim-tabs-nav">
                    <div class="xim-tabs-list-selector xim-hidden-tab"></div>
                    <ul class="xim-tabs-list" style="display:none"></ul>
                </div>
                <div class="ui-tabs ui-widget ui-widget-content ui-corner-all tabs-container">
                    <ul class="ul ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all {{type && 'nav-' + type}}"
                        ng-class="{'nav-stacked': vertical, 'nav-justified': justified}" ng-transclude>
                        <li>
                            <div class="browser-view-title">{{'browser.headings.'+tab.heading | xI18n}}</div>
                        </li>
                    </ul>
                    <div class="tab-content browser-view browser-projects-view-content ui-tabs-panel ui-widget-content ui-corner-bottom">
                        <div class="browser-view ui-tabs-panel ui-widget-content ui-corner-bottom tab-pane"
                             ng-repeat="tab in tabs"
                             ng-class="{active: tab.active}"
                             tab-content-transclude="tab">
                            <div class="browser-view-title">{{'browser.headings.'+tab.heading | xI18n}}</div>
                        </div>
                    </div>
                </div>
            </div>

        </script>
        <script id="template/tabs/tab.html" type="text/ng-template">
            <li ng-class="{'xim-first-tab': $first, 'ui-tabs-active': active, 'ui-state-active': active}"
                class="ui-state-default ui-corner-top">
                <a ng-click="select()" tab-heading-transclude class="ui-tabs-anchor browser-{{heading}}-view"><span>{{heading}}</span></a>
            </li>
        </script>
        <script type="text/ng-template" id="tree_item_renderer.html">
            <div class="noselect" hm-doubletap="toggleNode(node,$event)" hm-press="loadActions(node,$event)"
                 hm-tap="select(node,$event)" ng-right-click="loadActions(node,$event)"
                 ng-class="{'xim-treeview-container-selected': (node | nodeSelected: selectedNodes)}">
                <span class="ui-icon xim-actions-toggle-node"
                      ng-class="{'ui-icon-triangle-1-se': node.showNodes, 'ui-icon-triangle-1-e': !node.showNodes, 'icon-hidden': !node.children && (node.collection == null || node.collection.length==0)}"
                      hm-tap="toggleNode(node,$event)"></span>
                <span class="xim-treeview-icon icon-#/node.icon.split('.')[0]/#"></span>
                <span ng-if="node.nodeid=='10000'" class="xim-treeview-branch" >#/'browser.headings.'+node.name | xI18n/#</span>
                <span ng-if="node.nodeid!='10000'" class="xim-treeview-branch" ng-bind-html="node.name"></span>
                <span hm-tap="loadActions(node,$event)"
                      class="xim-actions-dropdown xim-treeview-actions-dropdown
                      ui-icon ui-icon-triangle-1-e"></span>
            </div>
            <ul class="xim-treeview-branch" ng-show="node.showNodes">
                <li ng-repeat="node in node.collection" ng-include="'tree_item_renderer.html'"
                    class="xim-treeview-node ui-draggable xim-treeview-expanded"></li>
            </ul>
            <ul class="xim-treeview-loading" id="treeloading-undefined" ng-show="node.showNodes && node.loading">
                <li></li>
                <img src="xmd/images/browser/hbox/loading.gif"></ul>
        </script>
    {/literal}
    <tabset class="ui-tabs ui-widget ui-widget-content ui-corner-all tabs-container">
        <tab heading="projects" select="$parent.$parent.selectedTab=1;">
            <div ng-if="treeMode" ><xim-tree /></div>
            <div ng-if="!treeMode" ><xim-list /></div>
        </tab>
        <tab heading="ccenter" select="$parent.$parent.selectedTab=2;">
            <div class="browser-projects-view-treecontainer xim-treeview-container" style="display: block;">
                <div ng-click="reloadNode()"
                     class="xim-treeview-btnreload ui-corner-all ui-state-default">#/ 'browser.reload_node' | xI18n /#</div>
                <div ng-if="ccenter!='null' && ccenter!=null"
                     class="xim-treeview-branch-container xim-treeview-expanded">
                    <ul class="xim-treeview-branch">
                        <li ng-repeat="node in ccenter.collection" ng-include="'tree_item_renderer.html'"
                            class="xim-treeview-node ui-draggable xim-treeview-expanded"></li>
                    </ul>
                </div>
            </div>
        </tab>
        <tab heading="modules" select="$parent.$parent.selectedTab=3;">
            <div class="browser-modules-view-list-container" style="display: block;">
                <ul ng-if="modules!='null' && modules!=null" class="browser-modules-view-list">
                    <li
                            {literal}
                                ng-class="{'browser-modules-view-enabled': node.enabled, 'browser-modules-view-disabled': !node.enabled}"
                            {/literal}
                            ng-repeat="node in modules"
                            hm-tap="openModuleAction(node)">#/node.name/#
                    </li>
                </ul>
            </div>
        </tab>
    </tabset>

    {literal}
        <button hm-tap="toggleView()" ng-show="selectedTab == 1" ng-class="{'btn-view-list': !treeMode}" class="btn btn-sidebar btn-treeview btn-view" title="Change view"></button>
    {/literal}
    <button id="angular-tree-toggle" ng-click="toggleTree($event)" class="btn btn-sidebar btn-anchor" type="button" title="Collapse menu"></button>

    <div class="filter-tree" ng-show="selectedTab==1">
        <input ng-change="doFilter()" ng-model="filter" type="text" class="form-control" placeholder="#/('browser.filter' | xI18n)+'...'/#">
    </div>

    <div id="angular-tree-resizer"
         hm-panstart="dragStart($event)" hm-panmove="drag($event,'10')" hm-panend="dragEnd()"
         ng-mouseenter="showTree()"
         class="hbox-panel-sep hbox-panel-separator-0">
    </div>
</div>

<div id="angular-content" ng-cloak ng-controller="XTabsCtrl" class="angular-panel hbox-panel-container hbox-panel-container-1">
<div class="hbox-panel">
<div class="xim-tabs-nav noselect">
    <div ng-show="menuTabsEnabled" hm-tap="showingMenu=!showingMenu; return;" class="xim-tabs-list-selector"></div>
    <ul ng-show="showingMenu" class="xim-tabs-list">
        <li class="xim-tabs-list-item" hm-tap="closeMenu(); offAllTabs();">#/'browser.welcome_to_the_brand_new_Ximdex!' | xI18n/#</li>
        <li class="xim-tabs-list-item" ng-repeat="tab in tabs" hm-tap="closeMenu(); $parent.setActiveTab($index);">
            <span ng-if="$index != $parent.activeIndex()">#/tab.name/#</span>
            <strong ng-if="$index == $parent.activeIndex()">#/tab.name/#</strong>
        </li>
        <li class="xim-tabs-list-item tabswidget-close-all" hm-tap="closeMenu(); closeAllTabs();">Cerrar todas las pestañas</li>
    </ul>
</div>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all tabs-container">
<ul class="ul ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <li class="ui-state-default ui-corner-top xim-first-tab ng-hide">
        <div class="ui-tab-close"></div>
        <a class="ui-tabs-anchor browser-action-view"
           id="10000_welcome">
            <span>#/'browser.welcome_to_the_brand_new_Ximdex!' | xI18n/#</span></a></li>
    {literal}
    <li hm-tap="$parent.setActiveTab($index)" ng-repeat="tab in tabs" class="ui-state-default ui-corner-top"
        ng-class="{'ui-tabs-active ui-state-active': ($index == $parent.activeIndex()),
        'xim-last-tab': $last, 'blink': tab.blink, 'hide': ($parent.limitTabs<=$index)}" id="#/tab.id/#_tab">
        <div hm-tap="removeTab($index);" class="ui-tab-close"></div>
        <a class="ui-tabs-anchor browser-action-view"><span>#/tab.name/#</span></a></li>
    {/literal}
</ul>
<div ng-show="activeIndex()<0" class="browser-action-view-content ui-tabs-panel
        ui-widget-content ui-corner-bottom"
        id="10000_welcome_content" >
</div>
<div ng-show="$index == $parent.activeIndex()" ng-repeat="tab in tabs"
     class="browser-action-view-content ui-tabs-panel ui-widget-content ui-corner-bottom"
     id="#/tab.id/#_content">

</div>
</div>
</div>
</div>

</div>
</div>
</div>