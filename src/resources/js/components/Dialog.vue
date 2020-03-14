<template>
    <v-dialog
        v-model="dialog"
        width="500"
    >
        <v-card>
            <v-card-title
                primary-title
            >
                {{title}}
            </v-card-title>

            <v-divider/>

            <v-card-text>
                <slot></slot>
            </v-card-text>

            <v-divider/>

            <v-card-actions>
                <v-spacer/>
                <v-btn
                    :color="cancel.color"
                    text
                    outlined
                    @click="clickCancel"
                >
                    {{cancel.text}}
                </v-btn>
                <v-btn
                    :color="ok.color"
                    text
                    outlined
                    :disabled="disabled"
                    @click="clickOk"
                >
                    {{ok.text}}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import mixin from "../mixins/mixin";

    export default {
        name: "Dialog",
        mixins: [mixin],
        props: {
            dialog: {
                type: Boolean,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            ok: {
                type: Object,
                default: function () {
                    return {color: 'default', text: 'OK'};
                }
            },
            cancel: {
                type: Object,
                default: function () {
                    return {color: 'default', text: 'キャンセル'};
                }
            },
            disabled: {
                type: Boolean,
                default: function () {
                    return false;
                }
            }
        },
        methods: {
            clickOk() {
                this.$emit('ok', this.dialog)
            },
            clickCancel() {
                this.$emit('cancel', false)
            }
        }
    }
</script>

<style scoped>

</style>
