<template>
    <div class=" notesapp">
        <div class='row bottom-buffer '>
            <div class='col-xs-12 '>
                <div class="btn btn-success btn-xs pull-right" @click="addBookmark"><i class="fa fa-plus" ></i></div>
            </div>
        </div>

        <div class="note-list">

            <div v-for="(bookmark, index) in filteredBookmarks"
                 class="alert"
                 :class="[
                    severityClass(bookmark.severity),
                    {'editing': bookmark == editedBookmark }
                ]"
                 @dblclick="editBookmark(bookmark)"
                 role="alert"
                 ref="myBookmark">

                <span class='view'>
                    <a v-bind:href="bookmark.body ">{{ bookmark.body  }}</a>
                    <!--<a href= "bookmark.body">{{ bookmark.body }} </a>-->
                </span>

                <textarea
                        class="edit"
                        v-model="bookmark.body"
                        v-bookmark-focus="bookmark == editedBookmark"
                        @blur="doneEdit(bookmark)"
                        @keyup.enter="doneEdit(bookmark)"
                        @keyup.esc="cancelEdit(bookmark)"
                        :style="'height:'+ minHeight +'px'">
                </textarea>

                <div class="button-bar">

                    <div class="btn-group pull-right button-bar" role="group">

                        <input-btn-rank
                                v-model="bookmark.severity">
                        </input-btn-rank>

                        <button
                                type="button"
                                class="btn btn-default btn-xs"
                                @click="editBookmark(bookmark)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button
                                type="button"
                                class="btn btn-xs btn-danger"
                                @click="bookmarkDelete(index)">
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
                editedBookmark: null,
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
            bookmarks: {
                get() { return this.$store.state.bookmarks },
                set(value) { this.$store.commit('bookmarksSync', value) }
            },
            ...mapState({
                order: state => state.bookmarksOptions.order,
            }),
            minHeight () {
                return this.editBoxHeight < 100 ? 100 : this.editBoxHeight
            },
            filteredBookmarks () {
                return this.optionalSortFilter(this.bookmarks)
            }

        },
        methods: {
            ...mapMutations([
                'bookmarkAdd',
                'bookmarkDelete',
                'bookmarksSync'
            ]),
            addBookmark () {
                let key = uuid.generate()

                this.bookmarkAdd ({
                    body: "",
                    severity: 2,
                    key: key,
                    creation_date: moment().format("YYYYMMDDHHmmss")
                })

                this.$nextTick(function () {
                    this.editBookmark(this.getBookmarkByUuid(key))
                })
                console.log("add_bookmark_ran successfully")
            },
            editBookmark (bookmark) {

                let index = this.bookmarks.indexOf(bookmark)
                console.log("index",index);
                console.log("index",index,this.$refs.myBookmark[index]);

                if(typeof this.$refs.myBookmark[index] !== "undefined") {
                    this.editBoxHeight = (this.$refs.myBookmark[index].clientHeight * .8)
                } else {
                    this.editBoxHeight = 75
                }

                this.beforeEditCache = bookmark.body
                this.editedBookmark = bookmark
            },
            doneEdit (bookmark) {
                if (!this.editedBookmark) {
                    return
                }
                this.editedBookmark = null
                bookmark.body = bookmark.body.trim()
                if (!bookmark.body) {
                    this.bookmarkDelete(this.bookmarks.indexOf(bookmark))
                }
            },
            cancelEdit (bookmark) {
                this.editedBookmark = null
                bookmark.body = this.beforeEditCache
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
            getBookmarkByUuid(key) {
                console.log("getBookmarkByUuid(key)", key);

                return this.bookmarks.filter(bookmark => bookmark.key == key).shift()
            }
        },
        directives: {
            'bookmark-focus' (el, value) {
                if (value) {
                    el.focus()
                }
            }
        },

    }
</script>
