<?php

namespace App\Entities;

/**
 * @Entity @Table(name="selector_options")
 **/
class SelectorOption
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
     * @ManyToOne(targetEntity="Selector", inversedBy="options")
     * @JoinColumn(name="selector_id", referencedColumnName="id")
     *
     * @var Selector
     **/
    protected $selector;


    /**
     * @Column(name="key", type="string")
     * @var string
     */
    protected $key;

    /**
     * @Column(name="value", type="string")
     * @var string
     */
    protected $value;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param mixed $selector
     * @return SelectorOption
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * @param string $key
     * @return SelectorOption
     */
    public function setKey(string $key): SelectorOption
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $value
     * @return SelectorOption
     */
    public function setValue(string $value): SelectorOption
    {
        $this->value = $value;
        return $this;
    }


}
