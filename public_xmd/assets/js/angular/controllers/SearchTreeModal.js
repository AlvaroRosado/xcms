// Generated by CoffeeScript 1.10.0
(function() {
  var indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  angular.module('ximdex.main.controller').controller('SearchTreeModalCtrl', [
    '$scope', '$modalInstance', "xTranslate", "$window", "$http", "xUrlHelper", "xMenu", "$document", "$timeout", "$q", "nodetypesAllowedToShow", "nodetypesAllowedToSelect", "title", function($scope, $modalInstance, xTranslate, $window, $http, xUrlHelper, xMenu, $document, $timeout, $q, nodetypesAllowedToShow, nodetypesAllowedToSelect, title) {
      var actualFilter, canceler, findNodeById, getFolderPath, postLoadNodeChildren, postNavigateToNodeId, prepareBreadcrumbs;
      $scope.projects = null;
      $scope.filter = '';
      $scope.breadcrumbs = [];
      $scope.treeMode = true;
      $scope.filterMode = false;
      canceler = $q.defer();
      actualFilter = "";
      $scope.selectedNodes = [];
      if (nodetypesAllowedToSelect != null) {
        $scope.nodetypesAllowedToSelect = nodetypesAllowedToSelect;
      } else {
        $scope.nodetypesAllowedToSelect = null;
      }
      if (nodetypesAllowedToShow != null) {
        $scope.nodetypesAllowedToShow = nodetypesAllowedToShow;
      } else {
        $scope.nodetypesAllowedToShow = null;
      }
      $scope.title = title;
      $http.get(xUrlHelper.getAction({
        action: "browser3",
        method: "read",
        id: "10000"
      })).success(function(data) {
        if (data) {
          $scope.projects = data;
          $scope.projects.showNodes = true;
        }
        data = null;
      });
      $scope.toggleNode = function(node, event) {
        var action;
        if (node.isdir === "0") {
          action = {
            command: 'infonode',
            method: 'index',
            name: _("Node Info")
          };
          loadAction(action, [node]);
          return;
        }
        node.showNodes = !node.showNodes;
        if (node.showNodes && !node.collection) {
          $scope.loadNodeChildren(node);
        }
      };
      postLoadNodeChildren = function(data, callback, node) {
        var cancel, j, len, n, ref, ref1;
        node.loading = false;
        if (data && (data.collection != null) && data.collection.length > 0) {
          if ($scope.nodetypesAllowedToShow != null) {
            node.collection = [];
            ref = data.collection;
            for (j = 0, len = ref.length; j < len; j++) {
              n = ref[j];
              if ((ref1 = n.nodetypeid, indexOf.call($scope.nodetypesAllowedToShow, ref1) >= 0) || n.nodeid === "0") {
                node.collection.push(n);
              }
            }
            node.children = node.collection.length;
          } else {
            node.collection = data.collection;
            node.children = data.children;
          }
          node.state = data.state;
          if ($scope.treeMode === false && $scope.selectedTab === 1) {
            $scope.initialNodeList = node;
          }
          if (callback) {
            callback(node.collection);
          }
          callback = null;
        }
        $scope.initialNodeList = node;
        prepareBreadcrumbs();
        data = null;
        cancel = null;
      };
      $scope.loadNodeChildren = function(node, callback) {
        var fromTo, idToSend, maxItemsPerGroup, url;
        if (node.loading | node.isdir === "0") {
          if ($scope.treeMode === false) {
            $scope.initialNodeList = node;
            prepareBreadcrumbs();
          }
          return;
        }
        node.loading = true;
        node.showNodes = true;
        canceler.resolve();
        canceler = $q.defer();
        if ($scope.filterMode && $scope.selectedTab === 1) {
          node.collection = [];
          url = xUrlHelper.getAction({
            action: "browser3",
            method: "readFiltered",
            id: node.nodeid
          }) + "&query=" + actualFilter;
          $http.get(url, {
            timeout: canceler.promise
          }).success(function(data) {
            return postLoadNodeChildren(data, callback, node);
          }).error(function(data) {
            var cancel;
            node.loading = false;
            cancel = null;
          });
        } else {
          maxItemsPerGroup = parseInt($window.com.ximdex.preferences.MaxItemsPerGroup);
          fromTo = "";
          idToSend = node.nodeid;
          if (node.nodeid === "0" && (node.startIndex != null) && (node.endIndex != null)) {
            fromTo = "&from=" + node.startIndex + "&to=" + node.endIndex;
            idToSend = node.parentid;
          }
          $http.get(xUrlHelper.getAction({
            action: "browser3",
            method: "read",
            id: idToSend
          }) + ("&items=" + maxItemsPerGroup) + fromTo, {
            timeout: canceler.promise
          }).success(function(data) {
            return postLoadNodeChildren(data, callback, node);
          }).error(function(data) {
            var cancel;
            node.loading = false;
            cancel = null;
          });
          idToSend = null;
          fromTo = null;
          maxItemsPerGroup = null;
        }
      };
      $scope.select = function(node, event) {
        var ctrl, k, n, pushed, ref, ref1;
        ctrl = event.srcEvent != null ? event.srcEvent.ctrlKey : event.ctrlKey;
        if (ctrl) {
          ref = $scope.selectedNodes;
          for (k in ref) {
            n = ref[k];
            if (((n.nodeFrom == null) && (node.nodeFrom == null) && (n.nodeTo == null) && (node.nodeTo == null) && n.nodeid === node.nodeid) | ((n.nodeFrom != null) && (node.nodeFrom != null) && (n.nodeTo != null) && (node.nodeTo != null) && n.nodeFrom === node.nodeFrom && n.nodeTo === node.nodeTo)) {
              if (((event.button != null) && event.button === 0) || ((event.srcEvent != null) && event.srcEvent.button === 0)) {
                $scope.selectedNodes.splice(k, 1);
              }
              return;
            }
          }
          pushed = false;
          ref1 = $scope.selectedNodes;
          for (k in ref1) {
            n = ref1[k];
            if (n.nodeid > node.nodeid) {
              $scope.selectedNodes.splice(k, 0, node);
              pushed = true;
              break;
            }
          }
          if (!pushed) {
            $scope.selectedNodes.splice($scope.selectedNodes.length, 0, node);
          }
        } else {
          $scope.selectedNodes = [node];
        }
        ctrl = null;
      };
      $scope.reloadNode = function(nodeId, callback) {
        var action, n;
        if (nodeId != null) {
          n = findNodeById(nodeId, $scope.projects);
          if (n === null) {
            n = findNodeById(nodeId, $scope.ccenter);
          }
          if (n === null) {
            return;
          }
        } else if ($scope.selectedNodes.length === 1) {
          n = $scope.selectedNodes[0];
        } else {
          return;
        }
        if (n.isdir === "0") {
          action = {
            command: 'infonode',
            method: 'index',
            name: _("Node Info")
          };
          loadAction(action, [n]);
          return;
        }
        n.showNodes = true;
        n.collection = [];
        return $scope.loadNodeChildren(n, callback);
      };
      $scope.navigateToNodeId = function(nodeId) {
        if (nodeId == null) {
          return;
        }
        $http.get(xUrlHelper.getAction({
          method: "getTraverseForPath",
          id: nodeId,
          options: [
            {
              ajax: "json"
            }
          ]
        })).success(function(data) {
          return postNavigateToNodeId(data);
        });
      };
      postNavigateToNodeId = function(data) {
        var n, nodeList, shifted;
        nodeList = data['nodes'];
        shifted = nodeList.shift();
        if (shifted != null) {
          $scope.reloadNode(shifted.nodeid, callback);
        } else {
          n = findNodeById(nodeId, $scope.projects);
          if (n === null) {
            n = findNodeById(nodeId, $scope.ccenter);
          }
          if (n === null) {
            return;
          }
          $scope.select(n);
        }
        data = null;
        return nodeList = null;
      };
      $scope.doFilter = function() {
        if ($scope.filter.length > 2 && $scope.filter.match(/^[\d\w_\.-]+$/i)) {
          actualFilter = $scope.filter;
          $scope.filterMode = true;
          $scope.projects.showNodes = true;
          $scope.projects.collection = [];
          $scope.loadNodeChildren($scope.projects);
        } else if (actualFilter !== "") {
          actualFilter = "";
          $scope.filterMode = false;
          $scope.projects.showNodes = true;
          $scope.projects.collection = [];
          $scope.loadNodeChildren($scope.projects);
        }
        $scope.selectedNodes = [];
      };
      $scope.clearFilter = function() {
        if ($scope.filter !== '') {
          $scope.filter = '';
          $scope.doFilter();
        }
      };
      $scope.toggleView = function() {
        $scope.treeMode = !$scope.treeMode;
        if ($scope.treeMode === false && $scope.selectedTab === 1) {
          if ($scope.selectedNodes.length > 0 && $scope.selectedNodes[0].path.slice(0, 16) === "/Ximdex/Projects") {
            $scope.loadNodeChildren($scope.selectedNodes[0]);
          } else {
            $scope.loadNodeChildren($scope.projects);
          }
        }
      };
      $scope.goBreadcrums = function(index) {
        var actualNode, i, j, len, n, nodeFound, pathToNode, ref;
        pathToNode = $scope.breadcrumbs.slice(1, index + 1);
        actualNode = $scope.projects;
        nodeFound = false;
        while (pathToNode.length > 0) {
          nodeFound = false;
          ref = actualNode.collection;
          for (i = j = 0, len = ref.length; j < len; i = ++j) {
            n = ref[i];
            if ((n.name === pathToNode[0] && $scope.filterMode === false) | (n.originalName === pathToNode[0] && $scope.filterMode === true)) {
              actualNode = n;
              pathToNode.splice(0, 1);
              nodeFound = true;
              break;
            }
          }
          if (nodeFound === false) {
            return;
          }
        }
        $scope.loadNodeChildren(actualNode);
      };
      prepareBreadcrumbs = function() {
        var b, path;
        if ($scope.initialNodeList.nodeid === "0") {
          path = getFolderPath($scope.initialNodeList.collection[0].path);
        } else {
          path = $scope.initialNodeList.path;
        }
        if (path.slice(-1) === "/") {
          path = path.substring(0, path.length - 1);
        }
        if (path.slice(0, 1) === "/") {
          path = path.substring(1, path.length);
        }
        b = path.split("/");
        b.splice(0, 1);
        $scope.breadcrumbs = b;
        if ($scope.initialNodeList.isdir === "0") {
          $scope.goBreadcrums(b.length - 2);
        }
      };
      getFolderPath = function(path) {
        var n;
        n = path.lastIndexOf("/");
        if (n > 0) {
          return path.substring(0, n);
        }
        return path;
      };
      findNodeById = function(nodeId, source) {
        var i, item, j, len, queue, ref;
        queue = [source];
        while (queue.length > 0) {
          item = queue.pop();
          if (item.nodeid === nodeId) {
            return item;
          } else {
            if (item.collection != null) {
              ref = item.collection;
              for (j = 0, len = ref.length; j < len; j++) {
                i = ref[j];
                queue.push(i);
              }
            }
          }
        }
        return null;
      };
      $scope.$on('nodemodified', function(event, nodeId) {
        var node;
        node = findNodeById(nodeId, $scope.projects);
        if (node === null) {
          node = findNodeById(nodeId, $scope.ccenter);
        }
        if (node === null) {
          return;
        }
        if (node.isdir === "0") {
          return;
        }
        $scope.selectedNodes = [];
        node.showNodes = true;
        node.collection = [];
        return $scope.loadNodeChildren(node);
      });
      $scope.loadActions = function(node, event) {};
      $scope.error = false;
      $scope.ok = function() {
        var ref, ref1;
        if (((ref = $scope.selectedNodes) != null ? ref.length : void 0) === 1 && (ref1 = $scope.selectedNodes[0].nodetypeid, indexOf.call($scope.nodetypesAllowedToSelect, ref1) >= 0)) {
          return $modalInstance.close($scope.selectedNodes[0]);
        } else {
          return $scope.error = true;
        }
      };
      return $scope.cancel = function() {
        return $modalInstance.dismiss('cancel');
      };
    }
  ]);

}).call(this);

//# sourceMappingURL=SearchTreeModal.js.map
