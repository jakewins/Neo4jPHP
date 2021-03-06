<?php
namespace Everyman\Neo4j\Command;
use Everyman\Neo4j\Command,
	Everyman\Neo4j\Client,
	Everyman\Neo4j\Exception,
	Everyman\Neo4j\Node;

/**
 * Get and populate a node
 */
class GetNode extends Command
{
	protected $node = null;

	/**
	 * Set the node to drive the command
	 *
	 * @param Client $client
	 * @param Node $node
	 */
	public function __construct(Client $client, Node $node)
	{
		parent::__construct($client);
		$this->node = $node;
	}

	/**
	 * Return the data to pass
	 *
	 * @return mixed
	 */
	protected function getData()
	{
		return null;
	}

	/**
	 * Return the transport method to call
	 *
	 * @return string
	 */
	protected function getMethod()
	{
		return 'get';
	}

	/**
	 * Return the path to use
	 *
	 * @return string
	 */
	protected function getPath()
	{
		if (!$this->node->hasId()) {
			throw new Exception('No node id specified');
		}
		return '/node/'.$this->node->getId().'/properties';
	}

	/**
	 * Use the results
	 *
	 * @param integer $code
	 * @param array   $headers
	 * @param array   $data
	 * @return integer on failure
	 */
	protected function handleResult($code, $headers, $data)
	{
		if ((int)($code / 100) == 2) {
			$this->node = $this->makeNode($this->node, array('data'=>$data));
			return null;
		}
		return $code;
	}
}

