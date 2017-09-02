<?php
namespace App\Entities;

/**
 * @Entity @Table(name="websites")
 **/
class Website
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
    protected $url;

    /**
     * @Column(name="url_hash", type="string")
     * @var string
     */
    protected $urlHash;

    /**
     * @OneToMany(targetEntity="Endpoint", mappedBy="website")
     * @JoinColumn(name="id", referencedColumnName="endpoint_id")
     * @var Endpoint[]
     **/
    protected $endpoints;

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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlHash()
    {
        return $this->urlHash;
    }

    /**
     * @param string $urlHash
     * @return $this
     */
    public function setUrlHash(string $urlHash)
    {
        $this->urlHash = $urlHash;
        return $this;

    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints()
    {
        return $this->endpoints;
    }
}