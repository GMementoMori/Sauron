<?php
namespace App\Modules\Parser\Domain\Model\Entity;

interface Page
{
    public function getContent(): string;
}
