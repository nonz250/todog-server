<template>
    <v-app>
        <nav-component
            :is-logged-in="isLoggedIn"
        />
        <transition mode="out-in">
            <router-view/>
        </transition>
        <v-snackbar v-model="snackbar"
                    :color="this.$store.getters['snackbar/color']"
                    :top="this.$store.getters['snackbar/position'] === 'top'"
                    vertical
        >
            {{this.$store.getters['snackbar/text']}}
            <v-btn text @click="snackbar = false">閉じる</v-btn>
        </v-snackbar>
        <reload-dialog
            :dialog="reloadDialog"
        />
        <dialog-component
            :dialog="swDialog"
            title="アプリのアップデート"
            :ok="{color: 'success', text: '更新'}"
            @ok="updateApp"
            @cancel=""
        >
            <v-container>
                <p>アプリのバージョンが更新されています。</p>
                <p>更新してください。</p>
            </v-container>
        </dialog-component>
    </v-app>
</template>

<script>
    import mixin from "./mixins/mixin";
    import swController from "./src/swController";

    export default {
        name: "App.vue",
        mixins: [mixin],
        // data() {
        //     return {
        //         sw: null,
        //     };
        // },
        computed: {
            isLoggedIn() {
                return Object.keys(this.$store.getters['auth/user']).length > 0;
            },
            snackbar: {
                get() {
                    return this.$store.getters['snackbar/snackbar']
                },
                set(value) {
                    this.$store.dispatch('snackbar/setSnackbar', value)
                }
            },
            swDialog: {
                get() {
                    return this.$store.getters['swDialog/sw_dialog'];
                },
                set(value) {
                    this.$store.dispatch('swDialog/setSwDialog', value)
                }
            },
            reloadDialog: {
                get() {
                    return this.$store.getters['reloadDialog/reload_dialog']
                },
                set(value) {
                    this.$store.dispatch('swDialog/setReloadDialog', value)
                }
            }
        },
        async created() {
            if (this.$router.currentRoute.path === '/login') {
                return;
            }

            const res = await this.api('get', '/api/user', {});

            if (res.status === 200) {
                await this.$store.dispatch('auth/setUser', res.data);
            } else if (res.status === 401) {
                await this.$store.dispatch('reloadDialog/setReloadDialog', true);
            }

            // 更新検知をしたときの挙動を定義
            // const vm = this;
            // const onUpdateFound = () => {
            //     // 更新を検知したらモーダルで表示する。
            //     vm.$store.dispatch('swDialog/setSwDialog', true);
            // };
            //
            // // service worker 適応検知
            // this.sw = await (new swController()).init();
            // await this.sw.registerUpdateListener(onUpdateFound);
            // await this.sw.updateApp();
        },
        methods: {
            async updateApp() {
                // await this.sw.activateApp();
            }
        },
    }
</script>

<style lang="scss" scoped>
    /*@import url('https://fonts.googleapis.com/css?family=Noto+Sans+JP');*/

    * {
        font-family: monospace, Courier !important;
    }

    .v-enter-active, .v-leave-active {
        transition: opacity .3s;
    }

    .v-enter, .v-leave-to {
        opacity: 0;
    }
</style>
