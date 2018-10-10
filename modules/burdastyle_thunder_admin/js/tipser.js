Drupal.behaviors.burdastyleThunderAdminTipser = {
    attach: () => {
        for (const input of document.querySelectorAll('.advertising-products-autocomplete')) {
            input.addEventListener('change', (e) => { e.target.dispatchEvent(new Event('keydown')) });
        }
    }
};
