Drupal.behaviors.editPlus = {
  formHasChanged: false,
  attach(context) {
    jQuery('input, select, textarea').on(
        'change',
        () => {
          if (false === this.formHasChanged) {
            jQuery('.tabs a[href$="/edit"], .tabs a[href$="/edit_plus"]').on('click', (e) => {
              const answer = confirm('You have unsaved changes. Do you really want to leave this site?');
              if (false === answer)
                e.preventDefault();
            });
          }
          this.formHasChanged = true;
        }
    );
  },
};
