<?php
namespace App\Modules\Parser\Infrastructure\Model\ValueObject;

use App\Modules\Parser\Infrastructure\Model\Entity\Item;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "attributes")]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $value;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: "attribute")]
    #[ORM\JoinColumn(name: "item_id", referencedColumnName: "id")]
    private Item $item;

    public function __construct(string $name, string $value, Item $item)
    {
        $this->name = $name;
        $this->value = $value;
        $this->item = $item;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getItem(): Item
    {
        return $this->item;
    }
}
