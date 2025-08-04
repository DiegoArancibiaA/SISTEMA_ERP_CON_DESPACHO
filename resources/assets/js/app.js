/*       www/resources/assets/js
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('info-box', require('./dashboard/InfoBox.vue').default);

const app = new Vue({
    el: '#inventory' // o #app si así lo usas
});
