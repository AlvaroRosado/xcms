###*
\details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]

Ximdex a Semantic Content Management System (CMS)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

See the Affero GNU General Public License for more details.
You should have received a copy of the Affero GNU General Public License
version 3 along with Ximdex (see LICENSE file).

If not, visit http://gnu.org/licenses/agpl-3.0.html.

@author Ximdex DevTeam <dev@ximdex.com>
@version $Revision$
###
angular.module("ximdex.common.directive").directive "ximMenu", [
    "$window", "$timeout"
    ($window, $timeout) ->
        base_url = $window.X.baseUrl
        return (
            templateUrl: base_url+'/inc/js/angular/templates/ximMenu.html'
            restrict: "E"
            replace: true
            link: (scope, element, attrs, ctrl) ->
                if attrs.left
                    scope.left = attrs.left
                menuY = scope.options.length * 38 + 10
                windowY = $window.innerHeight
                finY = menuY + parseInt(attrs.top) - $window.document.body.scrollTop
                if finY > windowY
                    scope.top = (parseInt(attrs.top) - menuY)
                else
                    scope.top = attrs.top
                if attrs.expanded == "true"
                    scope.expanded = true
                else
                    scope.expanded = false
                return

        )
]