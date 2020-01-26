<template>
    <v-app>
        <nav-component></nav-component>
        <transition mode="out-in">
            <router-view></router-view>
        </transition>
    </v-app>
</template>

<script>
    export default {
        name: "App.vue"
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
