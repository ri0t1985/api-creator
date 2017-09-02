<?php

namespace App\Entities;

/**
 * @Entity @Table(name="selectors")
 **/
class Selector
{
    const TYPE_CSS = 'CSS';
    const TYPE_REGEX = 'REGEX';
    const TYPE_XPATH = 'XPATH';

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
     * @Column(type="string", columnDefinition="ENUM('CSS', 'XPATH', 'REGEX')")
     * @var string
     */
    protected $type;

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
     * @OneToMany(targetEntity="SelectorOption", mappedBy="selector")
     * @JoinColumn(name="selector_id", referencedColumnName="id")
     * @var SelectorOption[]
     **/
    protected $options;

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
    public function getSelector()
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        if (!in_array($type,[self::TYPE_CSS, self::TYPE_REGEX, self::TYPE_XPATH]))
        {
            throw new \InvalidArgumentException('The given type is not supported. Expected: '
                . implode([self::TYPE_CSS, self::TYPE_REGEX, self::TYPE_XPATH]). '. Received: '.$type);
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return SelectorOption[]
     */
    public function getOptions()
    {
        return $this->options;
    }

}