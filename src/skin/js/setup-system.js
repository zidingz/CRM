

$("document").ready(function () {
    var setupWizard = $("#setup-form");

    setupWizard.validate({
        rules: {
            DB_PASSWORD2: {
                equalTo: "#DB_PASSWORD"
            }
        }
    });

    setupWizard.children("div").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }
            setupWizard.validate().settings.ignore = ":disabled,:hidden";
            return setupWizard.valid();
        },
        onFinishing: function (event, currentIndex)
        {
            setupWizard.validate().settings.ignore = ":disabled";
            return setupWizard.valid();
        },
        onFinished: function (event, currentIndex)
        {
            submitSetupData(setupWizard);
        }
    });
});

function submitSetupData(form) {
    var formArray = form.serializeArray();
    var json = {};

    jQuery.each(formArray, function() {
       json[this.name] = this.value || '';
    });

    $.ajax({
        url: window.CRM.root + "/setup/",
        method: "POST",
        data: JSON.stringify(json),
        contentType: "application/json",
        success: function (data, status, xmlHttpReq) {
            location.replace( window.CRM.root + "/");
        }
    });

}
