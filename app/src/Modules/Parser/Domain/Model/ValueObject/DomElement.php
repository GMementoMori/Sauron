<?php
namespace App\Modules\Parser\Domain\Model\ValueObject;

interface DomElement
{
    public function getChildElements(): array;

    public function getParentElement(): object;

    public function getTagName(): string;

    public function getAttributes(): array;

    public function getContent(): string;
}