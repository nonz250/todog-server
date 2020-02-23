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
    </v-app>
</template>

<script>
    import mixin from "./mixins/mixin";

    export default {
        name: "App.vue",
        mixins: [mixin],
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
        },
        async created() {
            const res = await this.api('get', '/api/user', {});

            if (res.status === 200) {
                await this.$store.dispatch('auth/setUser', res.data);
            } else if (res.status === 401) {
                await this.$router.push('login');
            }

            // const user = this.$store.getters['auth/user'];
            // if (Object.keys(user).length === 0) {
            //     if (this.isLoggedIn) {
            // ログイン
            // const user = await this.api('get', '/api/user', {});
            // await this.$store.dispatch('auth/currentUser');
            // } else {
            //     await this.$router.push('login');
            // }
            // }
        }
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
