<template>
    <v-card class="card-max-height">

        <v-card-title>{{taskList.name}}</v-card-title>

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
                    <v-list-item v-for="(list, index) in taskList" :key="index" @click="">
                        <v-list-item-title>{{list.name + index}}</v-list-item-title>
                    </v-list-item>
                </v-list>

            </v-menu>

            <v-textarea
                v-show="isAdd === true"
                v-model="taskName"
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
                this.$emit('clickAddTask', this.taskList, task);
            },
            change(id, status) {
                for (let i in this.taskList.tasks) {
                    if (this.taskList.tasks[i].id === id) {
                        this.taskList.tasks[i].status = status;
                    }
                }
            },
        }
    }
</script>

<style scoped>
    .card-max-height {
        /* TODO: ここのカード高は使ってみて決める */
        /* height: 80vh; */
    }
</style>
