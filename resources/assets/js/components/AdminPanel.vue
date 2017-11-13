<template>
    <div class="oard admin-panel">
        <div class='row '>
            <div class="col-sm-12">
                <button class="btn btn-default btn-sm pull-right" @click="setAdminDisplayOff" :disabled="isDisabledClose">Close</button>
            </div>
        </div>
        <div class='row'>
            <div class="col-sm-4">
                <div class="panel panel-warning ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            Mob Settings
                        </h3>
                    </div>

                    <div class="panel-body">

                        <div class='row bottom-buffer'>
                            <div class="col-sm-12">
                                <div class="form-group tight-bottom">
                                    <label  for="mob-name">Mob Name</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="mob-name"
                                            v-model="name">
                                </div>
                                <div class="note">mob url: /mob-example</div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-sm-12">
                                <input-checkbox
                                        name='show_feature_name'
                                        v-model="featureNameOption">
                                    Check to Add JIRA Task.
                                </input-checkbox>

                                <div class="form-group tight-bottom margin-top-15" v-if="featureNameOption">
                                    <label  for="feature-name">Jira Task Id</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="feature-name"
                                            v-model="featureName">
                                    <button type="button"
                                            class="btn btn-primary btn-sm margin-top-15"
                                            v-on:click="getTaskDetailsFromJira"
                                            :disabled="isDisabledFetch">
                                        Fetch
                                    </button>

                                    <div class="alert alert-info margin-top-15" data-auto-dismiss="2000" v-show="notifyFetch!=''?true:false">
                                        <a href="#"
                                           class="close"
                                           aria-label="close"
                                           @click="setMessageToDefault">×</a>
                                        {{ notifyFetch }}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="panel panel-warning ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            Jira Settings
                        </h3>
                    </div>

                    <div class="panel-body">


                        <div class='row bottom-buffer'>
                            <div class="col-sm-12">
                                <div class="form-group tight-bottom">
                                    <label  for="jira-url">Jira URL</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="jira-url"
                                            v-model="url">
                                </div>
                            </div>
                        </div>
                        <div class='row bottom-buffer'>
                            <div class="col-sm-12">
                                <div class="form-group tight-bottom">
                                    <label  for="jira-user">Jira UserName</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="jira-user"
                                            v-model="username">
                                </div>

                            </div>
                        </div>
                        <div class='row bottom-buffer'>
                            <div class="col-sm-12">
                                <div class="form-group tight-bottom">
                                    <label  for="jira-password">Jira Password</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="jira-password"
                                            v-model="password">
                                </div>

                            </div>
                        </div>

                        <div class='row'>
                            <button type="button"
                                    class="btn btn-primary btn-sm margin-left-15"
                                    v-on:click="saveJiraConfiguration"
                                    :disabled="isDisabledSave"
                            >
                                Save</button>
                        </div>

                        <div class='row'>
                            <div class="alert alert-info alert-dismissable margin-top-15 width-94" v-show="notifySave!=''?true:false">
                                <a href="#" class="close" aria-label="close" @click="setMessageToDefault">×</a>
                                {{ notifySave }}
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <h3>Long Term Storange</h3>
                <p>
                    <input-checkbox
                            name='long-term'
                            v-model="save">
                        Use long term storage
                    </input-checkbox>
                </p>

                <confirm-alert
                        v-if="alerts.mobDeletionWarning"
                        @confirm="confirmMobDeletion"
                        buttonType="yesNo">If you turn off long term storage, anything currently saved will be removed. Do you want to permanently delete your long term storage?</confirm-alert>
                <confirm-alert v-if="alerts.mobNeedsName" @confirm="confirmMobName">You must have a mob name before you can use long term storage.</confirm-alert>
                <p>MobBoss is not responsible for loss of data.  Data is secure only to the extent needed by the MobBoss applicaiton.  Store sensitive information at your own risk.</p>
                <p v-if="save && slug">Report Page: <a :href="'/report/'+ slug"> /report/{{ slug }}</a></p>
            </div>
        </div>
        <div class='row'>
            <div class="col-sm-4">

                <div class="panel panel-warning ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            Timer Settings
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <div class='form-inline'>
                                <label for="timer-duration">Duration: </label>
                                <input
                                        type="text"
                                        class="form-control input-sm shrink-box"
                                        id="timer-duration"
                                        v-model="timerDuration">
                                minutes

                            </div>
                        </div>
                        <div class="form-group">
                            <input-checkbox
                                    name="flash-browser"
                                    v-model="flashBrowser">
                                Flash title when timer is up
                            </input-checkbox>
                        </div>
                        <div class="form-group">
                            <input-checkbox
                                    name="play-sound"
                                    v-model="playSound">
                                Play sound when timer is up
                            </input-checkbox>
                        </div>
                        <div class="form-group">
                            <div class='form-inline'>
                                <input-checkbox
                                        name="suggest-breaks"
                                        v-model="suggestBreaks">
                                    Suggest breaks after
                                    <input
                                            type="text"
                                            class="form-control input-sm shrink-box"
                                            name="typing-reps"
                                            v-model="typingReps"> timers finish

                                </input-checkbox>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <div class="panel panel-warning ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            Task Settings
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <input-checkbox
                                    name="tasks-severity"
                                    v-model="tasksBySeverity">
                                Order by severity
                            </input-checkbox>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-sm-4">

                <div class="panel panel-warning ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            Note Settings
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <input-checkbox
                                    name="notes-severity"
                                    v-model="notesBySeverity">
                                Order by severity
                            </input-checkbox>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class='row '>
            <div class="col-sm-12">
                <button class="btn btn-default btn-sm pull-right" @click="setAdminDisplayOff" :disabled="isDisabledClose">Close</button>
            </div>
        </div>

    </div>
</template>

<script>

    import { mapActions, mapMutations, mapState } from 'vuex'
    import Vue from 'vue'
    import axios from 'axios'
    import VueAxios from 'vue-axios'
    Vue.use(VueAxios, axios)

    export default {
        data() {
            return {
                alerts: {
                    mobNeedsName: false,
                    mobDeletionWarning: false,
                },
                messageSave: '',
                messageFetch: '',
                disabledSave: false,
                disabledFetch: false,
                disabledClose: false
            }
        },
        computed: {
            ...mapState({
                mobName: state => state.mobName,
                slug: state => state.slug,
            }),
            save: {
                get() { return this.$store.state.persist },
                set(value) {
                    if (value) {
                        if(this.name == "") {
                            this.alerts.mobNeedsName = true
                        } else {
                            this.$store.commit('persistOn')
                        }

                    } else {
                        this.alerts.mobDeletionWarning = true
                    }

                }
            },
            name: {
                get() { return this.mobName },
                set(value) { this.setMobName(value) }
            },
            featureNameOption: {
                get() { return this.$store.state.featureNameOption },
                set(value) {
                    this.$store.commit('setFeatureNameOption', value)
                    if (!value) {
                        this.$store.commit('setFeatureName', '')
                    }
                }
            },
            featureName: {
                get() { return this.$store.state.featureName },
                set(value) { this.$store.commit('setFeatureName', value) }
            },
            url: {
                get() { return this.$store.state.url },
                set(value) { this.$store.commit('setJiraUrl', value) }
            },
            username: {
                get() { return this.$store.state.username },
                set(value) { this.$store.commit('setJiraName', value) }
            },
            password: {
                get() { return this.$store.state.password },
                set(value) { this.$store.commit('setJiraPassword', value) }
            },
            taskDetails: {
                get() { return this.$store.state.jiraTasks },
                set(value) { this.$store.commit('setJiraTaskDetails', value) }
            },

            timerDuration: {
                get() { return this.$store.state.timerOptions.sessionLength },
                set(value) { this.$store.dispatch('timerSetTime', value) }
            },
            flashBrowser: {
                get() { return this.$store.state.timerOptions.flashBrowser },
                set(value) { this.$store.commit('timerToggleFlashBrowser', value) }
            },
            playSound: {
                get() { return this.$store.state.timerOptions.playSound },
                set(value) { this.$store.commit('timerTogglePlaySound', value) }
            },
            suggestBreaks: {
                get() { return this.$store.state.timerOptions.suggestBreaks },
                set(value) { this.$store.commit('timerToggleSuggestBreaks', value) }
            },
            typingReps: {
                get() { return this.$store.state.timerOptions.breakIntervals },
                set(value) { this.$store.commit('timerSetBreakIntervals', value) }
            },
            tasksBySeverity: {
                get() { return this.$store.state.tasksOptions.order },
                set(value) { this.$store.commit('tasksSetOrder', value) }
            },
            notesBySeverity: {
                get() { return this.$store.state.notesOptions.order },
                set(value) { this.$store.commit('notesSetOrder', value) }
            },
            notifySave: function () {
                return this.messageSave
            },
            notifyFetch: function () {
                return this.messageFetch
            },
            isDisabledSave: function () {
                return this.disabledSave
            },
            isDisabledFetch: function () {
                return this.disabledFetch
            },
            isDisabledClose: function(){
                return this.disabledClose
            }
        },
        methods: {
            ...mapMutations([
                'setAdminDisplayOff',
                'setMobName',
            ]),
            confirmMobName() {

                this.alerts.mobNeedsName = false

                if(this.name == "") {
                    this.$store.commit('persistOff')
                }
            },
            confirmMobDeletion(value) {
                this.alerts.mobDeletionWarning = false

                if(value) {
                    this.$store.dispatch('deleteMob')
                    this.$store.commit('persistOff')
                    this.$store.commit('createdOff')
                } else {
                    this.$store.commit('persistOn')
                }
            },

            getTaskDetailsFromJira(){
                let api='/issue/'+this.$store.state.featureName;
                this.messageFetch = ''
                this.disabledFetch = true
                this.disabledClose = true
                let tasks = this.$store.state.tasks;
                for(let i=0;i<tasks.length;i++){
                    if(tasks[i].task_key.toLowerCase()==this.$store.state.featureName.toLowerCase()){
                        this.messageFetch = "Jira task already exists."
                        this.disabledFetch = false
                        this.disabledClose = false
                        setTimeout(() => {
                            this.messageFetch = ''
                        }, 3000)
                        return;
                    }
                }

                this.axios.get(api).then((response) => {
                    let res = response.data
                    if (res == '') {
                        this.messageFetch = "Server Error or incorrect credentials."
                        this.$store.state.featureName=''
                        setTimeout(() => {
                            this.messageFetch = ''
                        }, 3000)
                    } else if (res.errorMessages) {
                        this.messageFetch = res.errorMessages[0];
                        this.$store.state.featureName=''
                    } else {
                        this.$store.commit('taskAdd', res)
                        this.messageFetch = "Jira task added successfully."
                        setTimeout(() => {
                            this.messageFetch = ''
                        }, 3000)
                    }
                    this.disabledFetch = false
                    this.disabledClose = false
                })


            },
            saveJiraConfiguration(){
                let api='/save-config';
                this. messageSave = ''
                this.disabledSave = true
                this.disabledClose = true
                this.axios.post(api,{
                    'url': this.$store.state.url,
                    'username': this.$store.state.username,
                    'password': this.$store.state.password
                }).then((response) => {
                    let res = response.data
                    if(res == 'ok') {
                        this.messageSave = "Jira credentials saved."
                        setTimeout(() => {
                            this.messageSave = ''
                        }, 3000)
                    } else {
                        this.messageSave = "Jira credentials failed to save."
                        setTimeout(() => {
                            this.messageSave = ''
                        }, 3000)
                    }
                    this.disabledSave = false
                    this.disabledClose = false
                })
            },
            setMessageToDefault() {
                this.messageFetch =''
                this.messageSave = ''
            }
        }
    }
</script>
