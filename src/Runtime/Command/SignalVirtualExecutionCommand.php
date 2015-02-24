<?php

/*
 * This file is part of KoolKode BPMN.
*
* (c) Martin Schröder <m.schroeder2007@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace KoolKode\BPMN\Runtime\Command;

use KoolKode\Process\Command\SignalExecutionCommand;
use KoolKode\Util\UUID;

/**
 * Signal a virtual execution directly.
 * 
 * @author Martin Schröder
 */
class SignalVirtualExecutionCommand extends SignalExecutionCommand
{
	/**
	 * Wake the given execution up using the given signal / variables.
	 * 
	 * @param Execution $execution
	 * @param string $signal
	 * @param array $variables
	 * @param array $delegation
	 */
	public function __construct(UUID $executionId, $signal = NULL, array $variables = [], array $delegation = [])
	{
		$this->executionId = $executionId;
		$this->signal = ($signal === NULL) ? NULL : (string)$signal;
		$this->variables = serialize($variables);
		$this->delegation = serialize($delegation);
	}
}
