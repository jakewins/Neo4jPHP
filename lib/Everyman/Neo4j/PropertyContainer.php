<?php
namespace Everyman\Neo4j;

/**
 * Represents an entity that is a collection of properties
 */
abstract class PropertyContainer
{
	protected $id = null;
	protected $client = null;
	protected $properties = null;

	protected $lazyLoad = true;

	/**
	 * Build the container and set its client
	 *
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Delete this entity
	 *
	 * @return boolean
	 */
	abstract public function delete();

	/**
	 * Load this entity
	 *
	 * @return boolean
	 */
	abstract public function load();

	/**
	 * Save this entity
	 *
	 * @return boolean
	 */
	abstract public function save();

	/**
	 * Get the entity's client
	 *
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Get the entity's id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Return all properties
	 *
	 * @return array
	 */
	public function getProperties()
	{
		$this->loadProperties();
		return $this->properties;
	}

	/**
	 * Return the named property
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function getProperty($property)
	{
		$this->loadProperties();
		return (isset($this->properties[$property])) ? $this->properties[$property] : null;
	}

	/**
	 * Is this entity identified?
	 *
	 * @return boolean
	 */
	public function hasId()
	{
		return $this->getId() !== null;
	}

	/**
	 * Remove a property set on the entity
	 *
	 * @param string $property
	 * @return PropertyContainer
	 */
	public function removeProperty($property)
	{
		$this->loadProperties();
		unset($this->properties[$property]);
		return $this;
	}

	/**
	 * Set the entity's id
	 *
	 * @param integer $id
	 * @return PropertyContainer
	 */
	public function setId($id)
	{
		$this->id = (int)$id;
		return $this;
	}

	/**
	 * Set multiple properties on the entity
	 *
	 * @param array $properties
	 * @return PropertyContainer
	 */
	public function setProperties($properties)
	{
		$this->loadProperties();
		foreach ($properties as $property => $value) {
			$this->setProperty($property, $value);
		}
		return $this;
	}

	/**
	 * Set a property on the entity
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return PropertyContainer
	 */
	public function setProperty($property, $value)
	{
		$this->loadProperties();
		$this->properties[$property] = $value;
		return $this;
	}

	/**
	 * Should this entity be lazy-loaded if necessary?
	 *
	 * @param boolean $lazyLoad
	 * @return PropertyContainer
	 */
	public function useLazyLoad($lazyLoad)
	{
		$this->lazyLoad = (bool)$lazyLoad;
		return $this;
	}

	/**
	 * Set up the properties array the first time we need it
	 * This includes loading the properties from the server
	 * if we can get them.
	 */
	protected function loadProperties()
	{
		if ($this->properties === null) {
			$this->properties = array();
			if ($this->hasId() && $this->lazyLoad) {
				$this->load();
			}
		}
	}
}
