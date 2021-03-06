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

set_time_limit(0);

use Ximdex\Logger;
use Ximdex\Models\Node;
use Ximdex\Models\NodesToPublish;
use Ximdex\Models\Workflow;
use Ximdex\Runtime\App;
use Ximdex\Sync\SynchroFacade;
use Ximdex\Sync\BatchManager;

global $argc, $argv;
main($argc, $argv);

function main(int $argc = 0, array $argv = null)
{
    Logger::generate('PUBLICATION', 'publication');
    Logger::setActiveLog('publication');

    // Command line mode call
    if ($argv != null && isset($argv[1]) && is_numeric($argv[1])) {
        Logger::logTrace('IdNode passed: ' . $argv[1]);
        
        // Add node to publishing pool and exit (SyncManager will call this daemon again when inserting node job is done)
        $syncFac = new SynchroFacade();
		$syncFac->pushDocInPublishingPool($argv[1], time(), null);
        exit(1);
    }

    // One block of nodes to publish, sorted and grouped by dateup
    // Every node in the block shares same dateup
    $nodesToPublish = NodesToPublish::getNext();
    while ($nodesToPublish != null) {
        Logger::info('Publication cycle triggered by ' . $nodesToPublish['idNodeGenerator']);
        createBatchsForBlock($nodesToPublish);

        // Gext next block (if any) of nodes to publish
        $nodesToPublish = NodesToPublish::getNext();
    }
}

function createBatchsForBlock(array $nodesToPublish)
{
    $idNodeGenerator = $nodesToPublish['idNodeGenerator'];
    
    // If the node which trigger publication do not exists anymore return null and cancel.
    $node = new Node($idNodeGenerator);
    if (! $node->get('IdNode')) {
        Logger::error('Required node does not exist ' . $idNodeGenerator);
        return;
    }

    // Get list of physicalServers related to generator node.
    $idServer = $node->getServer();
    $nodeServer = new Node($idServer);
    if (App::getValue('PublishOnDisabledServers') == 1) {
        Logger::info('PublishOnDisabledServers is true');
        $physicalServers = $nodeServer->class->getPhysicalServerList(true);
    } else {
        $physicalServers = $nodeServer->class->getPhysicalServerList(true, true);
    }
    if (count($physicalServers) == 0) {
        Logger::error('Physical server does not exist for nodeId: ' . $idNodeGenerator . ' ... returning empty arrays.');
        return;
    }

    // BatchManager 'publicate' method does all the creating batchs job
    $batchMng = new BatchManager();
    $docsPublicated = $batchMng->publicate(
        $nodesToPublish['idNodeGenerator'],
        $nodesToPublish['docsToPublish'],
        $nodesToPublish['docsToPublishVersion'],
        $nodesToPublish['docsToPublishSubVersion'],
        $nodesToPublish['dateUp'],
        $nodesToPublish['dateDown'],
        $physicalServers,
        $nodesToPublish['forcePublication'],
        $nodesToPublish['userId'],
        $nodesToPublish['noCache']
    );

    // Clean up caches, tmp files, etc...
    if (is_null($docsPublicated)) {
        Logger::error('PUSHDOCINPOOL - docsPublicated null');
        return;
    }

    // Back node to initial state
    $node = new Node($idNodeGenerator);
    if ($node->get('IdState') > 0) {
        $workflow = new Workflow($node->nodeType->getWorkflow());
        $firstState = $workflow->getInitialState();
        $node->SetState($firstState);
    }
}
