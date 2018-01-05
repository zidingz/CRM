<?php

use Slim\Views\PhpRenderer;
use ChurchCRM\FamilyQuery;
use ChurchCRM\PersonQuery;
use ChurchCRM\TokenQuery;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Note;
use ChurchCRM\Person;
use ChurchCRM\Base\SurveyDefinitionQuery;
use ChurchCRM\SurveyResponse;

$app->group('/survey', function () {

  $this->get('/{token}', function ($request, $response, $args) {
    $renderer = new PhpRenderer("templates/survey/");
   
  
    $token = TokenQuery::create()->findPk($args['token']);
    
    if ($token != null && $token->isSurveyToken() && $token->isValid()) {
      
      $surveyDefinition = SurveyDefinitionQuery::create()->findPk($token->getReferenceId());
      $haveSurvey= ($surveyDefinition != null);
      if ($token->getRemainingUses() > 0) {
        $token->setRemainingUses($token->getRemainingUses() - 1);
        $token->save();
      }
    }

    if ($haveSurvey) {
      return $renderer->render($response, "complete-survey.php", array("surveyDefinition" => $surveyDefinition, "token" => $token));
    } else {
      return $renderer->render($response, "/../404.php", array("message" => gettext("Unable to load survey")));
    }
  });

  $this->post('/{token}', function ($request, $response, $args) {
   
    
    $token = TokenQuery::create()->findPk($args['token']);
    
    $surveyDefinition = SurveyDefinitionQuery::create()->findPk($token->getReferenceId());
    $haveSurvey= ($surveyDefinition != null);
    
    if ($haveSurvey) {
        $surveyPOST = (object) $request->getParsedBody();
        $surveyResponse = new SurveyResponse();
        $surveyResponse->setDateSubmitted(new DateTime());
        $surveyResponse->setSurveyDefinition($surveyDefinition);
        $surveyResponse->setResponseData(json_encode($surveyPOST->responses));
        $surveyResponse->save();
        return $response->withJSON(array("status"=>"success"));
    } else {
      return $response->withJSON(array("status"=>"fail"));
    }
    
   
  });

});


