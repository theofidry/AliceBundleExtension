Feature: Test Doctrine ODM context

  Scenario: Emptying the database
    Given the database is empty
    Given there is 10 "dummy" entities
    Then the database should contain 10 "dummy" entities
    When I empty the database
    Then the database should be empty

  Scenario: Loads a fixtures file with @Bundlename notation
    Given the database is empty
    Given the fixtures file "@TestBundle/DataFixtures/ODM/dummy.yml" is loaded
    Then the database should contain 10 "dummy" entities

  Scenario: Loads a fixtures directory with @Bundlename notation
    Given the database is empty
    Given the fixtures "@TestBundle" are loaded
    Then the database should contain 11 "dummy" entities

  Scenario: Loads a fixture file based on basePath
    Given the database is empty
    Given the fixtures file "another_dummy.yml" is loaded
    Then the database should contain 10 "another_dummy" entities

  Scenario: Loads a fixture file with absolute path
    Given the database is empty
    Given the fixtures file "/home/travis/build/theofidry/AliceBundleExtension/tests/Features/fixtures/ODM/another_dummy.yml" is loaded
    Then the database should contain 10 "another_dummy" entities

  Scenario: Loads a fixture directory with absolute path
    Given the database is empty
    Given the fixtures "/home/travis/build/theofidry/AliceBundleExtension/tests/Features/fixtures/ODM/" are loaded
    Then the database should contain 11 "another_dummy" entities

  Scenario: Loads a fixture file with a custom persister
    Given the database is empty
    Given the fixtures file "another_dummy.yml" is loaded with the persister "doctrine_mongodb.odm.default_document_manager"
    Then the database should contain 10 "another_dummy" entities

  Scenario: Loads several fixtures files based on basePath
    Given the database is empty
    Given the following fixtures files are loaded:
      | another_dummy.yml     |
      | one_another_dummy.yml |
    Then the database should contain 11 "another_dummy" entities

  Scenario: Loads several fixtures files with @Bundlename notation
    Given the database is empty
    Given the following fixtures files are loaded:
      | @TestBundle/DataFixtures/ODM/dummy.yml             |
      | @TestBundle/DataFixtures/ODM/one_another_dummy.yml |
    Then the database should contain 11 "dummy" entities

  Scenario: Loads several duplicated fixtures
    Given the database is empty
    Given the following fixtures files are loaded:
      | @TestBundle/DataFixtures/ODM/dummy.yml |
      | @TestBundle                            |
    Then the database should contain 11 "dummy" entities
