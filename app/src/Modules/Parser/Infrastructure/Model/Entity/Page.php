<?php
namespace App\Modules\Parser\Infrastructure\Model\Entity;

use App\Modules\Parser\Domain\Model\Entity\Page as PageInterface;

class Page implements PageInterface
{
    private string $html;

    public function setContent(string $url): void
    {
        $this->html = file_get_contents($url);
    }

    public function getContent(): string
    {
        return $this->html;
    }
}
