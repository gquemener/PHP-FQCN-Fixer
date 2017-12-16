<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Assert\Assertion;
use Assert\Assert;
use PhpFQCNFixer\Fixer\Infrastructure\Console\Application;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $application;
    private $projectDir;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
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
        $this->application = new Application('behat-dev');
        $this->application->setAutoExit(false);
    }

    /**
     * @Given the following :filename file:
     */
    public function theFollowingFile($filename, PyStringNode $data)
    {
        $filename = $this->getPrefixedPath($filename);
        mkdir(dirname($filename), 0777, true);
        file_put_contents($filename, (string) $data);
    }

    /**
     * @When I run the fixer with the following arguments:
     */
    public function iRunTheFixerWithTheFollowingArguments(TableNode $table)
    {
        $input = new ArrayInput($table->getRowsHash());
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

    private function getPrefixedPath($filename)
    {
        return sprintf('%s/%s', $this->projectDir, $filename);
    }
}
