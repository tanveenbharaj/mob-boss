<template>
    <div class=" notesapp">

        <div class='row bottom-buffer '>
            <div class='col-xs-12 '>
                <div class="btn btn-success btn-xs pull-right" @click="addNote"><i class="fa fa-plus" ></i></div>
            </div>
        </div>

        <div class="note-list">

            <div v-for="(note, index) in filteredNotes"
                class="alert"
                :class="[
                    severityClass(note.severity),
                    {'editing': note == editedNote }
                ]"
                @dblclick="editNote(note)"
                role="alert"
                ref="myNotes">

                <span class='view'>{{ note.body }}</span>

                <textarea
                    class="edit"
                    v-model="note.body"
                    v-note-focus="note == editedNote"
                    @blur="doneEdit(note)"
                    @keyup.enter="doneEdit(note)"
                    @keyup.esc="cancelEdit(note)"
                    :style="'height:'+ minHeight +'px'">
                </textarea>

                <div class="button-bar">

                    <div class="btn-group pull-right button-bar" role="group">

                        <input-btn-rank
                            v-model="note.severity">
                        </input-btn-rank>

                        <button
                             type="button"
                             class="btn btn-default btn-xs"
                             @click="editNote(note)">
                                <i class="fa fa-pencil"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-xs btn-danger"
                            @click="noteDelete(index)">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'
    import * as uuid from '../helpers/uuid'

    export default {
        data () {
            return {
                editedNote: null,
                editBoxHeight: 100,
                severityClasses: [
                    'alert-success',
                    'alert-info',
                    'alert-warning',
                    'alert-danger'
                ],
            }
        },
        // watch: {
        //     notes: {
        //         handler: _.debounce(function (e) {
        //             this.notesSync(e)
        //         }, 500),
        //         deep: true
        //     }
        // },
        computed: {
            notes: {
                get() { return this.$store.state.notes },
                set(value) { this.$store.commit('notesSync', value) }
            },
            ...mapState({
                order: state => state.notesOptions.order,
            }),
            minHeight () {
                return this.editBoxHeight < 100 ? 100 : this.editBoxHeight
            },
            filteredNotes () {
                return this.optionalSortFilter(this.notes)
            }

        },
        methods: {
            ...mapMutations([
                'noteAdd',
                'noteDelete',
                'notesSync'
            ]),
            addNote () {
                let key = uuid.generate()

                this.noteAdd ({
                    body: "",
                    severity: 2,
                    key: key,
                    creation_date: moment().format("YYYYMMDDHHmmss")
                })

                this.$nextTick(function () {
                    this.editNote(this.getNoteByUuid(key))
                })
            },
            editNote (note) {

                let index = this.notes.indexOf(note)

                if(typeof this.$refs.myNotes[index] !== "undefined") {
                    this.editBoxHeight = (this.$refs.myNotes[index].clientHeight * .8)
                } else {
                    this.editBoxHeight = 75
                }

                this.beforeEditCache = note.body
                this.editedNote = note
            },
            doneEdit (note) {
                if (!this.editedNote) {
                    return
                }
                this.editedNote = null
                note.body = note.body.trim()
                if (!note.body) {
                    this.noteDelete(this.notes.indexOf(note))
                }
            },
            cancelEdit (note) {
                this.editedNote = null
                note.body = this.beforeEditCache
            },
            severityClass(index) {
                return this.severityClasses[(index -1)]
            },
            optionalSortFilter (list) {
                if(this.order) {
                    return list.sort((a, b) => b.severity - a.severity)
                } else {
                    return list.sort((a, b) => b.creation_date - a.creation_date)
                }
            },
            getNoteByUuid(key) {

                return this.notes.filter(note => note.key == key).shift()
            }
        },
        directives: {
            'note-focus' (el, value) {
                if (value) {
                    el.focus()
                }
            }
        },

    }
</script>
