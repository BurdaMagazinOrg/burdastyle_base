Drupal.behaviors.burdastyleArticle = {
    selectedPromotionOptions: [],

    setSelectedInputs: function (context) {
        const promotionInputs = context.getElementById('edit-options')
            .querySelectorAll('input[type="checkbox"]:not(#edit-field-promote-states-landing)');
        for (const promotionInput of promotionInputs) {
            if (promotionInput.checked) {
                this.selectedPromotionOptions.push(promotionInput);
            }
        }
    },

    attach: function (context) {
        const landingPageCheckbox = context.getElementById('edit-field-promote-states-landing');
        landingPageCheckbox.addEventListener('change', () => {
            if (landingPageCheckbox.checked) {
                this.setSelectedInputs(context);
            } else {
                for (const promotionInput of this.selectedPromotionOptions) {
                    promotionInput.checked = true;
                }
            }
            this.toggleDisabledAttributeOnPromotionInputs(context, landingPageCheckbox);
        });
    },

    toggleDisabledAttributeOnPromotionInputs: function (context, landingPageCheckbox) {
        const promotionInputs = context.getElementById('edit-options')
            .querySelectorAll('input[type="checkbox"]:not(#edit-field-promote-states-landing)');

        for (const promotionInput of promotionInputs) {
            landingPageCheckbox.checked ?
                promotionInput.setAttribute('disabled', 'disabled') :
                promotionInput.removeAttribute('disabled');

            // remove checked state
            if (landingPageCheckbox.checked) {
                promotionInput.checked = false;
            }
        }
    },
};
