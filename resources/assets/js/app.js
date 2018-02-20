
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import ElementUI from 'element-ui';
import VueImg from 'v-img';

window.Vue = require('vue');

Vue.use(ElementUI);
// Vue.use(VueCoreImageUpload);

const vueImgConfig = {
    // Use `alt` attribute as gallery slide title
    altAsTitle: true,
    // Display 'download' button near 'close' that opens source image in new tab
    sourceButton: true,
    // Event listener to open gallery will be applied to <img> element
    openOn: 'click',
    // Show thumbnails for all groups with more than 1 image
    thumbnails: true,
  }
Vue.use(VueImg, vueImgConfig);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });
