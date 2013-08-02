<?php


class Counts extends \Phalcon\Mvc\Model 
{

    /**
     * @var integer
     *
     */
    public $id;

    /**
     * @var integer
     *
     */
    public $links_id;

    /**
     * @var integer
     *
     */
    public $value;

    /**
     * @var string
     *
     */
    public $visit_date;

    /**
     * @var string
     *
     */
    public $visitor_ip;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->belongsTo("links_id", "Links", "id");
    }

}
