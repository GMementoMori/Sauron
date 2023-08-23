<?php
namespace App\Modules\Parser\Infrastructure\Model\ValueObject;

use App\Modules\Parser\Domain\Model\ValueObject\DomElement as DomElementInterface;

class DomElement implements DomElementInterface
{
    private object $element;

    public function __construct(object $element)
    {
        $this->element = $element;
    }

    public function getChildElements(): array
    {
        return $this->element->childNodes;
    }

    public function getParentElement(): object
    {
        return $this->element->parentNode;
    }

    public function getTagName(): string
    {
        return $this->element->nodeName;
    }

    public function getAttributes(): array
    {
        return $this->element->attributes;
    }

    public function getContent(): string
    {
        return $this->element->nodeValue;
    }
}
