<template>

    <div class="panel panel-primary participantapp">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="fa fa-user-circle-o hidden-md" ></span>
                Crew
            </h3>
        </div>

        <ul class="list-group participants-list">

            <draggable v-model="participants">
                <transition-group
                        name="custom-classes-transition"
                        enter-active-class="animated flipInX"
                        leave-active-class="animated hinge">
                    <li
                            v-for="(participant, index) in participants"
                            class="list-group-item"
                            :key="participant.name"
                            :class="{
                            'list-group-item-success': participant.active,
                            'non-contributor': !participant.contributor,
                            editing: participant == editedParticipant
                        }"
                            @click.self="setParticipantActive(participant)"
                            @dblclick="editParticipant(participant)">

                        <div class="view" @click.self="setParticipantActive(participant)">

                            <i class="fa fa-keyboard-o" v-show="participant.active"></i>

                            {{ participant.name }}

                            <div class="btn-group pull-right button-bar" role="group" >
                                <button
                                        type="button"
                                        class="btn btn-default btn-xs"
                                        v-if="participant.contributor"
                                        @click="participantsUnsetContributor(participant)">
                                    <i class="fa fa-check-square"></i>
                                </button>
                                <button
                                        type="button"
                                        class="btn btn-default btn-xs"
                                        v-else
                                        @click="participantsSetContributor(participant)">
                                    <i class="fa fa-square-o"></i>
                                </button>

                                <button
                                        type="button"
                                        class="btn btn-default btn-xs"
                                        @click="editParticipant(participant)">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button
                                        type="button"
                                        class="btn btn-default btn-xs btn-danger"
                                        @click="participantDelete(index)">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>

                        <input class="edit" type="text"
                               v-model="participant.name"
                               v-participant-focus="participant == editedParticipant"
                               @blur="doneEdit(participant, index)"
                               @keyup.enter="doneEdit(participant, index)"
                               @keyup.esc="cancelEdit(participant)">

                    </li>
                </transition-group>
            </draggable>

        </ul>
        <div class="panel-footer" @click="addParticipant">
            <small><i class="fa fa-plus" ></i> Add to Crew</small>
        </div>
    </div>

</template>

<script>
    import { mapState, mapMutations, mapActions } from 'vuex'

    export default {
        data () {
            return {
                editedParticipant: null,
            }
        },
        watch: {
            participants: {
                handler: _.debounce(function (e) {
                    this.participantsSync(e)
                }, 500),
                deep: true
            }
        },
        computed: {
            participants: {
                get() { return this.$store.state.participants },
                set(value) { this.participantsSync(value) }
            },
        },
        methods: {
            ...mapMutations([
                'participantAdd',
                'participantsSync',
                'setParticipantActive',
                'participantsUnsetContributor',
                'participantsSetContributor',
            ]),
            ...mapActions([
                'participantDelete'
            ]),
            addParticipant () {
                this.participantAdd({
                    name: '',
                    contributor: true,
                    active: false
                })

                this.$nextTick(() => this.editParticipant(this.participants[0]))
            },
            editParticipant (participant) {
                this.beforeEditCache = participant.name
                this.editedParticipant = participant
            },
            doneEdit (participant, index) {

                if (!this.editedParticipant) {
                    return
                }
                this.editedParticipant = null
                participant.name = participant.name.trim()

                if (!participant.name) {
                    this.participantDelete(index)
                }
            },
            cancelEdit (participant) {
                this.editedParticipant = null
                participant.name = this.beforeEditCache
            },
        },
        directives: {
            'participant-focus'  (el, value) {
                if (value) {
                    el.focus()
                }
            }
        },
    }
</script>
