Feature: Fixed inconsistent PHP class FQCN
  In order to increase my productivity
  As a developper
  I need a tool which will fix PSR-0 inconsistency between my PHP Class name and the file it contains

  Scenario: Successfully fix inconsistent classname following psr-0
    Given the following "src/App/Model/Truck.php" file:
    """
    <?php

    namespace App;

    class Motorbike
    {
    }
    """
    And the following "composer.json" file:
    """
    {
        "autoload": {
            "psr-0": {
                "PhpFQCNFixer": "src/"
            }
        }
    }
    """
    And I dump the composer autoload
    When I run the fixer with the following arguments:
      | command_name | fix                     |
      | path         | src/App/Model/Truck.php |
    Then file "src/App/Model/Truck.php" should contain:
    """
    <?php

    namespace App\Model;

    class Truck
    {
    }
    """
