<?php

/**
 *  \details &copy; 2018 Open Ximdex Evolution SL [http://www.ximdex.org]
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
 * @author Ximdex DevTeam <dev@ximdex.com>
 * @version $Revision$
 */

namespace Ximdex\MVC\Render;

use Ximdex\Models\Action;
use Ximdex\Models\Node;
use Ximdex\Runtime\App;
use Ximdex\Runtime\Session;

/**
 * @brief Abstract renderer who acts as base for all renderers
 *
 * Pseudo Abstract renderer class who stablish a base for all renderers, provides
 * some basic functionality and deals with some session parameters
 */
class AbstractRenderer
{
    var $_template;

    /**
     * @var \Ximdex\Behaviours\AssociativeArray
     */
    var $_parameters;

    /**
     * AbstractRenderer constructor
     * 
     * @param null $fileName
     */
    function __construct($fileName = NULL)
    {
        $this->displayEncoding = App::getValue('displayEncoding');
        $this->_template = $fileName;
        $this->_parameters = new \Ximdex\Behaviours\AssociativeArray();
    }

    function getTemplate()
    {
        return $this->_template;
    }

    function setTemplate($fileName)
    {
        $this->_template = $fileName;
    }

    function add($key, $value)
    {
        return $this->_parameters->add($key, $value);
    }

    /**
     * @return array
     */
    function & getParameters()
    {
        return $this->_parameters->getArray();
    }

    function setParameters($array)
    {
        if (is_array($array)) {
            foreach ($array as $idx => $data) {
                $this->set($idx, $data);
            }
        }
    }

    function set($key, $value)
    {
        return $this->_parameters->set($key, $value);
    }

    function render($view = null)
    {
        $actionID = $this->get('actionid');
        $nodeID = $this->get('nodeid');
        $actionName = $this->get('actionName');
        $module = $this->get('module');
        $method = $this->get('method');
        $method = empty($method) ? 'index' : $method;
        $method = empty($view) ? $method : $view;
        $action = new Action($actionID);
        $_ACTION_COMMAND = ($actionName) ? $actionName : $action->get('Command');
        $base_action = null;

        // Si se ha lanzado una accion se visualiza la accion, sino se ejecuta el composer
        if (! isset($_ACTION_COMMAND) || $_ACTION_COMMAND != "composer") {

            // Definicion de algunos parametros utiles
            if ($nodeID > 0) {
                $this->_set_node_params($nodeID);
            }
            $this->set('id_action', $actionID);
            $this->_set_action_url($this->get('action_url'), $nodeID, $actionID, $actionName);
            $base_action = $this->_set_module($module, $_ACTION_COMMAND);
            $this->_set_action_property($action->get('Name'), $action->get('Description'), $_ACTION_COMMAND, $base_action);
        } else {
            
            //Visualizamos el composer(al no haber accion)
            $_ACTION_COMMAND = "composer";
            $this->_set_action_property("composer", "visualiza los componentes de la web", "composer", "/actions/composer/");
        }
        $this->set('_URL_ROOT', App::getValue('UrlRoot'));
        $this->set('_APP_ROOT', XIMDEX_ROOT_PATH);

        // Si es la misma accion que se ha ejecutado en FrontControllerHttp:
        // Guardamos los datos en los valores de session
        $this->_set_session_params($actionID, $_ACTION_COMMAND, $method, $nodeID, $module, $base_action);
        
        // Encode the content to the display Encoding from Config
        foreach ($this->_parameters->_data as $key => $value) {
            if (is_array($value)) {
                $this->_parameters->_data[$key] = \Ximdex\XML\Base::encodeArrayElement($this->_parameters->_data[$key], $this->displayEncoding);
            } else {
                $this->_parameters->_data[$key] = \Ximdex\XML\Base::encodeSimpleElement($this->_parameters->_data[$key], $this->displayEncoding);
            }
        }
    }

    function & get($key)
    {
        return $this->_parameters->get($key);
    }
    
    private function _set_node_params($nodeID)
    {
        $node = new Node($nodeID);
        
        // Mandamos el padre a la plantilla para cuando toque recargar el node
        $this->set('id_node_parent', $node->get('IdParent'));
        $this->set('node_name', $node->get('Name'));
        $this->set('id_node', $nodeID);
        $path = pathinfo($node->GetPath());
        $ruta = "";
        if (! empty($path) && array_key_exists("dinarme", $path)) {
            $path_split = explode("/", $path['dirname']);
            $max = count($path_split);
            for ($i = 0; $i < $max; $i++) {
                if (!empty($path_split[$i]))
                    $path_split[$i] = _($path_split[$i]) . " ";
            }
            $path["dirname"] = implode("/", $path_split);
            $ruta .= $path['dirname'] . '/<b>';
        }
        if (! empty($path["basename"])) {
            $path["basename"] = _($path['basename']);
            $ruta .= $path["basename"] . "</b>";
        } else {
            $ruta .= "</b>";
        }
        $ruta = str_replace("/", "/ ", $ruta);
        $ruta = str_replace("</ b>", "</b>", $ruta);
        $this->set('_NODE_PATH', $ruta);
    }

    private function _set_action_url($action_url = null, $nodeID = null, $actionID = null, $actionName = null)
    {
        // If a destination URL have not been given, we add the default action
        if ($action_url == null) {
            $query = App::get('\Ximdex\Utils\QueryManager');
            $go_method = $this->get('go_method');
            if (! empty($go_method)) {
                $query->add('method', $go_method);
            }
            if (! empty($actionID)) {
                $query->add('actionid', $actionID);
            }
            if (! empty($actionName)) {
                $query->add('action', $actionName);
            }
            if (! empty($nodeID)) {
                $query->add('nodeid', $nodeID);
                $query->add('nodes', array($nodeID));
            }
            $this->set('action_url', $query->getPage() . $query->build());
        }
    }

    private function _set_module($module, $action_command)
    {
        if ($module) {
            $base_action = XIMDEX_ROOT_PATH . \Ximdex\Modules\Manager::path($module) . "/actions/" . $action_command . "/";
            
            // We indicate the specfieds module parameters
            $this->set("base_module", XIMDEX_ROOT_PATH . \Ximdex\Modules\Manager::path($module) . "/");
            $this->set("module", $module);
        } else {
            $base_action = "/public_xmd/actions/" . $action_command . "/";
        }
        return $base_action;
    }

    private function _set_action_property($_name, $_desc, $_command, $_base)
    {
        $this->set('_ACTION_COMMAND', $_command);
        $this->set("base_action", $_base);
    }

    /**
     * @param $actionID
     * @param $_ACTION_COMMAND
     * @param $method
     * @param $nodeID
     * @param $module
     * @param $base_action
     */
    private function _set_session_params($actionID, $action_command, $method, $nodeID, $module, $base_action)
    {
        if (Session::get("actionId") == $actionID) {
            Session::set("action", $action_command);
            Session::set("method", $method);
            Session::set("nodeId", $nodeID);
            Session::set("module", $module);
            Session::set("base_action", $base_action);
        }
    }
}
