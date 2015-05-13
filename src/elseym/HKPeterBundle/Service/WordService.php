<?php

namespace elseym\HKPeterBundle\Service;

/**
 * Class WordService
 * @package elseym\HKPeterBundle\Service
 */
class WordService
{
    /** @var array $lipsum */
    public static $lipsum = [
        'a', 'ac', 'accumsan', 'adipiscing', 'aenean', 'aliquam', 'aliquet', 'amet', 'ante', 'arcu', 'at', 'auctor', 'augue',
        'blandit',
        'condimentum', 'congue', 'consectetur', 'consequat', 'convallis', 'cras', 'curabitur', 'cursus',
        'dapibus', 'dictum', 'dignissim', 'dolor', 'donec', 'dui', 'duis',
        'efficitur', 'egestas', 'eget', 'eleifend', 'elementum', 'elit', 'enim', 'erat', 'eros', 'est', 'et', 'etiam', 'eu', 'euismod', 'ex',
        'faucibus', 'fermentum', 'feugiat', 'finibus', 'fringilla', 'fusce',
        'gravida',
        'iaculis', 'id', 'imperdiet', 'in', 'integer', 'ipsum',
        'justo',
        'lacinia', 'lacus', 'lectus', 'leo', 'ligula', 'lorem',
        'maecenas', 'magna', 'malesuada', 'mauris', 'metus', 'mi', 'molestie', 'mollis', 'morbi',
        'nec', 'neque', 'nibh', 'nisl', 'non', 'nulla', 'nullam', 'nunc',
        'odio', 'orci', 'ornare',
        'pellentesque', 'pharetra', 'phasellus', 'porta', 'porttitor', 'posuere', 'praesent', 'proin', 'pulvinar', 'purus',
        'quam', 'quis', 'quisque',
        'rutrum',
        'sapien', 'sed', 'sem', 'semper', 'sit', 'sodales', 'sollicitudin', 'suscipit', 'suspendisse',
        'tempor', 'tempus', 'tincidunt', 'tortor', 'tristique', 'turpis',
        'ullamcorper', 'ultricies', 'urna', 'ut',
        'varius', 'vehicula', 'vel', 'velit', 'venenatis', 'vestibulum', 'vitae', 'vivamus', 'viverra', 'volutpat', 'vulputate'
    ];

    /** @var array $words */
    private $words = [];

    /**
     * @param array $words
     * @param bool $includeLoremIpsum
     */
    function __construct($words = [], $includeLoremIpsum = true)
    {
        if ($includeLoremIpsum) {
            $words = array_merge($words, self::$lipsum);
        }

        $this->words = array_combine($words, $words);
    }

    /**
     * @param int $count
     * @return array
     */
    public function get($count = 1)
    {
        return array_rand($this->words, $count);
    }
}
