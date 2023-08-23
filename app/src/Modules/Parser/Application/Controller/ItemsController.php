<?php

namespace App\Modules\Parser\Application\Controller;

use App\Modules\Parser\Application\DTO\ItemDTO;
use App\Modules\Parser\Infrastructure\Persistence\Doctrine\ItemRepository;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ItemsController extends AbstractController
{
    #[Route('/items/{parserName}', name: 'items')]
    public function index(ItemRepository $itemRepository, LoggerInterface $logger, string $parserName = null): JsonResponse
    {
        try {
            $resultItems = [];
            $items = $itemRepository->findLimitItemsByParserName($parserName);
            foreach ($items as $item) {
                $itemDTO = new ItemDTO();
                $itemDTO->title = $item->getTitle();
                $itemDTO->link = $item->getLink();
                $itemDTO->setAttributes($item->getAttributes()->toArray());
                $resultItems[] = $itemDTO;
            }
            return new JsonResponse($resultItems);
        } catch (Exception $exception) {
            $logger->error(
                'GettingItemsError',
                [
                    'parser_name' => &$parserName,
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'stack_trace' => $exception->getTraceAsString(),
                ]
            );
            return new JsonResponse([]);
        }
    }
}
