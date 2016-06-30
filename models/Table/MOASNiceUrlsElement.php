<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class Table_MOASNiceUrlsElement extends Table_Element
{
    protected $_name = 'elements';
    protected $_target = 'element';

    const SLUG_ELEMENT_NAME = 'URL Slug';
    const ELEMENT_SET = 'MOAS Elements';

    public function getSlugElement()
    {
        return $this->findByElementSetNameAndElementName(static::ELEMENT_SET, static::SLUG_ELEMENT_NAME);
    }

    public function getSlugs()
    {
        $elementID = $this->getSlugElement()->id;
        $db = $this->getDb();
        $slugs = array();

        $elements = $db->getTable('ElementText')->findBy(array('element_id' => $elementID));

        foreach ($elements as $element) {
            $slugs[] = array('slug' => $element->text, 'id' => $element->id);
        }

        return $slugs;
    }
}