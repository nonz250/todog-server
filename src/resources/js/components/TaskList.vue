<template>
    <v-card>

        <v-card-title>
            <v-row>
                <v-col style="margin: 0 0 0 1rem; padding: 0;">{{taskList.name}}</v-col>
                <v-col class="my-0 py-0">
                    <v-menu
                        v-show="isAdd === false"
                        v-model="detail"
                        transition="slide-x-transition"
                        absolute
                        left
                        bottom
                    >
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-show="isAdd === false"
                                text
                                icon
                                class="float-right"
                                v-on="on"
                            >
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item
                                v-for="(operation, index) in operationLists"
                                :key="index"
                                @click="clickOperation(operation.operation)"
                            >
                                <v-list-item-title :class="getItemClass(operation.color)">{{operation.name}}
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>

                    </v-menu>
                </v-col>
            </v-row>
        </v-card-title>

        <v-divider/>

        <v-card-text>

            <v-list>

                <draggable
                    v-model="taskList.tasks"
                    group="group"
                    @start="drag=true"
                    @end="drag=false"
                    :options="{animation: 200}"
                    :disabled="!enabled"
                >
                    <div v-for="(task, index) in taskList.tasks" :key="index">

                        <task-list-item :task="task" local-class="draggable-item" @change="change"
                                        @delete="deleteTask"/>

                        <v-divider/>

                    </div>

                </draggable>

            </v-list>

            <div class="text-right">
                <v-btn
                    text
                    outlined
                    :color="isAdd ? 'error' : 'primary'"
                    @click="clickEnabledTaskForm"
                >
                    <v-icon v-show="!isAdd">mdi-plus-circle-outline</v-icon>
                    <span v-show="!isAdd">タスクを追加する</span>

                    <v-icon v-show="isAdd">mdi-minus-circle-outline</v-icon>
                    <span v-show="isAdd">キャンセル</span>
                </v-btn>
            </div>

        </v-card-text>

        <v-card-actions v-show="isAdd">

            <v-textarea
                v-model="taskName"
                ref="input-task"
                class="float-left"
                label="タスク"
                rows="2"
                maxlength="255"
                counter
                full-width
                auto-grow
            />

            <v-btn
                color="success"
                class="float-left"
                text
                outlined
                @click="clickAddTask"
            >
                <v-icon>mdi-plus-circle-outline</v-icon>
                追加
            </v-btn>

        </v-card-actions>
    </v-card>

</template>

<script>
    import draggableComponent from 'vuedraggable';
    import isMobile from 'ismobilejs';

    export default {
        name: "TaskList",
        components: {draggableComponent},
        props: {
            taskList: {
                type: Object,
                required: true,
                default: {},
            }
        },
        data() {
            return {
                isAdd: false,
                detail: false,
                enabled: true,
                taskName: '',
                operationLists: [
                    {
                        name: '削除',
                        color: 'error',
                        operation: 'delete'
                    }
                ],
            }
        },
        created() {
            this.enabled = !isMobile(navigator.userAgent).any
        },
        methods: {
            clickAddTask() {
                this.isAdd = false;
                const task = {
                    id: null,
                    name: this.taskName,
                };
                this.taskName = '';
                this.$emit('clickAddTask', this.taskList, task);
            },
            clickEnabledTaskForm() {
                this.isAdd = !this.isAdd;
                const vm = this;
                this.$nextTick(() => {
                    vm.$refs['input-task'].focus();
                });
            },
            clickOperation(operation) {
                if (operation === 'delete') {
                    this.$emit('delete-list', this.taskList);
                } else {
                    console.error('この操作はありません。');
                }
            },
            change(task) {
                this.$emit('change', task)
            },
            deleteTask(task) {
                this.$emit('delete-task', task);
            },
            getItemClass(color) {
                return {
                    'red--text': color === 'error',
                }
            }
        }
    }
</script>

<style scoped>
    .draggable-item:hover {
        cursor: grab;
    }

    .draggable-item:active {
        cursor: grabbing;
    }
</style>
