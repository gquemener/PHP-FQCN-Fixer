<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Composer\Console\Application as ComposerApplication;
use Assert\Assertion;
use Assert\Assert;
use PhpFQCNFixer\Console\Application;

class FeatureContext implements Context
{
    private $application;
    private $composerApplication;
    private $projectDir;

    public function __construct()
    {
        $tempFile = tempnam(sys_get_temp_dir(), '');
        if (!is_string($tempFile)) {
            throw new \RuntimeException('Unable to generate unique filename');
        }

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        mkdir($tempFile);
        $this->projectDir = $tempFile;
        $this->application = new Application();
        $this->application->setAutoExit(false);

        $this->composerApplication = new ComposerApplication();
        $this->composerApplication->setAutoExit(false);
    }

    /**
     * @Given the following :filename file:
     */
    public function theFollowingFile($filename, PyStringNode $data)
    {
        $filename = $this->getPrefixedPath($filename);
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($filename, (string) $data);
    }

    /**
     * @When I run the fixer with the following arguments:
     */
    public function iRunTheFixerWithTheFollowingArguments(TableNode $table)
    {
        $arguments = $table->getRowsHash();
        if (isset($arguments['path'])) {
            $arguments['path'] = $this->getPrefixedPath($arguments['path']);
        }
        $input = new ArrayInput($arguments);
        $output = new BufferedOutput();

        if (0 !== $exitCode = $this->application->run($input, $output)) {
            echo $output->fetch();

            throw new \RuntimeException(sprintf(
                'Command "%s" return errored exit code %d',
                implode(' ', $table->getRowsHash()),
                $exitCode
            ));
        }
    }

    /**
     * @Then file :arg1 should contain:
     */
    public function fileShouldContain($filename, PyStringNode $data)
    {
        $filename = $this->getPrefixedPath($filename);
        Assertion::file($filename);
        Assert::that(file_get_contents($filename))->same((string) $data);
    }

    /**
     * @Given I have dumped the composer autoload
     */
    public function iHaveDumpedTheComposerAutoload()
    {
        $input = new ArrayInput([
            'command' => 'dump-autoload',
            '--working-dir' => $this->projectDir,
            '-vvv' => '',
        ]);
        $output = new BufferedOutput();
        $exitCode = $this->composerApplication->run($input, $output);

        if (0 !== $exitCode) {
            echo $output->fetch();

            throw new \RuntimeException(sprintf(
                'composer dump-autoload returned errored exit code %d',
                $exitCode
            ));
        }
    }

    private function getPrefixedPath($filename)
    {
        return sprintf('%s/%s', $this->projectDir, $filename);
    }
}
