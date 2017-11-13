<template>
    <div class='mob-monitor'>
        <div class='admin-loader' v-if="adminLoader">
            <img src="/images/admin-loader.gif" />
        </div>
    </div>
</template>

<script>

    import { mapActions, mapGetters, mapState } from 'vuex'

    export default {
        data () {
            return { }
        },
        watch: {
            assembled: {
                handler: _.debounce(function (e) {
                    if(this.save) {
                        this.persist
                    }
                }, 5000),
                deep: true
            }
        },
        computed: {
            ...mapState({
                adminLoader: state => state.adminLoader,
                save: state => state.persist,
            }),
            ...mapGetters([
                'assembled'
            ]),
            ...mapActions([
                'persist'
            ]),
        }
    }
</script>
