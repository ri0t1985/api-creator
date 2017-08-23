Feature: Soccer
  As an user of website-api-converter
  in order to get soccer-data
  I need to be able to make requests to the API

  Scenario: List soccer matches
    When I request "GET api/v1/soccer/matches"
    Then I get a "200" response
    And the properties exist:
    """
    id
    home-team
    visiting-team
    date
    score
    """

  Scenario: List specific soccer match
    When I request "GET api/v1/soccer/matches/1"
    Then I get a "200" response
    And the properties exist:
    """
    id
    home-team
    visiting-team
    date
    score
    """
