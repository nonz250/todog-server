<template>
    <div>
        <ul class="horizontal-list">
            <li class="item">
                <v-card class="card-max-height">
                    <v-card-title>タスクステータス / ジャンル</v-card-title>
                    <v-card-text>ここにリスト</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text outlined>
                            <v-icon>mdi-plus-circle-outline</v-icon>
                            タスクを追加する
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </li>
            <li class="item">
                <v-card class="card-max-height">
                    <v-card-title>タスクステータス / ジャンル</v-card-title>
                    <v-card-text>ここにリスト</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text outlined>
                            <v-icon>mdi-plus-circle-outline</v-icon>
                            タスクを追加する
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </li>
            <li class="item">
                <v-card class="card-max-height">
                    <v-card-title>タスクステータス / ジャンル</v-card-title>
                    <v-card-text>ここにリスト</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text outlined>
                            <v-icon>mdi-plus-circle-outline</v-icon>
                            タスクを追加する
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </li>
            <li class="item">
                <v-btn text outlined block class="add-list-container" color="primary">
                    <v-icon>mdi-plus-circle-outline</v-icon>
                    リストを追加する
                </v-btn>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "Index",
        created() {},
        methods: {
            click() {
                const loader = this.$store.getters['loader/loader'];
                this.$store.dispatch('loader/setLoader', !loader);
            }
        }
    }
</script>

<style scoped>
    .horizontal-list {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .item {
        display: inline-block;
        vertical-align: top;
        height: 100vh;
        margin: 0;
        padding: 1rem;
    }

    .card-max-height {
        /* TODO: ここのカード高は使ってみて決める */
        /* height: 80vh; */
    }

    .add-list-container {
        border: dashed 2px #000000;
    }

    @media screen and (min-width: 375px) and (max-width: 850px) {
        .item {
            width: 90%;
        }
    }

    @media screen and (min-width: 851px) and (max-width: 920px) {
        .item {
            width: 60%;
        }
    }

    @media screen and (min-width: 921px) {
        .item {
            width: 40%;
        }
    }
</style>
