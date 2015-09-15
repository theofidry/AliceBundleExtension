Feature: Test Doctrine ORM context

  Scenario: Emptying the database
    Given the database is empty
    Given there is 10 "dummy" entities
    Then the database should contain 10 "dummy" entities
    When I empty the database
    Then the database should be empty

  Scenario: Loads a fixtures file with @Bundlename notation
    Given the database is empty
    Given the fixtures file "@TestBundle/DataFixtures/ORM/dummy.yml" is loaded
    Then the database should contain 10 "dummy" entities

  Scenario: Loads a fixture file base on basePath
    Given the database is empty
    Given the fixtures file "another_dummy.yml" is loaded
    Then the database should contain 10 "another_dummy" entities

  Scenario: Loads a fixture file with absolute path
    Given the database is empty
    Given the fixtures file "/home/travis/build/theofidry/AliceFixturesExtension/tests/Features/fixtures/ORM/another_dummy.yml" is loaded
    Then the database should contain 10 "another_dummy" entities

  Scenario: Loads a fixture file with a custom persister
    Given the database is empty
    Given the fixtures file "another_dummy.yml" is loaded with the persister "doctrine.orm.entity_manager"
    Then the database should contain 10 "another_dummy" entities
