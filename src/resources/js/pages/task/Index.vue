<template>
    <div>
        <ul class="horizontal-list">
            <li class="task-list" v-for="(list, index) in lists" :key="index">
                <task-list :task-list="list" @clickAddTask="clickAddTask" @change="change"/>
            </li>
            <li class="task-list">
                <v-btn text
                       outlined
                       block
                       class="add-list-container"
                       color="primary"
                       @click="isDisplayInputName = !isDisplayInputName"
                >
                    <span v-show="!isDisplayInputName">
                        <v-icon>mdi-plus-circle-outline</v-icon>
                        リストを追加する
                    </span>
                    <span v-show="isDisplayInputName">
                        <v-icon>mdi-minus-circle-outline</v-icon>
                        キャンセル
                    </span>
                </v-btn>
                <v-text-field v-show="isDisplayInputName" v-model="name" placeholder="タスクリスト名"/>
                <v-btn v-show="isDisplayInputName"
                       color="primary"
                       text
                       outlined
                       class="float-right"
                       @click="clickAddList"
                >
                    <v-icon>mdi-plus-circle-outline</v-icon>
                    追加
                </v-btn>
            </li>
        </ul>
    </div>
</template>

<script>
    import mixin from "../../mixins/mixin";

    export default {
        name: "Index",
        mixins: [mixin],
        data() {
            return {
                isDisplayInputName: false,
                name: '',
                lists: [],
            }
        },
        async created() {
            await this.getTaskLists();
        },
        methods: {
            click() {
                const loader = this.$store.getters['loader/loader'];
                this.$store.dispatch('loader/setLoader', !loader);
            },
            async getTaskLists() {
                await this.$store.dispatch('loader/setLoader', true);

                const res = await this.api('get', 'api/task_list', {});

                if (res.status === 200) {
                    for (let i in res.data) {
                        this.lists.push({
                            id: res.data[i].id,
                            name: res.data[i].name,
                            tasks: res.data[i].tasks,
                        });
                    }
                } else if (res.status === 422) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
                    await this.$store.dispatch('snackbar/setColor', 'error');
                }

                await this.$store.dispatch('loader/setLoader', false);
            },
            async clickAddList() {
                await this.$store.dispatch('loader/setLoader', true);

                const params = new FormData();
                params.append('name', this.name);

                const res = await this.api('post', '/api/task_list', params);

                if (res.status === 200) {
                    this.lists.push({
                        id: res.data.id,
                        name: res.data.name,
                    });
                } else if (res.status === 422) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
                    await this.$store.dispatch('snackbar/setColor', 'error');
                }

                await this.$store.dispatch('loader/setLoader', false);
            },
            async clickAddTask(taskList, task) {
                await this.$store.dispatch('loader/setLoader', true);

                const params = new FormData();
                params.append('task_list_id', taskList.id)
                params.append('name', task.name)

                const res = await this.api('post', '/api/task', params);

                if (res.status === 200) {
                    for (let i in this.lists) {
                        if (this.lists[i].id === res.data.task_list_id) {
                            this.lists[i].tasks.push({
                                id: res.data.id,
                                task_list_id: res.data.task_list_id,
                                name: res.data.name,
                            })
                        }
                    }
                } else if (res.status === 422) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
                    await this.$store.dispatch('snackbar/setColor', 'error');
                }

                await this.$store.dispatch('loader/setLoader', false);
            },
            async change(task) {
                await this.$store.dispatch('loader/setLoader', true);

                const params = new FormData();
                params.append('task_list_id', task.task_list_id);
                params.append('name', task.name);
                params.append('status', task.status);

                const res = await this.api('put', '/api/task/' + task.id, params);

                if (res.status === 200) {
                } else if (res.status === 422) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
                    await this.$store.dispatch('snackbar/setColor', 'error');
                }

                await this.$store.dispatch('loader/setLoader', false);
            }
        }
    }
</script>

<style scoped>
    .horizontal-list {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        margin: 0;
        padding: 0;
    }

    .task-list {
        display: inline-block;
        vertical-align: top;
        height: 100vh;
        margin: 0;
        padding: 1rem;
    }

    .card-max-height {
        /* TODO: ここのカード高は使ってみて決める */
        /* height: 80vh; */
    }

    .add-list-container {
        border: dashed 2px #000000;
    }

    @media screen and (min-width: 375px) and (max-width: 850px) {
        .task-list {
            width: 90%;
        }
    }

    @media screen and (min-width: 851px) and (max-width: 920px) {
        .task-list {
            width: 60%;
        }
    }

    @media screen and (min-width: 921px) {
        .task-list {
            width: 40%;
        }
    }
</style>
