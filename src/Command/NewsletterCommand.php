<?php

namespace App\Command;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class NewsletterCommand extends Command
{
    //le nom de votre command (le nom sert pour l'execution de la command)
    protected static $defaultName = 'app:newsletter';

    //propriétées qui contiennent les services autowirés
    protected $em;
    protected $mailer;

    //le constructeur est optionnel il permet de faire passer des services (ici doctrine)
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        //on affecte l'entity manager à notre propriété em pour l'utiliser dans la partie execute de notre command
        $this->em = $entityManager;
        $this->mailer = $mailer;
        parent::__construct();
    }

    //la configuation de votre commande description, arguments, options..
    protected function configure()
    {
        //un argument peut être un chemin vers un repertoire, un nom...
        //une option se présente avec des tirets -h, -a ...
        $this
            ->setDescription('Send newsletter to the distribution list')
            ->addArgument('title', InputArgument::OPTIONAL, 'specify title')
            ->addArgument('template', InputArgument::OPTIONAL, 'specify template')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    //le coeur de la command : le contenu du script
    //la variable $input va contenir les arguments et les options passés dans la commandr
    //la variable $output permet l'affichage dans une console d'information pour l'utilisateur
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = 'emails/signup.html.twig';
        $title = 'Thanks for sign up !';

        if(!empty($input->getArgument('title'))) {
            $title = $input->getArgument('title');
        }

        if(!empty($input->getArgument('template'))) {
            $template = $input->getArgument('template');
        }
        //SymfonyStyle permet d'ajouter du style dans l'affichage (il prend systématiquement $input et $output en paramètre)
        //SymfonyStyle est totalement optionnel et est parfois inutile si la command n'a pas d'informations à afficher
        $io = new SymfonyStyle($input, $output);

        //titre qui sera affiché dans la console
        $io->title('Newsletter command');

        //contenu du script qui permet l'envoi de mail
        $customers = $this->em->getRepository(Customer::class)->findSubscribedCustomers();
        if($customers) {
            /**
             * @var Customer $customer
             */
            $io->writeln("Emailing starting");

            foreach ($customers as $customer) {

                $io->writeln('New Customer found');
                $io->writeln('Sending mail to : '. $customer->getEmail());
                $email = (new TemplatedEmail())
                    ->from('lab@symfony.com')
                    ->to(new Address($customer->getEmail()))
                    ->subject($title)
                    ->htmlTemplate($template)
                    ->context([
                        'expiration_date' => new \DateTime('+7 days'),
                        'firstName' => $customer->getFirstName(),
                        'customerEmail' => $customer->getEmail(),
                        'url' => 'http://127.0.0.1:8000/account'
                    ]);

                try {
                    $this->mailer->send($email);
                    $io->writeln('Email successfully send !');
                }
                catch (TransportExceptionInterface $exception) {
                    $io->writeln('Error while sending mail : '. $exception);
                }
            }
        }
        //message de succès
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        //retour de l'execution de la command Command::SUCCESS = 0 et Command::FAILURE = 1;
        //La command étant un script éxécuté en console on prend le type de retour 'BASH' (0 ou 1)
        return Command::SUCCESS;
    }
}
