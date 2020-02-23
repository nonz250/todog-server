<template>
    <v-container>
        <v-card>
            <v-card-text>
                <p>Android端末の方は下のボタンからインストールできます。</p>
                <p>iPhone端末の方はブラウザの<strong>「ホームに追加」</strong>を利用してください。</p>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    text
                    outlined
                    color="success"
                    @click="install"
                >インストール
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        name: "Index",
        computed: {
            debug() {
                return process.env.NODE_ENV !== 'production';
            }
        },
        methods: {
            async install() {
                const vm = this;
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker
                        .register("/sw.js")
                        .then(registration => {
                            if (vm.debug) {
                                console.log("ServiceWorker registered");
                            }
                        })
                        .catch(error => {
                            console.warn("ServiceWorker error", error);
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>
