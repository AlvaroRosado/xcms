<?php

/**
 *  \details &copy; 2019 Open Ximdex Evolution SL [http://www.ximdex.org]
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

namespace Ximdex\Modules;

use Ximdex\Logger;
Use Ximdex\Utils\FsUtils;
use Ximdex\Runtime\App;

class Manager
{
    public static $msg = null;
    
    public $modules;
    
    public $caller;
    
    private static $core_modules = array("ximIO");
    
    private static $deprecated_modules = array("ximTEST");

    public static function get_modules_dir()
    {
        // Replaces XIMDEX_MODULES_DIR
        return XIMDEX_ROOT_PATH. "/modules/";
    }

    public static function get_modules_pro_dir()
    {
        // Replaces XIMDEX_MODULES_PRO_DIR
        return XIMDEX_ROOT_PATH. '/modules/modules_PRO/';
    }

    public static function get_module_prefix()
    {
        // Replaces MODULE_PREFIX
        return 'Module_';
    }

    public static function get_module_state_installed()
    {
        // Replaces MODULE_STATE_INSTALLED
        return 1;
    }

    public static function get_module_state_uninstalled()
    {
        // Replaces MODULE_STATE_UNINSTALLED
        return 0;
    }

    public static function get_module_state_error()
    {
        // Replaces MODULE_STATE_ERROR
        return -1;
    }

    public static function get_modules_install_params()
    {
        // Replaces MODULE_INSTALL_PARAMS
        return '/conf/install-modules.php';
    }

    public static function get_pre_define_module()
    {
        // Replaces PRE_DEFINE_MODULE
        return "define('MODULE_";
    }

    public static function get_post_define_module()
    {
        // Replaces POST_DEFINE_MODULE
        return "_ENABLED', 1);";
    }

    public static function get_post_path_define_module()
    {
        // Replaces POST_PATH_DEFINE_MODULE
        return "_PATH','";
    }

    /**
     * Core modules are specials:
     * They are installed always and they never can be uninstalled or disabled
     * 
     * @return array
     */
    public static function getCoreModules()
    {
        return self::$core_modules;
    }

    /**
     * Deprecated modules
     * They don't have to be shown on Ximdex CMS interface
     * 
     * @return array
     */
    public static function getDeprecatedModules()
    {
        return self::$deprecated_modules;
    }

    /**
     * Get install params for GUI
     * 
     * @param string $name
     * @return array
     */
    public function getInstallParams(string $name)
    {
        $module = $this->instanceModule($name);
        if (is_null($module)) {
            return array();
        }
        return $module->getInstallParams();
    }

    public function writeStates()
    {
        $modules = self::getModules();
        $str = "<?php\n\n";
        foreach ($modules as $mod) {
            $str .= Manager::get_pre_define_module() . strtoupper($mod["name"]) . Manager::get_post_path_define_module() 
                . str_replace(XIMDEX_ROOT_PATH, '', $mod["path"]) . "');" . "\n";
        }
        $str .= "\n?>";
        FsUtils::file_put_contents(XIMDEX_ROOT_PATH. Manager::get_modules_install_params(), $str);
    }

    public function __construct(string $caller = null)
    {
        // Init stuff
        $this->caller = $caller;
    }

    public static function parseModules(string $constModule, array & $modules)
    {
        $paths = FsUtils::readFolder($constModule, false);
        if ($paths) {
            foreach ($paths as $moduleName) {
                $modulePath = $constModule . $moduleName;
                if (! in_array($moduleName, self::getDeprecatedModules())) {
                    if (is_dir($modulePath)  ) {
                        $i = count($modules);
                        $modules[$i]["name"] = $moduleName;
                        $modules[$i]["path"] = $modulePath;
                        $modules[$i]["enable"] = (int) self::isEnabled($moduleName);
                    }
                }
            }
        }
    }

    public static function getModules()
    {
        $modules = array();
        self::parseModules(self::get_modules_dir(), $modules);
        self::parseModules(self::get_modules_pro_dir(), $modules);
        return $modules;
    }

    public static function moduleExists(string $name)
    {
        $path = Manager::path($name);
        if (! empty($path)) {
            return true;
        }
        return false;
    }

    public static function path(string $name)
    {
        $str = "MODULE_" . strtoupper($name) . "_PATH";
        if (defined($str)) {
            return constant($str);
        } else {
            return "";
        }
    }

    public function installModule(string $name)
    {
        if ($metaParent = self::hasMetaParent($name)) {
            self::$msg = sprintf("Can't install module %s directly. Try installing Meta-module %s instead", $name, $metaParent[$name]);
            return false;
        }
        if (Manager::isEnabled($name)) {
            self::$msg = "checkModule: MODULE_STATE_ENABLED, module is enabled... try to reinstall ";
            
            /* BUG? it returns true but as installation failed should return false*/
            return Manager::get_module_state_installed();
        }
        $module = $this->instanceModule($name);
        if (is_null($module)) {
            print(" * ERROR: Can't install module $name\n");
            return false;
        }
        return $module->install();
    }

    public function uninstallModule(string $name)
    {
        if ($metaParent = self::hasMetaParent($name)) {
            self::$msg = sprintf("Can't uninstall module %s directly. Try uninstalling Meta-module %s instead", $name, $metaParent[$name]);
            return false;
        }
        $module = $this->instanceModule($name);
        if (is_null($module) || $module->isCoreModule()) {
            self::$msg = "Can't uninstall module $name";

            return false;
        }
        $module->uninstall();
    }

    public function checkModule(string $name)
    {
        $module = $this->instanceModule($name);
        if (is_null($module)) {
            self::$msg = "Module instance down";
            return Manager::get_module_state_error();
        }
        return $module->state();
    }

    /**
     * Enable a Module
     * 
     * @param string $name
     * @return boolean
     */
    public function enableModule(string $name)
    {
        if ($metaParent = self::hasMetaParent($name)) {
            self::$msg = sprintf("Can't enable module %s directly. Try enabling Meta-module %s instead", $name, $metaParent[$name]);
            return false;
        }
        $module = $this->instanceModule($name);
        if (is_null($module)) {
            self::$msg = " * ERROR: instance module down";
            return false;
        }
        $modConfig = new Config();
        $modConfig->enableModule($module->getModuleName());
        $module->enable();
        return true;
    }

    /**
     * Disable a Module
     * 
     * @param string $name
     * @return boolean
     */
    public function disableModule(string $name)
    {
        if ($metaParent = self::hasMetaParent($name)) {
            self::$msg = sprintf("Can't disable module %s directly. Try disabling Meta-module %s instead", $name, $metaParent[$name]);
            return false;
        }
        $module = $this->instanceModule($name);
        if (is_null($module) || $module->isCoreModule()) {
            self::$msg = "instance module down";
            return false;
        }
        $modConfig = new Config();
        $modConfig->disableModule($module->getModuleName());
        $module->disable();
    }

    /**
     * Instantiate a module by name
     * 
     * @param string $name
     * @return \Ximdex\Modules\Module
     */
    public function instanceModule(string $name)
    {
        // If no name provided exit.
        if (is_null($name)) {
            self::$msg = "Module name not provided.";
            return NULL;
        }
        $className = "\\Ximdex\\Modules\\" . $name . "\\Manager";
        if (class_exists($className)) {
            return new $className;
        }
        
        // If module not exists exit.
        $moduleClassName = Manager::get_module_prefix() . $name;
        $moduleClassFile = Manager::get_module_prefix() . $name . ".class.php";
        $moduleClassPath = XIMDEX_ROOT_PATH. self::path($name) . "/" . $moduleClassFile;
        if (file_exists($moduleClassPath)) {
            include_once($moduleClassPath);
        } else {
            self::$msg = "Module definition file not found [$moduleClassPath].";
            return NULL;
        }
        $module = new $moduleClassName;
        if (is_null($module)) {
            self::$msg = " Module not instantiated [$moduleClassName].";
            return NULL;
        }
        return $module;
    }

    public static function isEnabled(string $name)
    {
        $str = "MODULE_" . strtoupper($name) . "_ENABLED";
        if (App::getValue($str)) {
            return true;
        }
        return false;
    }

    public static function getEnabledModules()
    {
        $modules = self::getModules();
        foreach ($modules as $key => $module) {
            if (! self::isEnabled($module['name'])) {
                unset($modules[$key]);
            }
        }
        return $modules;
    }

    public static function component(string $_file, string $_component = 'XIMDEX')
    {
        if ("XIMDEX" == $_component) {
            $dir = '';
        } else {
            $dir = self::path($_component);
        }
        self::file($dir . $_file);
    }

    public static function file(string $_file, string $_module = 'APP')
    {
        if ("XIMDEX" == $_module) {
            $path = XIMDEX_ROOT_PATH;
        } elseif ("APP" == $_module) {
            $path = APP_ROOT_PATH;
        } else {
            $dir = self::path($_module);
            $path = XIMDEX_ROOT_PATH . $dir;
        }
        if (file_exists("{$path}{$_file}")){
            if (('XIMDEX' == $_module || 'APP' == $_module || self::isEnabled($_module))) {
                return require_once("{$path}{$_file}");
            } else {
                Logger::warning('Module ' . $_module . ' is not enabled');
            }
        } else {
            Logger::error('Could not load the file: ' . $path . $_file);
        }
    }
    
    private function getMetaParent()
    {
        $metaParent = null;
        self::parseMetaParent(self::get_modules_dir(), $metaParent);
        self::parseMetaParent(self::get_modules_pro_dir(), $metaParent);
        return $metaParent;
    }
    
    private function hasMetaParent(string $name)
    {
        $metaParent = self::getMetaParent();
        if (! empty($metaParent) && in_array($name, array_keys($metaParent)) && $this->caller != $metaParent[$name]) {
            return $metaParent;
        }
        return false;
    }
    
    private function parseMetaParent(string $constModule, array & $metaParent = null)
    {
        $paths = FsUtils::readFolder($constModule, false);
        if ($paths) {
            foreach ($paths as $moduleName) {
                $modulePath = $constModule . $moduleName;
                if (is_dir($modulePath) && file_exists($modulePath . "/conf.ini")) {
                    $conf = parse_ini_file($modulePath . "/conf.ini");
                    foreach ($conf['module'] as $childrenModName)
                        $metaParent[$childrenModName] = $moduleName;
                }
            }
        }
    }
}
