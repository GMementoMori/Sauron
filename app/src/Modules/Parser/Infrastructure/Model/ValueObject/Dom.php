<?php
namespace App\Modules\Parser\Infrastructure\Model\ValueObject;

use App\Modules\Parser\Domain\Model\ValueObject\Dom as DomInterface;
use PhpQuery\PhpQuery;

class Dom implements DomInterface
{
    private object $dom;

    public function __construct()
    {
        $this->dom = new PhpQuery();
    }

    public function setDom(string $html): void
    {
        $this->dom->load_str($html);
    }

    public function getDom(): object
    {
        return $this->dom;
    }
}