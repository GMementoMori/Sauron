<?php
namespace App\Modules\Parser\Infrastructure\Model\Entity;

use App\Modules\Parser\Infrastructure\Model\ValueObject\Attribute;
use App\Modules\Parser\Infrastructure\Persistence\Doctrine\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: "items")]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $link;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $parserName;

    #[ORM\OneToMany(targetEntity: Attribute::class, mappedBy: "item", cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getParserName(): ?string
    {
        return $this->parserName;
    }

    public function setParserName(string $parserName): self
    {
        $this->parserName = $parserName;
        return $this;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(string $name, string $value): self
    {
        $attribute = new Attribute($name, $value, $this);
        $this->attributes->add($attribute);

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }
}
