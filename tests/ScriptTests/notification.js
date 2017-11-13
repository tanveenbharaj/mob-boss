import Vue from 'vue/dist/vue.js';
import test from 'ava';
import Notification from '../../resources/assets/js/components/Notification';

let vm;

test.beforeEach(t => {
    let N = Vue.extend(Notification);

    vm = new N({ propsData: {
        message: 'Foobar'
    }}).$mount();
});


test('that is renders a notification', t => {

    t.is(vm.$el.textContent, "FOOBAR")

});