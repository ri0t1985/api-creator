<?php

namespace App\Entities;

/**
 * @Entity @Table(name="endpoints")
 **/
class Endpoint
{
    /**
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
     * @Column(name="website_id", type="string")
     * @var string
     */
    protected $websiteId;

    /**
     * @OneToMany(targetEntity="Selector", mappedBy="endpoint")
     * @JoinColumn(name="endpoint_id", referencedColumnName="id")
     * @var Selector[]
     **/
    protected $selectors;

    /**
     * @ManyToOne(targetEntity="Website", inversedBy="endpoints")
     * @JoinColumn(name="website_id", referencedColumnName="id")
     * @var Website
     **/
    protected $website;

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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Selector[]
     */
    public function getSelectors()
    {
        return $this->selectors;
    }

    /**
     * @param Selector[] $selectors
     * @return $this
     */
    public function setSelectors(array $selectors)
    {
        $this->selectors = $selectors;
        return $this;
    }

    /**
     * @return Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param Website $website
     * @return $this
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;
        $this->websiteId = $website->getId();
        return $this;
    }
}