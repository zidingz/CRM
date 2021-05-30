/// <reference types="cypress" />

context('API Private Admin locale', () => {

    it('Get DB terms for localization', () => {
        cy.makePrivateAdminAPICall("GET", '/api/locale/database/terms', null, 200);
    });

});

