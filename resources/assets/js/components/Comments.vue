<template>
    <div class=" notesapp">
        {{this.$store.state.selectedTaskKey?this.$store.state.selectedTaskKey:''}}
        <div class='row bottom-buffer '>
            <div class='col-xs-12 '>
                <div class="btn btn-success btn-xs pull-right" @click="addComment"  v-show="checkToShow"><i class="fa fa-plus" ></i></div>
                <span class='view'>{{checkToShowMsg}}</span>
            </div>
        </div>

        <div class="note-list">

            <div v-for="(comment, index) in filteredComments"
                 class="alert"
                 :class="[
                    severityClass(comment.severity),
                    {'editing': comment == editedComment }
                ]"
                 @dblclick="editComment(comment)"
                 role="alert"
                 ref="myComment">

                <span class='view'>
                    <!--<a v-bind:href=".body ">{{ bookmark.body  }}</a>-->
                    {{ comment.body }}
                </span>

                <textarea
                        class="edit"
                        v-model="comment.body"
                        v-comment-focus="comment == editedComment"
                        @blur="doneEdit(comment)"
                        @keyup.enter="doneEdit(comment)"
                        @keyup.esc="cancelEdit(comment)"
                        :style="'height:'+ minHeight +'px'">
                </textarea>

            </div>

        </div>

        <div class='row'>
            <div class="alert alert-warning alert-dismissable width-94" v-show="notify!=''?true:false">
                <a href="#" class="close" aria-label="close" @click="setMessageToDefault">×</a>
                {{ notify }}
            </div>
        </div>

    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'
    import * as uuid from '../helpers/uuid'
    import axios from 'axios'
    import VueAxios from 'vue-axios'

    Vue.use(VueAxios, axios)
  //  var csrf_token = $('meta[name="csrf-token"]').attr('content');

    export default {
        data () {
            return {
                editedComment: null,
                editBoxHeight: 100,
                severityClasses: [
                    'alert-success',
                    'alert-info',
                    'alert-warning',
                    'alert-danger'
                ],
                commentMsg: ''
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
            comments: {
                get() { return this.$store.state.comments },
                set(value) { this.$store.commit('commentsSync', value) }
            },
            ...mapState({
                order: state => state.commentsOptions.order,
            }),
            minHeight () {
                return this.editBoxHeight < 100 ? 100 : this.editBoxHeight
            },
            filteredComments () {
                return this.optionalSortFilter(this.comments)
            },
            checkToShow () {
                if(this.$store.state.selectedTaskKey == ''){
                    return false
                }
                return true;
            },
            checkToShowMsg () {
                if(this.$store.state.selectedTaskKey == ''){
                    let message = "Comments can only be added for JIRA Tasks"
                    return message;
                }
            },
            notify: function () {
                return this.commentMsg

            },
        },
        methods: {
            ...mapMutations([
                'commentAdd',
                'commentDelete',
                'commentsSync'
            ]),

            addComment () {
                let key = uuid.generate()

                this.commentAdd ({
                    body: "",
                    task_key: this.$store.state.selectedTaskKey,
                    severity: 2,
                    key: key,
                    creation_date: moment().format("YYYYMMDDHHmmss")
                })

                this.$nextTick(function () {
                    this.editComment(this.getCommentByUuid(key))
                })
                console.log("add_bookmark_ran successfully")
            },
            editComment(comment) {

                let index = this.comments.indexOf(comment)
                console.log("index",index);
                console.log("index",index,this.$refs.myComment[index]);

                if(typeof this.$refs.myComment[index] !== "undefined") {
                    this.editBoxHeight = (this.$refs.myComment[index].clientHeight * .8)
                } else {
                    this.editBoxHeight = 75
                }

                this.beforeEditCache = comment.body
                this.editedComment = comment
            },
            doneEdit (comment) {
                if (!this.editedComment) {
                    return
                }
                this.editedComment = null
                comment.body = comment.body.trim()
                this.commentMsg = ''
                let api='/add/comment/'
                this.axios.post(api, {
                    'comment_body': comment.body,
                    'task_key': comment.task_key
                }).then((response) => {
                    if(response.data.author != undefined){
                        this.commentMsg = "Comment added to task successfully"
                        setTimeout(() => {
                            this.commentMsg = ''
                        }, 3000)
                    }else{
                        this.commentMsg = "Comment failed to add"
                        this.commentDelete(this.comments.indexOf(comment))
                    }
                })

                if (!comment.body) {
                    this.commentDelete(this.comments.indexOf(comment))
                }
            },
            cancelEdit (comment) {
                this.editedComment = null
                comment.body = this.beforeEditCache
            },
            severityClass(index) {
                return this.severityClasses[(index -1)]
            },
            optionalSortFilter (list) {
                let i=0;
                let filteredComments=[]
                let comments=this.$store.state.comments
                for(i=0;i<comments.length;i++){
                    if(this.$store.state.selectedTaskKey!=''&&
                        comments[i].task_key==this.$store.state.selectedTaskKey){
                        filteredComments.push(comments[i])
                    }
                }
                list=filteredComments;
                if(this.order) {
                    return list.sort((a, b) => b.severity - a.severity)
                } else {
                    return list.sort((a, b) => b.creation_date - a.creation_date)
                }
            },
            getCommentByUuid(key) {
                return this.comments.filter(comment => comment.key == key).shift()
            },
            setMessageToDefault() {
                this.commentMsg =''
            }
        },
        directives: {
            'comment-focus' (el, value) {
                if (value) {
                    el.focus()
                }
            }
        },

    }

</script>
