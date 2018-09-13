Drupal.behaviors.burdastyleArticle = {
  selectedPromotionOptions: [],
  landingPageCheckbox: null,
  attach(context) {
    this.landingPageCheckbox = context.querySelector('#edit-field-promote-states-landing');
    this.landingPageCheckbox.addEventListener('change', (e) => {
      this.handleSelection(e, context);
    });
    this.selectCheckboxes(context);
  },

  handleSelection(e, context) {
    this.selectCheckboxes(context);
  },

  selectCheckboxes(context) {
    if (this.landingPageCheckbox.checked) {
      this.setSelectedInputs(context);
    }
    else {
      this.selectedPromotionOptions.forEach((promotionInput) => {
        promotionInput.checked = true;
      });
    }
    this.toggleDisabledAttributeOnPromotionInputs(context, this.landingPageCheckbox);
  },

  setSelectedInputs(context) {
    const promotionInputs = context.querySelector('#edit-options')
      .querySelectorAll('input[type="checkbox"]:not(#edit-field-promote-states-landing)');
    for (const promotionInput of promotionInputs) {
      if (promotionInput.checked) {
        this.selectedPromotionOptions.push(promotionInput);
      }
    }
  },

  toggleDisabledAttributeOnPromotionInputs(context, landingPageCheckbox) {
    const promotionInputs = context.querySelector('#edit-options')
      .querySelectorAll('input[type="checkbox"]:not(#edit-field-promote-states-landing)');

    for (const promotionInput of promotionInputs) {
      landingPageCheckbox.checked
        ? promotionInput.setAttribute('disabled', 'disabled')
        : promotionInput.removeAttribute('disabled');

      // remove checked state
      if (landingPageCheckbox.checked) {
        promotionInput.checked = false;
      }
    }
  },
};
