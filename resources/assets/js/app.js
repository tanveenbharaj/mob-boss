require('./bootstrap');
import store from './store'
import inputs from './components/inputs'
import Draggable from 'vuedraggable'

Vue.component('oard', require('./components/MobBoard'));
Vue.component('front-panel', require('./components/FrontPanel'));
Vue.component('admin-panel', require('./components/AdminPanel'));
Vue.component('mob-timer', require('./components/Timer'));
Vue.component('mob-notes', require('./components/Notes'));
Vue.component('mob-tasks', require('./components/Tasks'));
Vue.component('mob-comments', require('./components/Comments'));
Vue.component('participants', require('./components/Participants'));
Vue.component('mob-monitor', require('./components/Monitor'));
Vue.component('confirm-alert', require('./components/Confirm'));
Vue.component('mob-bookmarks', require('./components/Bookmarks'));

Vue.component('draggable', Draggable);
const app = new Vue({
    store,
    el: '#app'
});