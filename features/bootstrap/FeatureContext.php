<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Composer\Console\Application;
use Assert\Assertion;
use Assert\Assert;
use Symfony\Component\Console\Input\ArgvInput;
use GildasQ\AutoloadFixer\Composer\Plugin;

class FeatureContext implements Context
{
    private $application;
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
    }

    /**
     * @BeforeStep
     */
    public function createComposerApplication()
    {
        $this->application = new Application();
        $this->application->setAutoExit(false);
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
     * @Then file :filename should contain:
     */
    public function fileShouldContain($filename, PyStringNode $data)
    {
        $filename = $this->getPrefixedPath($filename);
        Assertion::file($filename);
        Assert::that(file_get_contents($filename))->same((string) $data);
    }

    /**
     * @Given I have ran :command
     * @When I run :command
     */
    public function iRunComposer($command)
    {
        if (0 !== strpos($command, 'composer')) {
            throw new \InvalidArgumentException('Cannot execute non composer command');
        }
        $argv = array_merge(
            explode(' ', $command),
            [
                sprintf('-d%s', $this->projectDir),
                '-vvv'
            ]
        );
        $input = new ArgvInput($argv);
        $output = new BufferedOutput();
        $exitCode = $this->application->run($input, $output);

        if (0 !== $exitCode) {
            echo $output->fetch();

            throw new \RuntimeException(sprintf(
                '"%s" returned errored exit code %d',
                $input,
                $exitCode
            ));
        }
    }

    private function getPrefixedPath($filename)
    {
        return sprintf('%s/%s', $this->projectDir, $filename);
    }
}
