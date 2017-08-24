Feature: Soccer
  As an user of website-api-converter
  in order to get soccer-data
  I need to be able to make requests to the API

  Scenario: List soccer matches
    When I request "GET http://localhost/api/v1/soccer/matches"
    Then I get a "200" response
    And scope into the first property
    And the properties exist:
    """
    home_team
    visiting_team
    date
    """

  Scenario: List specific soccer match
    When I request "GET http://localhost/api/v1/soccer/matches/1"
    Then I get a "200" response
    And the properties exist:
    """
    home_team
    visiting_team
    date
    """

  Scenario: Search for matches
    When I request "GET http://localhost/api/v1/soccer/matches/search/visiting_team/DSO 1"
    Then I get a "200" response
    And scope into the first property
    Then the properties exist:
    """
    home_team
    visiting_team
    date
    """
