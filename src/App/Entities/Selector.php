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
     * @ManyToOne(targetEntity="Endpoint", inversedBy="selectors")
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
     * @return Endpoint
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    /**
     * @param Endpoint $endPoint
     * @return $this
     */
    public function setEndpoint(Endpoint $endPoint)
    {
        $this->endpoint = $endPoint;
        $this->endpointId = $endPoint->getId();
        return $this;
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
     * @return $this
     */
    public function setSelector(string $selector)
    {
        $this->selector = $selector;
        return $this;
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
     * @return $this
     */
    public function setEndpointId(string $endpointId)
    {
        $this->endpointId = $endpointId;
        return $this;
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
     * @return $this
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
        return $this;
    }
}