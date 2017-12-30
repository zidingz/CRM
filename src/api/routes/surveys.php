<?php

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\SurveyDefinitionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use ChurchCRM\Map\SurveyDefinitionTableMap;
use ChurchCRM\SurveyDefinition;
$app->group('/surveys', function () {

    $this->get('/definitions', function ($request, $response, $args) {
        $SurveyDefinitions = SurveyDefinitionQuery::create()
                ->select(array("name","survey_definition_id"))
                ->find();
        return $response->write($SurveyDefinitions->toJSON());
    });
    
     $this->post('/definitions', function ($request, $response, $args) {
        $surveySettings = (object) $request->getParsedBody();
        $survey = new SurveyDefinition();
        $survey->setName($surveySettings->surveyDefinitionName);
        $survey->save();
        echo $survey->toJSON();
    });
    
    
});
    
    
?>