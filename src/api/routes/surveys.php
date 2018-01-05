<?php

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\SurveyDefinitionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use ChurchCRM\Map\SurveyDefinitionTableMap;
use ChurchCRM\Map\PersonTableMap;
use ChurchCRM\SurveyDefinition;
use ChurchCRM\SurveyResponseQuery;
use ChurchCRM\Map\SurveyResponseTableMap;

$app->group('/surveys', function () {

    $this->get('/definitions', function ($request, $response, $args) {
        $SurveyDefinitions = SurveyDefinitionQuery::create()
                ->joinPerson()
                ->joinSurveyResponse(null, Criteria::LEFT_JOIN)
                ->withColumn('COUNT(SurveyResponse.SurveyResponseId)','Responses')
                ->withColumn(SurveyDefinitionTableMap::COL_NAME)
                ->withColumn(SurveyDefinitionTableMap::COL_SURVEY_DEFINITION_ID)
                ->withColumn(SurveyDefinitionTableMap::COL_OWNER_PER_ID)
                ->withColumn(PersonTableMap::COL_PER_FIRSTNAME)
                ->groupBySurveyDefinitionId()
                ->find();
        return $response->write($SurveyDefinitions->toJSON());
    });
    
     $this->post('/definitions', function ($request, $response, $args) {
        $surveySettings = (object) $request->getParsedBody();
        $survey = new SurveyDefinition();
        $survey->setName($surveySettings->surveyDefinitionName);
        $survey->setOwnerPersonID($_SESSION['user']->getId());
        $survey->save();
        echo $survey->toJSON();
    });
    
    $this->post('/definitions/{id}', function ($request, $response, $args) {
        $survey = ChurchCRM\SurveyDefinitionQuery::create()
                ->findPk($args['id']);
        if (!isset($survey)) {
          throw new \Exception("No survey by that ID");
        }
        $surveySettings = (object) $request->getParsedBody();
        $survey->setDefinition($surveySettings->definition);
        $survey->save();
        echo $survey->toJSON();
    });
    
     $this->get('/responses', function ($request, $response, $args) {
        $SurveyResponses = SurveyResponseQuery::create()
                ->joinSurveyDefinition()
                ->withColumn(SurveyResponseTableMap::COL_SURVEY_RESPONSE_ID)
                ->withColumn(SurveyResponseTableMap::COL_DATE_SUBMITTED)
                ->withColumn(SurveyDefinitionTableMap::COL_NAME)
                ->withColumn(SurveyDefinitionTableMap::COL_SURVEY_DEFINITION_ID)
                ->find();
        return $response->write($SurveyResponses->toJSON());
    });
    
    
});
    
    
?>