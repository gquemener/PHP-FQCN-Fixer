Feature: Fixed inconsistent PHP class FQCN
  In order to increase my productivity
  As a developper
  I need a tool which will fix PSR-0 inconsistency between my PHP Class name and the file it contains

  Scenario: Successfully fix inconsistent classname
    Given the following "src/App/Model/Truck.php" file:
    """
    <?php

    namespace App;

    class Motorbike
    {
    }
    """
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
