<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:create-admin',
    description: 'Crea el usuario admin.',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateUserCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setHelp('Crea el usuario admin en la base de datos...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Creacion de usuario administrador',
            '==================================',
            'Se creara el usuario administrador',
            'Si ya existe este usuario admin, el password sera actualizado al password por default \'test\'',
            ''
        ]);

        $res = $this->entityManager->getRepository(User::class)->createAdmin();

        if (!$res['success'])
            return Command::FAILURE;

        $output->writeln([
            $res['msg'],
            '',
            'Se recomienda cambiar el password'
            ]);


        return Command::SUCCESS;
    }

}
