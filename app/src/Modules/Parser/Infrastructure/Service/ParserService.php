<?php
namespace App\Modules\Parser\Infrastructure\Service;

use App\Modules\Parser\Infrastructure\Model\Entity\Page;
use App\Modules\Parser\Infrastructure\Model\ValueObject\Dom;
use DOMElement;
use DOMNodeList;

class ParserService
{
    private object $pageObject;
    private object $domObject;
    private string $url;

    public function __construct()
    {
        $this->pageObject = new Page();
        $this->domObject = new Dom();
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function findElement(string $xpath, ?int $numberOfElement = null): ?DOMElement
    {
        $this->pageObject->setContent($this->url);
        $content = $this->pageObject->getContent();

        $this->domObject->setDom($content);
        $dom = $this->domObject->getDom();

        if (empty($dom)) {
            return null;
        }

        if (empty($xpath)) {
            return null;
        }

        if (!is_null($numberOfElement)) {
            return $dom->xpath($xpath)[$numberOfElement];
        }

        return $dom->xpath($xpath);
    }

    public function getListElements(string $xpath): ?DOMNodeList
    {
        $this->pageObject->setContent($this->url);
        $content = $this->pageObject->getContent();

        $this->domObject->setDom($content);
        $dom = $this->domObject->getDom();

        if (empty($dom)) {
            return null;
        }

        if (empty($xpath)) {
            return null;
        }

        return $dom->xpath($xpath);
    }

    public function findElementRecursion(DOMElement $parentElement, string $tagName, array $tagInfo = []): ?DOMElement
    {
        if ($parentElement->tagName === $tagName) {
            if (empty($tagInfo) || $tagInfo['attributeValue'] === $parentElement->getAttribute($tagInfo['attributeName'])) {
                return $parentElement;
            }
        }

        foreach ($parentElement->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $foundElement = $this->findElementRecursion($child, $tagName, $tagInfo);
                if ($foundElement !== null) {
                    return $foundElement;
                }
            }
        }

        return null;
    }
}
