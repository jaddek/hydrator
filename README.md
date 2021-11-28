## Usage

```php

$data = [
    'id' => 1,
    'title' => 'TEst title'
];

class Book implements \Jaddek\Hydrator\ItemInterface
{
    public function __construct(
        private int $id,
        private string $title
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

