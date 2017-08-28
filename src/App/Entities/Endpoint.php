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
     * @OneToMany(targetEntity="Selector", mappedBy="endpoints")
     * @JoinColumn(name="endpoint_id", referencedColumnName="id")
     * @var Selector[]
     **/
    protected $selectors;

    /**
     * @ManyToOne(targetEntity="Website", inversedBy="endpoints")
     * @JoinColumn(name="id", referencedColumnName="website_id")
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     */
    public function setSelectors(array $selectors)
    {
        $this->selectors = $selectors;
    }

    /**
     * @return Website
     */
    public function getWebsite(): Website
    {
        return $this->website;
    }

    /**
     * @param Website $website
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;
    }
}