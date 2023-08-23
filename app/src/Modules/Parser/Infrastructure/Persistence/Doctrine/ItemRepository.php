<?php

namespace App\Modules\Parser\Infrastructure\Persistence\Doctrine;

use App\Modules\Parser\Infrastructure\Model\Entity\Item;
use App\Modules\Parser\Infrastructure\Model\ValueObject\Attribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param Item $item
     */
    public function save(Item $item): void
    {
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param Item $item
     */
    public function remove(Item $item): void
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function findById(int $id): ?Item
    {
        return $this->find($id);
    }

    /**
     * @return Item[]
     */
    public function findAllItems(): array
    {
        return $this->findAll();
    }

    /**
     * @return Item[]
     */
    public function findLimitItemsByParserName(string $parserName): array
    {
        return $this->findBy(['parserName' => $parserName], null, 10);
    }

    /**
     * @param Item $item
     * @param string $name
     * @param string $value
     * @return Item
     */
    public function addAttributeToItem(Item $item, string $name, string $value): Item
    {
        $attribute = new Attribute($name, $value, $item);
        $item->getAttributes()->add($attribute);

        $this->_em->persist($attribute);
        $this->_em->flush();

        return $item;
    }

    /**
     * @param Item $item
     * @param Attribute $attribute
     * @return Item
     */
    public function removeAttributeFromItem(Item $item, Attribute $attribute): Item
    {
        $item->getAttributes()->removeElement($attribute);

        $this->_em->remove($attribute);
        $this->_em->flush();

        return $item;
    }

    /**
     * @param array $items
     * @return void
     */
    public function addItems(array $items, string $parserName): void
    {
        foreach ($items as $itemDTO) {
            $item = new Item();
            $item->setParserName($parserName);
            $item->setTitle($itemDTO->title);
            $item->setLink($itemDTO->link);

            foreach ($itemDTO->attributes as $nameAttribute => $valueAttribute) {
                $attribute = new Attribute($nameAttribute, $valueAttribute, $item);
                $item->getAttributes()->add($attribute);
            }
            $this->_em->persist($item);
        }

        $this->_em->flush();
    }
}
