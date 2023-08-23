<?php
namespace App\Modules\Parser\Infrastructure\Model\Collection;

use App\Modules\Parser\Infrastructure\Model\ValueObject\DomElement;

class DomElementsCollection
{
    private array $elements = [];

    public function add(DomElement $element): void
    {
        $this->elements[] = $element;
    }

    public function remove(DomElement $element): void
    {
        $index = array_search($element, $this->elements, true);
        if ($index !== false) {
            unset($this->elements[$index]);
        }
    }

    public function find(string $tagName): array
    {
        return array_filter($this->elements, function (DomElement $element) use ($tagName) {
            return $element->getTagName() === $tagName;
        });
    }

    public function getAll(): array
    {
        return $this->elements;
    }
}
