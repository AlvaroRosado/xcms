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

use Ximdex\Logger;
use Ximdex\Sync\Scheduler;

// Check if the installation have been finished in order to run the process
Ximdex\Modules\Manager::file('/install/InstallController.class.php');
if (! InstallController::isInstalled()) {
    Logger::warning('Cannot run the scheduler process until Ximdex core have been installed');
    exit();
}

/*
 This global variable will indicate to database connections that we are in a batch process and do the reconnect
 method when it's necessary
 */
$GLOBALS['InBatchProcess'] = true;
Logger::generate('SCHEDULER', 'scheduler');
Logger::setActiveLog('scheduler');
Scheduler::start();