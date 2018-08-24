/* eslint-disable no-alert */

const handleEditedForms = {
  formHasChanged: false,
  form: document.querySelector('#node-article-edit-form, #node-article-edit-plus-form'),
  handleClick(e) {
    const querySelector = '.tabs a[href$="/edit"], .tabs a[href$="/edit_plus"]';
    const message = 'You have unsaved changes. Do you really want to leave this site?';
    const clickedNavigation = e.target.matches(querySelector);

    if (clickedNavigation && this.formHasChanged) {
      const answer = window.confirm(message);
      if (!answer) e.preventDefault();
    }
  },
  handleFormChange() {
    this.formHasChanged = true;
  },
  handleMutations(mutationList) {
    const isChildList = !!mutationList.find(mutation => mutation.type === 'childList');
    if (isChildList) {
      Object.keys(CKEDITOR.instances).forEach((editor) => {
        const instance = CKEDITOR.instances[editor];
        if (!instance.listenerAdded) {
          instance.on('change', e => this.handleFormChange(e));
          instance.listenerAdded = true;
        }
      });
    }
  },
  init() {
    const observer = new MutationObserver(this.handleMutations.bind(this));
    observer.observe(this.form, { attributes: false, childList: true, subtree: true });
    this.form.addEventListener('change', this.handleFormChange.bind(this));
    document.body.addEventListener('click', this.handleClick.bind(this));
  },
};

document.addEventListener('DOMContentLoaded', handleEditedForms.init.bind(handleEditedForms));
