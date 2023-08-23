<?php

namespace App\Modules\Parser\Application\Facades;

use App\Modules\Parser\Application\DTO\ItemDTO;
use App\Modules\Parser\Infrastructure\Service\ParserService;
use DOMElement;

class OptionsParseFacade
{
    private array $settings;
    private ParserService $parserService;

    public function __construct(array $settings)
    {
        $this->parserService = new ParserService();
        $this->parserService->setUrl($settings['url']);
        $this->settings = $settings;
    }

    public function getElements(): array
    {
        $listObjects = $this->parserService->getListElements($this->settings['cardsXpath']);

        $arrayItems = [];
        foreach ($listObjects as $element) {
            $arrayItems[] = $this->getInfoElementBySettings($element);
        }

        return $arrayItems;
    }

    public function getInfoElementBySettings(DOMElement $element): ItemDTO
    {
        $itemDTO = new ItemDTO();
        foreach ($this->settings['mainIfo'] as $key => $setting) {
            $itemValue = $this->loadInfoKeyElement($element, $setting);
            $itemDTO->{$key} = $itemValue;
        }

        return $itemDTO;
    }

    public function loadInfoKeyElement(DOMElement $element, array $setting): string
    {
        $resultElement = $this->parserService->findElementRecursion($element, $setting['tagName'], $setting['tagInfo']);

        if ($setting['return']['type'] === 'attribute') {
            $resultInfo = $resultElement->getAttribute($setting['return']['name']);
        } else {
            $resultInfo = $resultElement->textContent;
        }

        if (!empty($setting['replace'])) {
            $resultInfo = preg_replace($setting['replace']['pattern'], $setting['replace']['replacement'], $resultInfo);
        }

        return $resultInfo;
    }

    public function getFirstItem(): ItemDTO
    {
        $listObjects = $this->parserService->getListElements($this->settings['cardsXpath']);
        return $this->getInfoElementBySettings($listObjects[0]);
    }
}
