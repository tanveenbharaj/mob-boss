<template>
    <div class="tasksapp">

        <div class='row bottom-buffer'>
            <div class='col-xs-10'>
                <small>{{ remaining | pluralize }} remaining: {{ remaining }}</small>
            </div>
            <div class='col-xs-2'>
                <div class="btn btn-success btn-xs pull-right" @click="addTask"><i class="fa fa-plus" ></i></div>
            </div>
        </div>

        <ul class="task-list">
            <li
                v-for="(task, index) in filteredTasks"
                class="list-group-item"
                :class="[
                    severityClass(task.severity),
                    {
                        completed: task.completed,
                        editing: task == editedTask
                    }
                ]"
                ref="taskList">

                <div class="view">

                    <input-checkbox
                        :name="'task_'+index"
                        :value="task.completed"
                        @input="taskCompleted(task)">
                        {{ task.task_key==''?'':task.task_key+" : " }}{{ task.task_name }}
                    </input-checkbox>

                    <div class="btn-group pull-right button-bar" role="group" v-show="task.task_type == 'JIRA'?false:true">

                        <input-btn-rank
                            v-model="task.severity">
                        </input-btn-rank>
                        <button
                             type="button"
                             class="btn btn-default btn-xs"
                             @click="editTask(task)">
                                <i class="fa fa-pencil"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-xs btn-danger"
                            @click="taskDelete(index)">
                            <i class="fa fa-close"></i>
                        </button>

                    </div>


                    <div class="btn-group pull-right button-bar" role="group" v-show="task.task_type == 'JIRA'?true:false">
                        <button
                                 type="button"
                                 class="btn btn-default btn-xs"
                                 @click="selectTask(task)"
                        >
                            <i class="fa fa-pencil"></i>
                        </button>
                    </div>

                </div>

                <input class="edit" type="text"
                    v-model="task.task_name"
                    v-task-focus="task == editedTask"
                    @blur="doneEdit(task, index)"
                    @keyup.enter="doneEdit(task, index)"
                    @keyup.esc="cancelEdit(task)">

             </li>
        </ul>

        <div class='task-footer' v-show="tasks.length" v-cloak>

            <div class="btn-group visibility" role="group">
                <button
                    type="button"
                    @click="visibility = 'all'"
                    class="btn btn-sm"
                    :class="visibility == 'all' ? classActive : classStandard">
                        All
                </button>

                <button
                    type="button"
                    @click="visibility = 'active'"
                    class="btn btn-sm"
                    :class="visibility == 'active' ? classActive : classStandard">
                        Active
                </button>

                <button
                    type="button"
                    @click="visibility = 'completed'"
                    class="btn btn-sm"
                    :class="visibility == 'completed' ? classActive : classStandard">
                        Completed
                </button>
            </div>

            <div class="btn btn-success btn-xs btn-danger pull-right" @click="tasksRemoveCompleted"><i class="fa fa-trash" ></i></div>

        </div>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'
    import * as uuid from '../helpers/uuid'
    import bus from '../bus'


    export default {
        data () {
            return {
                addingTask: false,
                editedTask: null,
                visibility: 'all',
                classActive: {
                    'btn-primary' : true,
                    'active' : true
                },
                classStandard: {
                    'btn-default' : true,
                    'active' : false
                },
                severityClasses: [
                    'list-group-item-success',
                    'list-group-item-info',
                    'list-group-item-warning',
                    'list-group-item-danger'
                ],
            }
        },
        computed: {
            tasks: {
                get() { return this.$store.state.tasks },
                set(value) { this.$store.commit('tasksSync', value) }
            },
            ...mapState({
                order: state => state.tasksOptions.order,
            }),
            filteredTasks () {

                let tasks = this.optionalSortFilter(this.tasks)

                switch(this.visibility) {
                    case "all":
                        return tasks
                        break
                    case "active":
                        return tasks.filter(task => !task.completed)
                        break
                    case "completed":
                        return tasks.filter(task => task.completed)
                        break
                }
            },
            remaining () {
                return this.tasks.filter(task => !task.completed).length
            },

        },
        filters: {
            pluralize: function (n) {
                return n === 1 ? 'Task' : 'Tasks'
            }
        },
        methods: {
            ...mapMutations([
                'taskAdd',
                'taskDelete',
                'taskCompleted',
                'tasksRemoveCompleted',
                'tasksSync',
                'taskSelectedToAddComment'
            ]),
            addTask () {
                this.addingTask = true;
                let key = uuid.generate()

                this.taskAdd({
                    task_name: '',
                    task_type: '',
                    task_key: '',
                    completed: false,
                    severity: 2,
                    key: key,
                    creation_date: moment().format("YYYYMMDDHHmmss")
                })

                this.$nextTick(() =>  this.editTask(this.getTaskByUuid(key)) )
            },
            editTask (task) {
                this.beforeEditCache = task.task_name
                this.editedTask = task
            },
            doneEdit (task, index) {
                if (!this.editedTask) {
                    return
                }
                this.editedTask = null
                task.task_name = task.task_name.trim()

                if (!task.task_name) {
                    this.$store.commit('taskDelete', index)
                    this.addingTask = false;
                }

                if(this.addingTask) {
                    this.addTask()
                }
            },
            cancelEdit (task) {
                this.editedTask = null
                task.task_name = this.beforeEditCache
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
            getTaskByUuid(key) {
                return this.tasks.filter(task => task.key == key).shift()
            },
            selectTask(task){

                this.$store.commit('taskSelectedToAddComment', task)
                this.$store.commit('activateTab',!this.$store.state.isActive)
                bus.$emit('activateTab', true)
            }



        },
        directives: {
            'task-focus'  (el, value) {
                if (value) {
                    el.focus()
                }
            }
        },
    }
</script>
