Feature: Fixed inconsistent PHP classname
  In order to increase my productivity
  As a developper
  I need a tool which will fix PSR-0 inconsistency between my PHP Class name and the file it contains

  Scenario: Successfully fix inconsistent classname based on PSR-0 autoloading rules
    Given the following "src/App/Model/A.php" file:
    """
    <?php

    namespace App;

    class B
    {
    }
    """
    And the following "composer.json" file:
    """
    {
        "repositories": [
            {
                "type": "path",
                "url": "/app"
            }
        ],
        "require": {
            "gildasq/autoload-fixer": "*@dev"
        },
        "autoload": {
            "psr-0": {
                "App\\": "src/",
                "Lib\\": "lib/"
            }
        }
    }
    """
    And I have ran "composer install"
    When I run "composer fix-autoload"
    Then file "src/App/Model/A.php" should contain:
    """
    <?php

    namespace App\Model;

    class A
    {
    }
    """
