<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Helpers_Slugs
{

    /**
     * Fetch all the nice url slugs that exist along with their id
     *
     * @return array
     */
    public static function fetchSlugs()
    {
        $db = get_db();
        $elementID = static::getSlugElementID();
        $slugs = [];

        foreach ($db->getTable('ElementText')->findByElement($elementID) as $row) {
            $slugs[] = array('slug' => $row->text, 'id' => $row->id);
        }

        return $slugs;
    }

    /**
     * Check the given slug exists
     *
     * @param string $slug the slug to check
     * @return bool does this slug exist
     */
    public static function checkSlugExists($slug)
    {
        $elementID = static::getSlugElementID();
        $db = get_db();

        $elements = $db->getTable('ElementText')->findBy(array(
            'element_id' => $elementID,
            'text' => $slug
        ));

        return !empty($elements);
    }

    /**
     * Create a url slug based of the given string
     *
     * Strips punctuation and spaces, camel cases and truncates to a max of 40 characters
     *
     * @param string $string String to turn into a slug
     * @return string string The created slug
     */
    public static function slugify($string, $length = 40)
    {
        $slug = MOASNiceUrls_Helpers_String::stripPunctuation($string);
        $slug = ucwords($slug);
        return MOASNiceUrls_Helpers_String::truncateWords($slug, $length);
    }

    /**
     * Get the element id for the one element that stores slugs
     *
     * @return mixed
     */
    public static function getSlugElementID()
    {
        $db = get_db();
        return  $db->getTable('MOASNiceUrlsElement')->getSlugElement()->id;
    }

    /**
     * Get all the slug entries for the given record
     *
     * @param int $id The id of the record
     * @param string $type record type, either 'Item' or 'Collection'
     * @return array
     */
    public static function getRecordsSlugs($id, $type)
    {
        $elementID = static::getSlugElementID();
        $db = get_db();
        $slugs = [];

        $elements = $db->getTable('ElementText')->findBy(array(
            'element_id' => $elementID,
            'record_id' => $id,
            'record_type' => $type
        ));

        foreach ($elements as $element) {
            $slugs[] = array('slug' => $element->text, 'id' => $element->id);
        }

        return $slugs;
    }

    /**
     * Validate the given slug
     *
     * @param string $slug the slug to validate
     * @return array
     */
    public static function validate($slug)
    {
        $error = [];
        if (strlen($slug) > 255) {
            $error[] = "Slugs cannot be more than 255 characters long";
        } else if (MOASNiceUrls_Helpers_String::hasPunctuation($slug)) {
            $error[] = "Slugs can only contain letters and numbers.";
        } else if (static::checkSlugExists($slug)) {
            $error[] = 'This slug is already in use, please enter another.';
        }
        return $error;
    }
}