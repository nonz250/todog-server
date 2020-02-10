<template>
    <v-list-item
        :class="localClass"
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
                    <v-list-item v-for="(item, index) in lists" :key="index" @click="">
                        <v-list-item-title>タスクの設定項目{{index}}</v-list-item-title>
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
                lists: [{}, {}, {}, {}, {}],
            }
        },
        computed: {
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
            }
        }
    }
</script>

<style scoped>

</style>
