<?php
/**
 * Simple PHP Router - A real simple PHP Router for PHP 7.1+
 *
 * @author      Michelle Torres <hola@michelletorres.mx>
 * @link        https://github.com/nmicht/SimplePHPRouter
 * @license     MIT
 */

namespace SimplePHPRouter;

/**
 * Validator
 *
 * Class to keep the regex rules for route validation.
 */
class Validator
{
    /**
     * The regular expression used to compile and match URL's
     * @var string
     */
    const ROUTE_COMPILE_REGEX = '`(\\\?(?:/|\.|))(?:\[([^:\]]*)(?::([^:\]]*))?\])(\?|)`';

    /**
     * The regular expression used to escape the non-named param section of a route URL
     * @var string
     */
    const ROUTE_ESCAPE_REGEX = '`(?<=^|\])[^\]\[\?]+?(?=\[|$)`';

    /**
     * The regex for match types on routes.
     * @var array
     */
    const MATCH_TYPES = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        's'  => '[0-9A-Za-z-_\-]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/\.]++'
    ];
}
