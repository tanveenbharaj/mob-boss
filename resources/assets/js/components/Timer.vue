<template>
    <div class="panel panel-primary timerapp">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-7">
                    <h3 class="panel-title">
                        <span class="fa fa-clock-o "></span>
                        Timer
                    </h3>
                </div>
                <div class="col-xs-5">

                    <div class="btn-group controllers pull-right" role="group" >
                        <button
                             type="button"
                             class="btn btn-info btn-xs"
                             @click="timerStart"
                             v-if="paused">
                                <i class="fa fa-play"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning btn-xs"
                            @click="paused = !paused"
                            v-else>
                                <i class="fa fa-pause"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger btn-xs"
                            @click="timerReset">
                                <i class="fa fa-stop"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="onBreak">
            <div class='break'>

                <h2>Take a Break</h2>
                <p>8.3 out of 10 doctors recommend regular breaks!</p>

                <button class='btn btn-primary btn-sm end-break'
                @click="onBreak = false">
                    Break Over
                </button>
            </div>
        </div>
        <div class="panel-body" v-else>
            <h2 class='counter'>{{ remaining }}</h2>
            <small>Take a break in {{ breakIntervals - count }} more sessions - <i class='fa fa-undo link-like' @click="timerResetCounter"></i></small>
        </div>

    </div>
</template>

<script>

    import { mapActions, mapMutations, mapState } from 'vuex'

    export default {

        computed: {
            remaining() {
                return moment(this.duration.asMilliseconds()).format('mm:ss')
            },
            paused: {
                get() { return this.$store.state.timer.paused },
                set(value) {
                    if(value) {
                        this.$store.commit('timerPaused')
                    } else {
                        this.$store.commit('timerUnpaused')
                    }
                }
            },
            onBreak: {
                get() { return this.$store.state.timer.onBreak },
                set(value) {
                    this.$store.commit('timerBreakOff')
                }
            },
            ...mapState({
                duration: state => state.timer.duration,
                created: state => state.timer.created,
                count: state => state.timer.count,
                count: state => state.timer.count,
                breakIntervals: state => state.timerOptions.breakIntervals,
            }),
        },
        methods: {
            ...mapActions([
                'timerStart',
                'timerReset'
            ]),
            ...mapMutations([
                'timerBuildDuration',
                'timerCreate',
                'timerResetCounter'
            ]),
        },
        created() {
            if (!this.created) {
                this.timerBuildDuration()
                this.timerCreate()
            }
        }
    }
</script>
