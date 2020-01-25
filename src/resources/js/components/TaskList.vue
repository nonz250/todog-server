<template>
    <v-card class="card-max-height">

        <v-card-title>{{taskList.name}}</v-card-title>

        <v-divider/>

        <v-card-text>

            <v-list>

                <draggable
                    v-model="tasks"
                    group="group"
                    @start="drag=true"
                    @end="drag=false"
                    :options="{animation: 200}"
                >
                    <div v-for="(task, index) in tasks" :key="index">

                        <task-list-item :task="task" local-class="draggable-item" @change="change"/>

                        <v-divider/>

                    </div>

                </draggable>

            </v-list>

        </v-card-text>

        <v-card-actions>

            <v-spacer v-show="isAdd === false"/>

            <v-btn
                v-show="isAdd === false"
                text
                outlined
                @click="isAdd = true"
            >
                <v-icon>mdi-plus-circle-outline</v-icon>
                タスクを追加する
            </v-btn>

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
                        v-on="on"
                    >
                        <v-icon>mdi-dots-horizontal</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="(list, index) in lists" :key="index" @click="">
                        <v-list-item-title>{{list.name + index}}</v-list-item-title>
                    </v-list-item>
                </v-list>

            </v-menu>

            <v-textarea
                v-show="isAdd === true"
                v-model="task"
                class="float-left"
                label="タスク"
                rows="2"
                maxlength="255"
                counter
                full-width
            />

            <v-btn
                v-show="isAdd === true"
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

    export default {
        name: "TaskList",
        components: {draggableComponent},
        props: {
            taskList: {
                type: Object,
                default: {},
            },
        },
        data() {
            return {
                isAdd: false,
                detail: false,
                lists: [
                    {value: 1, name: 'タスクリストの設定項目'},
                    {value: 1, name: 'タスクリストの設定項目'},
                    {value: 1, name: 'タスクリストの設定項目'},
                ],
                tasks: [
                    {id: 1, name: 'ここにタスクが表示される', status: 0},
                    {id: 2, name: 'ここにタスクが表示される', status: 1},
                    {id: 3, name: 'ここにタスクが表示される', status: 1},
                    {id: 4, name: 'ここにタスクが表示される', status: 1},
                    {id: 5, name: 'ここにタスクが表示される', status: 0},
                    {id: 6, name: 'ここにタスクが表示される', status: 1},
                    {id: 7, name: 'ここにタスクが表示される', status: 1},
                    {id: 8, name: 'ここにタスクが表示される', status: 0},
                    {id: 9, name: 'ここにタスクが表示される', status: 0},
                ],
                task: '',
            }
        },
        methods: {
            clickAddTask() {
                this.tasks.push({value: 1, name: this.task});
                this.isAdd = false;
            },
            change(id, status) {
                for (let i in this.tasks) {
                    if (this.tasks[i].id === id) {
                        console.log('before status change --> ' + status)
                        this.tasks[i].status = status;
                        console.log('after status change --> ' + this.tasks[i].status)
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .card-max-height {
        /* TODO: ここのカード高は使ってみて決める */
        /* height: 80vh; */
    }
</style>
