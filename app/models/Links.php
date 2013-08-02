<?php


class Links extends \Phalcon\Mvc\Model 
{

    /**
     * @var integer
     *
     */
    public $id;

    /**
     * @var string
     *
     */
    public $token;

    /**
     * @var string
     *
     */
    public $longurl;

    /**
     * @var integer
     *
     */
    public $visitor_count;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->hasMany("id", "Counts", "links_id");
    }

}
