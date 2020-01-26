<template>
    <div>
        <ul class="horizontal-list">
            <li class="task-list" v-for="(list, index) in lists">
                <task-list :task-list="list" @clickAddTask="clickAddTask"/>
            </li>
            <li class="task-list">
                <v-btn text outlined block class="add-list-container" color="primary" @click="clickAddList">
                    <v-icon>mdi-plus-circle-outline</v-icon>
                    リストを追加する
                </v-btn>
            </li>
        </ul>
    </div>
</template>

<script>
    import mixin from "../../mixins/mixin";

    export default {
        name: "Index",
        mixins:[mixin],
        created() {
        },
        data() {
            return {
                lists: [
                    {value: 1, name: 'タスクリストの設定項目'},
                    {value: 1, name: 'タスクリストの設定項目'},
                    {value: 1, name: 'タスクリストの設定項目'},
                ],
            }
        },
        created() {
        },
        methods: {
            click() {
                const loader = this.$store.getters['loader/loader'];
                this.$store.dispatch('loader/setLoader', !loader);
            },
            clickAddList() {
                this.lists.push({
                    value: 1,
                    name: 'タスクリストの設定項目'
                });
            },
            clickAddTask(task){
                const res = this.api('post', '/api/task', task);
                if (res.status === 200) {
                    // this.variant = 'primary'
                    // this.snackbarText = this.getMessages(res.data.messages)
                    // this.snackbar = true
                    // this.users.unshift(res.data.users)
                    alert(this.getMessages(res.data.messages))
                } else if (res.status === 422) {
                    // this.variant = 'error'
                    // this.snackbarText = this.getMessages(res.data.errors)
                    // this.snackbar = true
                    alert(this.getMessages(res.data.errors))
                } else {
                    // this.variant = 'error'
                    // this.snackbarText = this.getMessages([res.data.message])
                    // this.snackbar = true
                    alert(this.getMessages([res.data.message]))
                }

                // this.tasks.push(task);
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
