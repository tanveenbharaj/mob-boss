<template>
    <div class='oard'>
        <div class='row bottom-buffer '>
            <div class="col-md-11">
                <h2 class="margin-manipulator-m" v-if="mobName || featureName">
                    <span v-if="!editMobName" @dblclick="editMobName = true">{{ mobName }} </span>

                    <input type="text"
                        class="header-edit"
                        v-focus
                        v-else
                        v-model="mobName"
                        @blur="editMobName = false"
                        @keyup.enter="editMobName = false"
                        @keyup.esc="editMobName = false" />

                    <span v-if="mobName && featureName">:</span>

                    <span v-if="featureName" @dblclick="editFeatureName = true">
                        <span v-if="!editFeatureName">{{ featureName }}</span>


                        <input type="text"
                            class="header-edit"
                            v-focus
                            v-else
                            v-model="featureName"
                            @blur="editFeatureName = false"
                            @keyup.enter="editFeatureName = false"
                            @keyup.esc="editFeatureName = false" />

                    </span>
                </h2>
            </div>
            <div class="col-md-1">
                <span class="fa fa-cogs pull-right btn-admin" @click="adminOn"></span>
            </div>
        </div>
        <div class='row'>
            <div class="col-md-3">

                <participants></participants>
            </div>

            <div class="col-md-3">
                <mob-timer></mob-timer>
            </div>

            <div class="col-md-6">

                <div class='tab-panel' >
                    <ul class="nav nav-tabs ">
                        <li v-bind:class
                                ="{ 'active': !tabClass }"  @click="unsetClassValues">
                            <a href="#tasks" data-toggle="tab">
                                <span class="fa fa-tasks"></span>
                                Tasks
                            </a>
                        </li>
                        <li>
                            <a href="#notes" data-toggle="tab">
                                <span class="fa fa-file" aria-hidden="true"></span>
                                Notes
                            </a>
                        </li>
                        <li>
                            <a href="#bookmarks" data-toggle="tab">
                                <span class="fa fa-file" aria-hidden="true"></span>
                                Bookmarks
                            </a>
                        </li>
                        <li v-bind:class
                                    ="{ 'active': tabClass }" v-show="show">
                            <a href="#comments" data-toggle="tab" >
                                <span class="fa fa-file" aria-hidden="true">  </span>
                                Comments
                            </a>
                        </li>

                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div id="tasks" v-bind:class
                                ="{ 'tab-pane fade': aClass, 'tab-pane fade active in': bClass  }"
                               >
                            <mob-tasks></mob-tasks>
                        </div>
                        <div class="tab-pane fade" id="notes">
                            <mob-notes></mob-notes>
                        </div>
                        <div class="tab-pane fade" id="bookmarks">
                            <mob-bookmarks></mob-bookmarks>
                        </div>
                        <div class="tab-pane fade" id="comments" v-bind:class
                                ="{ 'tab-pane fade': bClass, 'tab-pane fade active in': aClass  }" >
                            <mob-comments></mob-comments>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    import { mapMutations, mapState } from 'vuex'
    import bus from '../bus'

    export default {
        data () {
            return {
                editFeatureName: false,
                editMobName: false,
                tabClass: false,
                aClass: false,
                bClass: true,
                show: false

            }
        },
        created() {
            bus.$on('activateTab', (value) => {
                if(value==true) {
                    this.tabClass = true
                    this.aClass = true
                    this.bClass = false
                    this.show = true
                }
            })
        },
        computed: {
            mobName: {
                get() { return this.$store.state.mobName},
                set(value) { this.$store.commit('setMobName', value) }
            },
            featureName: {
                get() { return this.$store.state.featureName},
                set(value) { this.$store.commit('setFeatureName', value) }
            },

        },

        methods: {
            ...mapMutations({
                adminOn : 'setAdminDisplayOn',
            }),
            unsetClassValues(){
                this.tabClass = false
                this.aClass = false
                this.bClass = true
            }
        },
        directives: {
            focus: {
                inserted(el) {
                  el.focus()
                }
            }
        }
    }
</script>
