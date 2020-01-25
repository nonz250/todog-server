<template>
    <v-list-item
        :class="localClass"
    >
        <v-list-item-action>
            <v-checkbox/>
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
