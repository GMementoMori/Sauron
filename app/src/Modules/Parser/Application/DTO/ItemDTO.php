<?php
namespace App\Modules\Parser\Application\DTO;

class ItemDTO
{
    public string $title;
    public string $link;
    public array $attributes;

    public function __set(string $name, int|string $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            $this->attributes[$attribute->getName()] = $attribute->getValue();
        }
    }
}
