<template>
    <v-card>
        <v-card-title>テスト</v-card-title>
        <v-card-text>表示されているかな？[ {{this.$store.getters['loader/loader']}} ]</v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text outlined color="primary" @click="click">テスト</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "Test",
        created() {
            this.$store.dispatch('loader/setLoader', true);
        },
        methods: {
            click() {
                const loader = this.$store.getters['loader/loader'];
                this.$store.dispatch('loader/setLoader', !loader);
            }
        }
    }
</script>

<style scoped>

</style>
