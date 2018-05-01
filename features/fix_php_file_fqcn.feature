Feature: Fixed inconsistent PHP classname
  In order to increase my productivity
  As a developper
  I need a tool which will fix PSR-0 inconsistency between my PHP Class name and the file it contains

  Scenario: Successfully fix inconsistent autoloading based on PSR-0
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
                "App\\": "src/"
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

  Scenario: Successfully fix inconsistent autoloading based on PSR-0 with underscored classname
    Given the following "lib/App/Model/A.php" file:
    """
    <?php

    class App_Model_B
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
                "App_": "lib/"
            }
        }
    }
    """
    And I have ran "composer install"
    When I run "composer fix-autoload"
    Then file "lib/App/Model/A.php" should contain:
    """
    <?php

    class App_Model_A
    {
    }
    """

