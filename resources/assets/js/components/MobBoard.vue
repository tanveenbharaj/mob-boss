<template>
    <div>
        <transition
            :name="(showAdmin) ? 'turn-clockwise' : 'turn-counter-clockwise'"
            mode='out-in'>
            <admin-panel v-if="showAdmin"></admin-panel>
            <front-panel v-else></front-panel>
        </transition>
    </div>
</template>

<script>
    import { mapActions, mapMutations, mapState } from 'vuex'

    export default {
        props: ['slug'],
        data() {
            return {
                transition: "turn-clockwise"
            }
        },
        computed: mapState({
                showAdmin: state => state.adminDisplay,
        }),
        methods: {
            ...mapMutations([
                'createdOn',
                'setSlug',
            ]),
            ...mapActions([
                'loadMob'
            ]),
        },
        mounted() {

            if (this.slug) {
                this.createdOn()
                this.setSlug(this.slug)
                this.loadMob()
            }
        }
    }
</script>
