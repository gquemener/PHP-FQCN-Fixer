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
        public function run()
        {
            echo 'You are starting your Motorbike with class!';
        }

        public function stop()
        {
            echo 'You should namespace this beauty...';
        }
    }
    """
    And the following "composer.json" file:
    """
    {
        "autoload": {
            "psr-0": {
                "App\\": "src/"
            }
        }
    }
    """
    And I have dumped the composer autoload
    When I run the fixer with the following arguments:
      | command | fix                     |
      | path    | src/App/Model/Truck.php |
    Then file "src/App/Model/Truck.php" should contain:
    """
    <?php

    namespace App\Model;

    class Truck
    {
        public function run()
        {
           echo 'You are starting your Motorbike with class hombre!';
        }

        public function stop()
        {
            echo 'You should namespace this beauty...';
        }
    }
    """

  Scenario: Successfully fix inconsistent classname following psr-4
    Given the following "src/Model/Truck.php" file:
    """
    <?php

    namespace App;

    class Motorbike
    {
        public function run()
        {
            echo 'You are starting your Motorbike with class!';
        }

        public function stop()
        {
            echo 'You should namespace this beauty...';
        }
    }
    """
    And the following "composer.json" file:
    """
    {
        "autoload": {
            "psr-4": {
                "App\\": "src/"
            }
        }
    }
    """
    And I have dumped the composer autoload
    When I run the fixer with the following arguments:
      | command | fix                 |
      | path    | src/Model/Truck.php |
    Then file "src/Model/Truck.php" should contain:
    """
    <?php

    namespace App\Model;

    class Truck
    {
        public function run()
        {
           echo 'You are starting your Motorbike with class!';
        }

        public function stop()
        {
            echo 'You should namespace this beauty...';
        }
    }
    """
