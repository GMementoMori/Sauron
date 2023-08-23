<?php

namespace App\Modules\Parser\Application\Controller;

use App\Modules\Parser\Application\Facades\OptionsParseFacade;
use App\Modules\Parser\Infrastructure\Persistence\Doctrine\ItemRepository;
use App\Modules\Parser\Infrastructure\Persistence\Redis\RedisService;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/parser', name: 'parser')]
    public function index(ItemRepository $itemRepository, RedisService $redisService, LoggerInterface $logger): JsonResponse
    {
        try {
            $parsers = $this->params->get('parsers');
            foreach ($parsers as $nameParser => $parserSettings) {
                $optionsParseFacade = new OptionsParseFacade($parserSettings);
                $items = $optionsParseFacade->getElements();
                if (!$this->isShowedFirstItem($redisService, $items[0]->link, $nameParser)) {
                    $itemRepository->addItems($items, $nameParser);
                }
            }

            return new JsonResponse(['success' => true]);
        } catch (Exception $exception) {
            $logger->error(
                'ParsingError',
                [
                    'parser_name' => &$nameParser,
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'stack_trace' => $exception->getTraceAsString(),
                ]
            );
            return new JsonResponse(['success' => false]);
        }
    }

    private function isShowedFirstItem(RedisService $redisService, string $firstItemLink, string $parserName): bool
    {
        $lastItem = $redisService->get($redisService->getKeyCache($parserName));
        if ($lastItem === $firstItemLink) {
            return true;
        } else {
            $redisService->set($redisService->getKeyCache($parserName), $firstItemLink);
            return false;
        }
    }
}
