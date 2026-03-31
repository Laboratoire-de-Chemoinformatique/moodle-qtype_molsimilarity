@javascript @qtype @qtype_molsimilarity
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


  Scenario: Create a molsimilarity question
    When I am on the "Course 1" "core_question > course question bank" page logged in as "teacher1"
    And I change viewport size to "1400x1000"
    And I press "Create a new question ..."
    And I set the field "item_qtype_molsimilarity" to "1"
    And I press "submitbutton"
    And I set the following fields to these values:
      | Question name        | molsimilarity-001                         |
      | Question text        | Draw a molecule of Methane  |
      | General feedback     | Simple as that |
      | Default mark         | 1                                         |
      | id_fraction_0        | 100%                                      |
      | id_feedback_0        | Well done.             |
    And I fill molsimilarity answer field "id_answer_0" with carbon
    And I press "id_submitbutton"
    Then I should see "molsimilarity-001"
