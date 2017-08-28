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
    protected $endPoints;

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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->urlHash;
    }

    /**
     * @param string $urlHash
     */
    public function setUrlHash(string $urlHash)
    {
        $this->urlHash = $urlHash;
    }

    /**
     * @return Endpoint[]
     */
    public function getEndPoints()
    {
        return $this->endPoints;
    }

    /**
     * @param Endpoint[] $endPoints
     */
    public function setEndPoints(array $endPoints)
    {
        $this->endPoints = $endPoints;
    }
}