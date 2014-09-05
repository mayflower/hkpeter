<?php

namespace elseym\HKPeterBundle\Service;

/**
 * Class WordService
 * @package elseym\HKPeterBundle\Service
 */
class WordService
{
    /** @var array $words */
    private $inWords = [
        'a',
        'ac',
        'accumsan',
        'adipiscing',
        'aenean',
        'aliquam',
        'aliquet',
        'amet',
        'ante',
        'arcu',
        'at',
        'auctor',
        'augue',
        'blandit',
        'condimentum',
        'congue',
        'consectetur',
        'consequat',
        'convallis',
        'cras',
        'curabitur',
        'cursus',
        'dapibus',
        'dictum',
        'dignissim',
        'dolor',
        'donec',
        'dui',
        'duis',
        'efficitur',
        'egestas',
        'eget',
        'eleifend',
        'elementum',
        'elit',
        'enim',
        'erat',
        'eros',
        'est',
        'et',
        'etiam',
        'eu',
        'euismod',
        'ex',
        'faucibus',
        'fermentum',
        'feugiat',
        'finibus',
        'fringilla',
        'fusce',
        'gravida',
        'iaculis',
        'id',
        'imperdiet',
        'in',
        'integer',
        'ipsum',
        'justo',
        'lacinia',
        'lacus',
        'lectus',
        'leo',
        'ligula',
        'lorem',
        'maecenas',
        'magna',
        'malesuada',
        'mauris',
        'metus',
        'mi',
        'molestie',
        'mollis',
        'morbi',
        'nec',
        'neque',
        'nibh',
        'nisl',
        'non',
        'nulla',
        'nullam',
        'nunc',
        'odio',
        'orci',
        'ornare',
        'pellentesque',
        'pharetra',
        'phasellus',
        'porta',
        'porttitor',
        'posuere',
        'praesent',
        'proin',
        'pulvinar',
        'purus',
        'quam',
        'quis',
        'quisque',
        'rutrum',
        'sapien',
        'sed',
        'sem',
        'semper',
        'sit',
        'sodales',
        'sollicitudin',
        'suscipit',
        'suspendisse',
        'tempor',
        'tempus',
        'tincidunt',
        'tortor',
        'tristique',
        'turpis',
        'ullamcorper',
        'ultricies',
        'urna',
        'ut',
        'varius',
        'vehicula',
        'vel',
        'velit',
        'venenatis',
        'vestibulum',
        'vitae',
        'vivamus',
        'viverra',
        'volutpat',
        'vulputate'
    ];

    /** @var array $words */
    private $words = null;

    /**
     * @param int $count
     * @return array
     */
    public function get($count = 1)
    {
        if (null === $this->words) {
            $this->words = array_combine($this->inWords, $this->inWords);
        }

        return array_rand($this->words, $count);
    }
}
