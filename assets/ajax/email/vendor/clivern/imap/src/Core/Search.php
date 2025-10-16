<?php
/**
 * @author clivern <hello@clivern.com>
 */

namespace Clivern\Imap\Core;

use Clivern\Imap\Core\Search\Contract\Condition;

/**
 * Search Class
 *
 * @package Clivern\Imap\Core
 */
class Search
{

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * Add Condition
     *
     * @param Condition $condition
     * @return Search
     */
    public function addCondition(Condition $condition)
    {
        $this->conditions[] = (string) $condition;

        return $this;
    }

    /**
     * Get Conditions
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Get Conditions Query
     *
     * @return string
     */
    public function __toString()
    {
        return (!empty($this->conditions)) ? implode(" ", $this->conditions) : "";
    }
}