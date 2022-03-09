@qtype @qtype_molsimilarity
Feature: Test duplicating a quiz containing a Molsimilarity question
  As a teacher
  In order re-use my courses containing Molsimilarity questions
  I need to be able to backup and restore them

  Background:
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype       | name            | template |
      | Test questions   | molsimilarity | molsimilarity-001 | ethanollp |
    And the following "activities" exist:
      | activity   | name      | course | idnumber |
      | quiz       | Test quiz | C1     | quiz1    |
    And quiz "Test quiz" contains the following questions:
      | molsimilarity-001 | 1 |
    And I log in as "admin"
    And I am on "Course 1" course homepage

  @javascript
  Scenario: Backup and restore a course containing a Molsimilarity question
    When I backup "Course 1" course using this options:
      | Confirmation | Filename | test_backup.mbz |
    And I restore "test_backup.mbz" backup into a new course using this options:
      | Schema | Course name | Course 2 |
    And I navigate to "Question bank" in current page administration
    And I choose "Edit question" action for "molsimilarity-001" in the question bank
    Then the following fields match these values:
      | Question name        | molsimilarity-001                                 |
      | Question text        | Draw the lewis structure of a molecule of ethanol.|
      | General feedback     | Ethanol should not be confused with methanol.     |
      | Option stereochemistry | 1             |
      | Default mark         | 1                                                 |
      | id_answer_0          | {"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}","mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}     |
      | id_fraction_0        | 100%                                              |
      | id_feedback_0        | Think about the lone pairs !                      |
