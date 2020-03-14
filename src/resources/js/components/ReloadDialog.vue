<template>
    <v-dialog
        v-model="dialog"
        width="500"
        persistent
    >
        <v-card>
            <v-card-title
                primary-title
            >
                サーバー通信に失敗しました
            </v-card-title>

            <v-divider/>

            <v-card-text>
                サーバー通信に失敗しました。<br>
                再度ログインするか、リロードして下さい。
            </v-card-text>

            <v-divider/>

            <v-card-actions>
                <v-btn
                    color="primary"
                    text
                    outlined
                    @click="clickLogin"
                >
                    ログイン
                </v-btn>
                <v-spacer/>
                <v-btn
                    color="success"
                    text
                    outlined
                    @click="clickReload"
                >
                    リロード
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
        },
        methods: {
            clickLogin() {
                this.$router.push('login');
                this.$store.dispatch('reloadDialog/setReloadDialog', false);
            },
            clickReload() {
                this.$store.dispatch('reloadDialog/setReloadDialog', false);
                this.$store.dispatch('loader/setLoader', true)
                this.$router.go({
                    path: this.$router.currentRoute.path, force: true
                });
            }
        }
    }
</script>

<style scoped>

</style>
