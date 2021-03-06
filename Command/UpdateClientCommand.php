<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 21.09
 */

namespace Manticora\OAuthBundle\Command;


use Manticora\OAuthBundle\Model\ClientRoleableInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('manticora:oauth-server:client:update')
            ->setDescription('Creates a new client')
            ->addArgument('id', InputArgument::REQUIRED, 'Sets the client name', null)
            ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.', null)
            ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..', null)
            ->addOption('role', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets role for client. Use this option multiple times to set multiple roles..', null)
            ->setHelp(<<<EOT
The <info>%command.name%</info>command creates a new client.

  <info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // $this->getContainer()->get('security.context')->


        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->findClientByPublicId($input->getArgument('id'));
        $client->setAllowedGrantTypes(array_merge($client->getAllowedGrantTypes(),$input->getOption('grant-type')));


        $clientManager->updateClient($client);
        $output->writeln(sprintf('Update a new client with name <info>%s</info> and public id <info>%s</info>.', $client->getName(), $client->getPublicId()));
    }
}