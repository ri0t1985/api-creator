<?php

namespace App\Entities;

/**
 * @Entity @Table(name="selector_options")
 **/
class SelectorOption
{

    const OPTION_PROPERTY    = 'property';
    const OPTION_STRIP_HTML  = 'strip_html';
    const OPTION_TRIM        = 'trim';
    const OPTION_INFO        = 'info';

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Selector
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
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
