<?php

namespace App\Command\Fake\Bill;

use App\Entity\Bill\BiItem;
use App\Service\Data\Bill\DataItem;
use App\Service\DatabaseService;
use Exception;
use Faker\Factory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FakeItemCreateCommand extends Command
{
    protected static $defaultName = 'fake:items:create';
    protected static $defaultDescription = 'Create fake items';
    private $em;
    private $registry;
    private $databaseService;
    private $dataEntity;

    public function __construct(ManagerRegistry $registry, DatabaseService $databaseService, DataItem $dataEntity)
    {
        parent::__construct();

        $this->em = $registry->getManager();
        $this->registry = $registry;
        $this->databaseService = $databaseService;
        $this->dataEntity = $dataEntity;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $biSocieties = $this->databaseService->resetTableAndGetSocieties($io, [BiItem::class]);

        $taxesRates = [0, 20, 13, 10, 8.5, 5.5, 2.1];
        $taxesCodes = [0, 1, 2, 3, 4, 5, 6];
        $unities = ["pièce", "heure", "minute", "jour", "nuit", "semaine", "mois", "année", "kg",
            "tonne", "litre", "km", "mètre", "m²"];

        $io->title('Création de 5000 items fake');
        $fake = Factory::create();
        for($i=0; $i<5000 ; $i++) {
            $taxeNumber = $fake->numberBetween(0, 6);
            $data = [
                'name' => $fake->name,
                'content' => $fake->sentence,
                'reference' => substr(uniqid(), 0,10),
                'numero' => uniqid(),
                'unity' => $unities[$fake->numberBetween(0, 13)],
                'price' => $fake->randomFloat(2, 0, 9999),
                'rateTva' => $taxesRates[$taxeNumber],
                'codeTva' => $taxesCodes[$taxeNumber],
            ];

            $data = json_decode(json_encode($data));

            $soc = $biSocieties[$fake->numberBetween(0, count($biSocieties) - 1)];
            $society = $soc['society'];

            $new = $this->dataEntity->setData(new BiItem(), $data, $society);

            $this->em->persist($new);
        }

        $io->text('ITEMS : Articles fake créés' );
        $this->em->flush();

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}
