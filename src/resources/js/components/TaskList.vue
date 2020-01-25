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

                        <task-list-item :task="task" local-class="draggable-item"/>

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
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                    {value: 1, name: 'ここにタスクが表示される'},
                ],
                task: '',
            }
        },
        methods: {
            clickAddTask() {
                this.tasks.push({value: 1, name: this.task});
                this.isAdd = false;
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