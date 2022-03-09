@qtype @qtype_molsimilarity
Feature: Test creating a molsimilarity question
  As a teacher
  In order to test my students
  I need to be able to create a molsimilarity question

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | teacher1 | T1        | Teacher1 | teacher1@moodle.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" in current page administration

  Scenario: Create a Short answer question
    When I add a "Molsimilarity" question filling the form with:
      | Question name        | molsimilarity-001                         |
      | Question text        | Draw a molecule of Methane  |
      | General feedback     | Simple as that |
      | Default mark         | 1                                         |
      | id_answer_0          | {"json":"{\"m\":[{\"a\":[{\"x\":255.75,\"y\":141,\"i\":\"a0\"}]}]}","mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  1  0  0  0  0  0            999 V2000\n    0.0000    0.0000    0.0000 C   0  0  0  0  0  0\nM  END"} |
      | id_fraction_0        | 100%                                      |
      | id_feedback_0        | Well done.             |
    Then I should see "molsimilarity-001"
