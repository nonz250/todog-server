<template>
    <v-app>
        <nav-component/>
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
    export default {
        name: "App.vue",
        computed: {
            snackbar: {
                get() {
                    return this.$store.getters['snackbar/snackbar']
                },
                set(value) {
                    this.$store.dispatch('snackbar/setSnackbar', value)
                }
            },
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
