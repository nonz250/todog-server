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
                detail: false,
                operationLists: [
                    {
                        name: '削除',
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
                    this.$emit('delete', this.task);
                } else {
                    console.error('この操作はありません。');
                }
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
