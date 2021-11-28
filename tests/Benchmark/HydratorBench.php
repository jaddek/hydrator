<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests\Benchmark;

use Jaddek\Hydrator\Hydrator;
use Jaddek\Hydrator\Tests\Stub\Author;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;

class HydratorBench
{
    private array $data = [
        'name'    => 'Jeremy Vagg',
        'country' => 'EML',
        'books'   => [
            [
                'name'    => 'Book 1',
                'pages'   => 300,
                'volumes' => [
                    [
                        'name'     => 'Volume 1',
                        'content'  => [
                            'description' => 'test description',
                            'annotation'  => 'test annotation',
                        ],
                        'chapters' => [
                            [
                                'name' => 'Chapter 1'
                            ],
                            [
                                'name' => 'Chapter 2'
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ];

    #[Revs(1000)]
    #[Iterations(10)]
    public function benchHydrate()
    {
        Hydrator::instance($this->data, Author::class);
    }
}