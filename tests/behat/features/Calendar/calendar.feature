Feature: Calendar
  In order to manage calendar events
  As a User
  I am able to visit the calendar

  Scenario: Open the calendar
    Given I am authenticated as "admin" using "changeme"
    And  I am on "/v2/calendar"
    Then I should see "Calendar"

  Scenario: Create a new calendar
    Given I am authenticated as "admin" using "changeme"
    And  I am on "/v2/calendar"
    And I click the "#newCalendarButton" element
    And I wait for AJAX to finish
    Then I should see "New Calendar"
    And I fill in "calendarName" with "Test Calendar"
    And I fill in "ForegroundColor" with "000000"
    And I fill in "BackgroundColor" with "FFFFFF"
    And I press "Save"
    And I wait for AJAX to finish
    Then I should see "Test Calendar"

  Scenario: Create a new event;
    Given I am authenticated as "admin" using "changeme"
    And  I am on "/v2/calendar"
    Then I click on "#calendar > div.fc-view-container > div > table > tbody > tr > td > div > div > div:nth-child(1) > div.fc-bg > table > tbody > tr > td.fc-day.fc-widget-content.fc-fri.fc-past"
    And I wait for AJAX to finish
    Then I should see "Save"
    And I fill in "EventTitle" with "Selenium Test Event"
    And I select "Church Service" from "eventType"
    And I fill in "EventDesc" with "Test Description"
    And I fill in "EventDateRange" with "2019-02-13 8:00 AM - 2019-02-13 8:30 AM"
    And I fill in select2 "PinnedCalendars" with "Test Calendar"
    And I fill in CKEditor "eventPredication" with "asdF"
    Then I press "Save"
    And I wait for the calendar to load
    Then I should see "Selenium Test Event"