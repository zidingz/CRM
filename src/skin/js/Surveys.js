window.CRM.surveys = {
  "new": function(surveyName) {
      return window.CRM.APIRequest({
        method: 'POST',
          path: 'surveys/definitions',
          data: JSON.stringify({"surveyDefinitionName":surveyName})
        });
  }
}