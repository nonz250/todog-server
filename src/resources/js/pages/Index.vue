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
                >インストール
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        name: "Index"
    }
</script>

<style scoped>

</style>
