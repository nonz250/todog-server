<template>
    <v-list-item
        :class="listClass"
    >
        <v-list-item-action>
            <v-checkbox v-model="checked" @change="change"/>
        </v-list-item-action>

        <v-list-item-content>
            {{task.name}}
        </v-list-item-content>

        <v-list-item-action>
            <v-menu
                v-model="detail"
                transition="slide-x-transition"
                absolute
                left
                bottom
            >
                <template v-slot:activator="{ on }">
                    <v-scroll-x-transition>
                        <v-icon v-if="task.status === 1" color="success">mdi-check</v-icon>
                    </v-scroll-x-transition>
                    <v-btn icon v-on="on">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="(operation, index) in operationLists"
                        :key="index"
                        @click="clickOperation(operation.operation)"
                    >
                        <v-list-item-title :class="getItemClass(operation.color)">{{operation.name}}</v-list-item-title>
                    </v-list-item>
                </v-list>

            </v-menu>

        </v-list-item-action>

        <dialog-component :dialog="dialog"
                          :title="title"
                          :ok="ok"
                          :cancel="cancel"
                          @ok="clickDelete"
                          @cancel="dialog = false"
        >
            <div v-html="text"></div>
        </dialog-component>

    </v-list-item>
</template>

<script>
    import tasks from "../app/tasks";

    export default {
        name: "TaskListItem",
        props: {
            task: {
                type: Object,
                default: {}
            },
            localClass: {
                type: String,
                default: '',
            }
        },
        data() {
            return {
                dialog: false,
                title: '',
                text: '',
                ok: {color: 'default', text: ''},
                cancel: {color: 'default', text: 'キャンセル'},
                detail: false,
                operationLists: [
                    {
                        name: 'タスクを編集',
                        color: '',
                        operation: 'edit'
                    },
                    {
                        name: 'タスクを削除',
                        color: 'error',
                        operation: 'delete'
                    }
                ],
            }
        },
        computed: {
            listClass() {
                let result = {};
                result[this.localClass] = true;
                if (this.checked) {
                    result['bg-color-checked'] = true;
                }
                return result;
            },
            checked: {
                get() {
                    return this.task.status === tasks.STATUS_COMPLETED
                },
                set(value) {
                    this.task.status = value ? tasks.STATUS_COMPLETED : tasks.STATUS_DEFAULT
                }
            }
        },
        methods: {
            change() {
                this.task.status = this.checked ? tasks.STATUS_COMPLETED : tasks.STATUS_DEFAULT;
                this.$emit('change', this.task)
            },
            clickOperation(operation) {
                if (operation === 'delete') {
                    this.dialog = true;
                    this.title = 'タスク削除';
                    this.text = 'タスクを削除しますか？<br>この操作は元には戻せません。';
                    this.ok.text = '削除';
                    this.ok.color = 'error';
                } else {
                    console.error('この操作はありません。');
                }
            },
            clickDelete() {
                this.dialog = false;
                this.$emit('delete', this.task);
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
    .bg-color-checked {
        background-color: #cccccc;
    }
</style>
