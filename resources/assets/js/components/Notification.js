/*
    This is an example for testing only.  Not and active class
 */

export default {
    template: '<div><h1>{{ notification }}</h1></div>',

    props: ['message'],

    computed: {
        notification() {
            return this.message.toUpperCase();
        }
    }
};