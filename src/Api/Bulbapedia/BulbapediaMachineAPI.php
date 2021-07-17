<?php


namespace App\Api\Bulbapedia;


use App\Api\Bulbapedia\Client\BulbapediaMachineClient;
use App\Satanizer\BulbapediaMachineSatanizer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMachineAPI
{
    private FilesystemAdapter $cache;
    private BulbapediaMachineClient $machineClient;
    private BulbapediaMachineSatanizer $bulbapediaMachineSatanizer;

    public function __construct(BulbapediaMachineClient $machineClient, BulbapediaMachineSatanizer $bulbapediaMachineSatanizer)
    {
        $this->cache = new FilesystemAdapter();

        $this->machineClient = $machineClient;
        $this->bulbapediaMachineSatanizer = $bulbapediaMachineSatanizer;
    }

    public function getMoveNameByItemAndGeneration(string $itemName, $generation)
    {
        $machineInfos = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s',$itemName),
            function (ItemInterface $item) use ($itemName) {
                return $this->machineClient->getMachineInformation($itemName);
            }
        );

        return $this->bulbapediaMachineSatanizer->getMoveNameByItem($machineInfos,$generation);
    }
}