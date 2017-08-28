<?php

namespace App\Entities;

/**
 * @Entity @Table(name="selectors")
 **/
class Selector
{
    /**
     *
     * @Id
     * @Column(name="id", type="guid")
     * @GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

   /**
     * @Column(type="string")
     * @var string
     */
    protected $selector;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $alias;

    /**
     * @Column(name="endpoint_id", type="string")
     * @var string
     */
    protected $endpointId;

    /**
     * @OneToMany(targetEntity="Endpoint", mappedBy="selectors")
     * @JoinColumn(name="endpoint_id", referencedColumnName="id")
     *
     * @var Endpoint
     **/
    protected $endpoint;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Endpoint
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    /**
     * @param Endpoint $endPoint
     */
    public function setEndpoints(Endpoint $endPoint)
    {
        $this->endpoint = $endPoint;
    }

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector(string $selector)
    {
        $this->selector = $selector;
    }

    /**
     * @return string
     */
    public function getEndpointId()
    {
        return $this->endpointId;
    }

    /**
     * @param string $endpointId
     */
    public function setEndpointId(string $endpointId)
    {
        $this->endpointId = $endpointId;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }
}