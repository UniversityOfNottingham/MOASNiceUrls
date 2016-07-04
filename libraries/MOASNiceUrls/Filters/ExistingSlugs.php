<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Filters_ExistingSlugs
{
    private $slugs;

    public function __construct(array $existingSlugs)
    {
        $this->slugs = $existingSlugs;
    }

    public function filter($value)
    {
        foreach ($this->slugs as $slug) {
            if ($slug['slug'] == $value['text']) {
                return false;
            }
        }
        return true;
    }
}