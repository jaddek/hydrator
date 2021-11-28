## Usage

```php

$book = [
    'id' => 1,
    'title' => 'Test title'
    'chapters' => [
        [
            'chapter1' => 'Intro',
        ],[
            'chapter2' => 'Final'
        ]
    ]
];

$chapter = []

class Chapter extends \Jaddek\Hydrator\Item 
{
     public function __construct(
        private string $chapter1,
        private string $chapter2,
    )
    {
    }
}

class ChapterCollection extends \Jaddek\Hydrator\Collection
{
        public static function getSupportedItem(): string
    {
        return \Jaddek\Hydrator\Tests\Stub\Chapter::class;
    }

    public static function getItemsKey(): string
    {
        return 'chapters';
    }
}

class Book extends \Jaddek\Hydrator\Item
{
    public function __construct(
        private int $id,
        private string $title,
        private ChaptersCollection $chapters
    )
    {
    }
   
    public  function getId(): int
    {
        return $this->id;
    }
    
    public  function getTitle(): string
    {
        return $this->title;
    }
}

$book = Hydrator::instance($data, Book::class);
```

