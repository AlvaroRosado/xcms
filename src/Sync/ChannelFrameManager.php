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
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */

namespace Ximdex\Sync;

use Ximdex\Models\Channel;
use Ximdex\Models\ServerFrame;
use Ximdex\Models\ChannelFrame;
use Ximdex\Models\Batch;

/**
* @brief Handles operations with ChannelFrames
*	
* A ChannelFrame stores the relationship between a NodeFrame and the channels in wich the Node will be published
*/
class ChannelFrameManager
{
	/**
	* Calls to ServerFrameManager method ChangeState
	* 
	* @param int serverFrameId
	* @param string operation
	* @param int nodeId
	* @param int canceled
	* @return array
	*/
	public function changeState($serverFrameId, $operation, $nodeId, $canceled)
	{
		$serverFrame = new ServerFrame($serverFrameId);
		$channelFrame = new ChannelFrame($serverFrame->get('IdChannelFrame'));
		$channel = new Channel($channelFrame->get('ChannelId'));
		$renderMode = $channel->get('RenderMode');
		if ($renderMode == 'client'){
			$operation = Batch::TYPE_UP;
		}
		
		// TODO make foreach serverframes
		$serverFrameManager = new ServerFrameManager();
		$result = $serverFrameManager->changeState($serverFrameId, $operation, $nodeId, $canceled);
		return $result;
	}
}
