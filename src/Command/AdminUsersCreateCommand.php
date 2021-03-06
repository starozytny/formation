<?php

namespace App\Command;

use App\Entity\Bill\BiCustomer;
use App\Entity\Bill\BiHistory;
use App\Entity\Bill\BiInvoice;
use App\Entity\Bill\BiItem;
use App\Entity\Bill\BiProduct;
use App\Entity\Bill\BiSociety;
use App\Entity\Bill\BiTaxe;
use App\Entity\Bill\BiUnity;
use App\Entity\Notification;
use App\Entity\Society;
use App\Entity\User;
use App\Service\Data\Bill\DataBill;
use App\Service\Data\Society\DataSociety;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminUsersCreateCommand extends Command
{
    protected static $defaultName = 'admin:users:create';
    private $em;
    private $databaseService;
    private $dataSociety;
    private $dataBill;

    public function __construct(EntityManagerInterface $entityManager, DatabaseService $databaseService,
                                DataSociety $dataSociety, DataBill $dataBill)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->databaseService = $databaseService;
        $this->dataSociety = $dataSociety;
        $this->dataBill = $dataBill;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create an user and an admin.')
            ->addOption('fake', "f", InputOption::VALUE_NONE, 'Option shit values')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $this->databaseService->resetTable($io, [
            BiCustomer::class, BiUnity::class, BiTaxe::class, BiProduct::class, BiHistory::class, BiInvoice::class, BiItem::class,
            Notification::class, User::class, Society::class
        ]);

        $fake = Factory::create();

        $users = array(
            [
                'username' => 'shanbo',
                'firstname' => 'Dev',
                'lastname' => 'Shanbora',
                'email' => 'chanbora.chhun@outlook.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN', 'ROLE_DEVELOPER']
            ],
            [
                'username' => 'staro',
                'firstname' => 'Admin',
                'lastname' => 'Starozytny',
                'email' => 'starozytny@hotmail.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN']
            ],
            [
                'username' => 'shanks',
                'firstname' => 'User',
                'lastname' => 'Shanks',
                'email' => 'shanks@hotmail.fr',
                'roles' => ['ROLE_USER']
            ],
            [
                'username' => 'manager',
                'firstname' => 'Manager',
                'lastname' => 'Shan',
                'email' => 'chanbora.manager@outlook.fr',
                'roles' => ['ROLE_USER', 'ROLE_MANAGER']
            ],
        );

        $password = password_hash("azerty", PASSWORD_ARGON2I);

        $io->title('Cr??ation de la soci??t?? Logilink');
        $data = [
            "name" => "Logilink",
            "code" => 0,
            "siren" => "",
            "siret" => "",
            "rcs" => "",
            "numeroTva" => "",
            "forme" => 1,
            "address" => "17 rue de la r??publique",
            "address2" => "",
            "zipcode" => "13002",
            "city" => "MARSEILLE 02",
            "complement" => "",
            "country" => "France",
            "email" => "chanbora@logilink.fr",
            "phone1" => "0652XXXXXX",
            "bankName" => $fake->company,
            "bankNumero" => $fake->creditCardNumber,
            "bankTitulaire" => $fake->lastName,
            "bankBic" => $fake->swiftBicNumber,
            "bankCode" => $fake->numberBetween(10,50),
            "bankIban" => $fake->iban,
            "noteQuotation" => "",
            "noteInvoice" => "",
            "noteAvoir" => "",
            "footerQuotation" => "",
            "footerInvoice" => "",
            "footerAvoir" => "",
        ];
        $data = json_decode(json_encode($data));

        $society = $this->dataSociety->setData(new Society(), $data, 0);
        $biSociety = $this->dataBill->setDataSociety(new BiSociety(), $data);

        $this->em->persist($society);
        $this->em->persist($biSociety);
        $io->text('SOCIETE : Logilink cr????' );

        $io->title('Cr??ation des utilisateurs');
        foreach ($users as $user) {
            $new = (new User())
                ->setUsername($user['username'])
                ->setEmail($user['email'])
                ->setRoles($user['roles'])
                ->setFirstname(ucfirst($user['firstname']))
                ->setLastname(mb_strtoupper($user['lastname']))
                ->setPassword($password)
                ->setSociety($society)
            ;

            $this->em->persist($new);
            $io->text('USER : ' . $user['username'] . ' cr????' );
        }

        if ($input->getOption('fake')) {
            $io->title('Cr??ation de 10 soci??t?? fake');
            $societies = [];
            for($i=0; $i<10 ; $i++) {
                $data = [
                    "name" => $fake->name,
                    "code" => $i,
                    "siren" => "",
                    "siret" => "",
                    "rcs" => "",
                    "numeroTva" => "",
                    "forme" => $fake->numberBetween(0, 4),
                    "address" => $fake->streetName,
                    "zipcode" => $fake->postcode,
                    "city" => $fake->city,
                    "country" => $fake->country,
                    "complement" => $fake->lastName,
                    "email" => $fake->email,
                    "phone1" => $fake->e164PhoneNumber,
                    "bankName" => $fake->company,
                    "bankNumero" => $fake->creditCardNumber,
                    "bankTitulaire" => $fake->lastName,
                    "bankBic" => $fake->swiftBicNumber,
                    "bankCode" => $fake->numberBetween(10,50),
                    "bankIban" => $fake->iban,
                    "noteQuotation" => "",
                    "noteInvoice" => "",
                    "noteAvoir" => "",
                    "footerQuotation" => "",
                    "footerInvoice" => "",
                    "footerAvoir" => "",
                ];

                $data = json_decode(json_encode($data));

                $new = $this->dataSociety->setData(new Society(), $data, $i+1);
                $new2 = $this->dataBill->setDataSociety(new BiSociety(), $data);

                $this->em->persist($new);
                $this->em->persist($new2);
                $societies[] = $new;
            }
            $io->text('SOCIETE : Soci??t??s fake cr????es' );

            $io->title('Cr??ation de 110 utilisateurs fake');
            $fake = Factory::create();
            for($i=0; $i<110 ; $i++) {
                $new = (new User())
                    ->setUsername($fake->userName)
                    ->setEmail($fake->freeEmail)
                    ->setRoles(['ROLE_USER'])
                    ->setFirstname(ucfirst($fake->firstName))
                    ->setLastname(mb_strtoupper($fake->lastName))
                    ->setPassword($password)
                    ->setSociety($societies[$fake->numberBetween(0,9)])
                ;

                $this->em->persist($new);
            }
            $io->text('USER : Utilisateurs fake cr????s' );
        }

        $this->em->flush();

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return 0;
    }
}
