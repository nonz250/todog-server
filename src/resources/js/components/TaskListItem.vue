<template>
    <v-list-item
        :class="listClass"
    >
        <v-list-item-action>
            <v-checkbox v-model="checked" @change="change"/>
        </v-list-item-action>

        <v-list-item-content>{{task.name}}</v-list-item-content>

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

        <v-dialog
            v-model="formDialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <v-card tile>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="formDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>タスクを編集</v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn dark text @click="clickUpdate">更新</v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-list three-line subheader>
                    <v-subheader>タスク情報</v-subheader>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>タスク</v-list-item-title>
                            <v-list-item-subtitle>
                                <v-textarea
                                    v-model="dialogTaskName"
                                    ref="edit-task"
                                    label="タスク"
                                    rows="2"
                                    maxlength="255"
                                    counter
                                    full-width
                                    auto-grow
                                />
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <!--                    <v-list-item>-->
                    <!--                        <v-list-item-content>-->
                    <!--                            <v-list-item-title>タスク期限</v-list-item-title>-->
                    <!--                            <div class="width: 290px;">-->
                    <!--                                <v-date-picker-->
                    <!--                                    v-model="limitDate"-->
                    <!--                                    full-width-->
                    <!--                                    landscape-->
                    <!--                                    locale="ja"-->
                    <!--                                    :day-format="date => new Date(date).getDate()"-->
                    <!--                                />-->
                    <!--                            </div>-->
                    <!--                        </v-list-item-content>-->
                    <!--                    </v-list-item>-->
                </v-list>
            </v-card>
        </v-dialog>

    </v-list-item>
</template>

<script>
    import tasks from "../app/tasks";
    import dayjs from "dayjs";

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
                formDialog: false,
                title: '',
                text: '',
                dialogTaskName: '',
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
                limitDate: dayjs().format('YYYY-MM-DD'),
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
                } else if (operation === 'edit') {
                    this.dialogTaskName = this.task.name;
                    this.formDialog = true;
                    const vm = this;
                    this.$nextTick(() => {
                        vm.$refs['edit-task'].focus();
                    });
                } else {
                    console.error('この操作はありません。');
                }
            },
            clickUpdate() {
                this.formDialog = false;
                this.task.name = this.dialogTaskName;
                this.$emit('update', this.task);
            },
            clickDelete() {
                this.dialog = false;
                this.$emit('delete', this.task);
            },
            getItemClass(color) {
                return {
                    'red--text': color === 'error',
                }
            },
        }
    }
</script>

<style scoped>
    .bg-color-checked {
        background-color: #cccccc;
    }
</style>
