<?php

declare(strict_types=1);

namespace Jaddek\Hydrator\Tests;

use Jaddek\Hydrator\Hydrator;
use Jaddek\Hydrator\Tests\Stub\Author;
use Jaddek\Hydrator\Tests\Stub\Book;
use Jaddek\Hydrator\Tests\Stub\BookCollection;
use Jaddek\Hydrator\Tests\Stub\Chapter;
use Jaddek\Hydrator\Tests\Stub\ChapterCollection;
use Jaddek\Hydrator\Tests\Stub\Content;
use Jaddek\Hydrator\Tests\Stub\Volume;
use Jaddek\Hydrator\Tests\Stub\VolumeCollection;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    public function getData()
    {
        return [
            [
                [
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
                ]
            ]
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testHydration($data)
    {
        /** @var Author $author */
        $author = Hydrator::instance($data, Author::class);

        $this->assertSame($data['name'], $author->getName());
        $this->assertSame($data['country'], $author->getCountry());
        $this->assertInstanceOf(BookCollection::class, $author->getBooks());

        foreach ($data['books'] as $bookIndex => $book) {
            /** @var Book $bookObject */
            $bookObject = $author->getBooks()->offsetGet($bookIndex);
            $this->assertSame($book['name'], $bookObject->getName());
            $this->assertSame($book['pages'], $bookObject->getPages());
            $this->assertInstanceOf(VolumeCollection::class, $bookObject->getVolumes());

            foreach ($book['volumes'] as $volumeIndex => $volume) {
                /** @var Volume $volumeObject */
                $volumeObject = $bookObject->getVolumes()->offsetGet($volumeIndex);

                $this->assertSame($volume['name'], $volumeObject->getName());

                $this->assertInstanceOf(Content::class, $volumeObject->getContent());
                $this->assertSame($volume['content']['description'], $volumeObject->getContent()->getDescription());
                $this->assertSame($volume['content']['annotation'], $volumeObject->getContent()->getAnnotation());

                $this->assertInstanceOf(ChapterCollection::class, $volumeObject->getChapters());

                foreach ($volume['chapters'] as $chapterIndex => $chapter) {
                    /** @var Chapter $chapterObject */
                    $chapterObject = $volumeObject->getChapters()->offsetGet($chapterIndex);

                    $this->assertSame($chapter['name'], $chapterObject->getName());
                }
            }
        }
    }

    /**
     * @dataProvider getData
     */
    public function testSameDataAfterHydration($data)
    {
        /** @var Author $author */
        $author = Hydrator::instance($data, Author::class);

        $this->assertSame(json_encode($data), json_encode($author));
    }
}