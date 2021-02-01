import animateScrollTo from 'animated-scroll-to'

import IndexField from './components/fields/progress-bar/IndexField'
import DetailField from './components/fields/progress-bar/DetailField'
import FormField from './components/fields/progress-bar/FormField'
import LocaleDropdown from './components/tools/LocaleDropdown'

require('../bootstrap');

Nova.booting((Vue, router, store) => {
  Vue.component('index-progress-bar', IndexField)
  Vue.component('detail-progress-bar', DetailField)
  Vue.component('form-progress-bar', FormField)

  Vue.component('locale-dropdown', LocaleDropdown)

  // Scroll to top on route change
  router.beforeEach(function (to, from, next) {
    setTimeout(() => {
      if(typeof document.activeElement.type !== 'undefined' && document.activeElement.closest('div[dusk*=index-component]')){
        animateScrollTo(document.activeElement.closest('div[dusk*=index-component]').offsetTop);
      }else{
        animateScrollTo(0);
      }
    }, 100);
    next();
  });

  // Scroll to top on error
  const origOpen = XMLHttpRequest.prototype.open;
  XMLHttpRequest.prototype.open = function() {
    this.addEventListener('load', function() {
      if (this.readyState === 4 && this.status === 422 && document.getElementsByClassName('modal').length < 1) {
        setTimeout(() => {
          if (document.querySelector('.error-text'))
            animateScrollTo(document.querySelector('.error-text').parentNode)
        }, 200);
      }
    });
    origOpen.apply(this, arguments);
  };
})
