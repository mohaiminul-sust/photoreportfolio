
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

const vueImgConfig = {
    altAsTitle: true,
    sourceButton: true,
    openOn: 'click',
    thumbnails: true,
  }
Vue.use(VueImg, vueImgConfig);
