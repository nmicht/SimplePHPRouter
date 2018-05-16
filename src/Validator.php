<?php
namespace SimplePHPRouter;

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
