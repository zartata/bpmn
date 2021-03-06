<?php

/*
 * This file is part of KoolKode BPMN.
*
* (c) Martin Schröder <m.schroeder2007@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace KoolKode\BPMN\Runtime\Behavior;

use KoolKode\BPMN\Engine\AbstractActivity;
use KoolKode\BPMN\Engine\VirtualExecution;
use KoolKode\BPMN\Runtime\Command\CreateMessageSubscriptionCommand;
use KoolKode\Process\Node;

/**
 * Similar to basic start event, message subscriptions are handled by repository services.
 * 
 * @author Martin Schröder
 */
class MessageStartEventBehavior extends AbstractActivity implements StartEventBehaviorInterface
{
	protected $message;
	
	protected $subProcessStart;
	
	protected $interrupting = true;
	
	public function __construct($message, $subProcessStart = false)
	{
		$this->message = (string)$message;
		$this->subProcessStart = $subProcessStart ? true : false;
	}
	
	public function getMessageName()
	{
		return $this->message;
	}
	
	public function isSubProcessStart()
	{
		return $this->subProcessStart;
	}
	
	public function isInterrupting()
	{
		return $this->interrupting;
	}
	
	public function setInterrupting($interrupting)
	{
		$this->interrupting = $interrupting ? true : false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function processSignal(VirtualExecution $execution, $signal, array $variables = [], array $delegation = [])
	{
		if($signal !== $this->message)
		{
			throw new \RuntimeException(sprintf('Start event awaits message "%s", unable to process signal "%s"', $this->message, $signal));
		}
		
		$this->passVariablesToExecution($execution, $variables);
		
		$this->leave($execution);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function createEventSubscriptions(VirtualExecution $execution, $activityId, Node $node = NULL)
	{
		$execution->getEngine()->executeCommand(new CreateMessageSubscriptionCommand(
			$this->message,
			$execution,
			$activityId,
			($node === NULL) ? $execution->getNode() : $node
		));
	}
}
