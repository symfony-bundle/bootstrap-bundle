<?php

namespace Anezi\Bundle\BootstrapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class BootstrapExtraAssetsInstallCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('bootstrap:extra-assets:install')
            ->setDefinition(array(
                new InputArgument('target', InputArgument::OPTIONAL, 'The target directory', 'web'),
            ))
            ->setDescription('Installs twitter bootstrap web extra assets under a public web directory')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs twitter bootstrap bundle extra assets into a given
directory (e.g. the web directory).

<info>php %command.full_name% web</info>

The Twitter Bootstrap files will be copied into the web bundles directory.

EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException When the target directory does not exist or symlink cannot be used
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $targetArg = rtrim($input->getArgument('target'), '/');

        if (!is_dir($targetArg)) {
            throw new \InvalidArgumentException(sprintf('The target directory "%s" does not exist.', $input->getArgument('target')));
        }

        /** @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get('filesystem');

        // Create the bundles directory otherwise symlink will fail.
        $bundlesDir = $targetArg . '/bundles/';
        $filesystem->mkdir($bundlesDir, 0777);

        $output->writeln(
            'Installing Bootstrap assets.'
        );

        $targetDir  = $bundlesDir . 'bootstrap-extra';

        $namespaceParts = explode('\\', __NAMESPACE__);
        array_pop($namespaceParts);

        $output->writeln(
            sprintf(
                'Installing assets for <comment>%s</comment> into <comment>%s</comment>',
                implode('\\', $namespaceParts),
                $targetDir
            )
        );

        $filesystem->remove($targetDir);

        $filesystem->mkdir($targetDir, 0777);
        $filesystem->mkdir($targetDir . '/css', 0777);

        $vendor = 'vendor';

        $filesystem->mirror(
            $vendor . '/twbs/bootstrap/dist',
            $targetDir
        );
    }
}
