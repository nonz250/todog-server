<template>
    <form action="/login" method="post">
        <v-container>
            <v-row justify="center">
                <v-col>
                    <v-card
                        tile
                        outlined
                    >
                        <v-card-title>ログイン</v-card-title>
                        <v-card-text>
                            <v-text-field
                                name="email"
                                tabindex="1"
                                clearable
                                persistent-hint
                                prepend-inner-icon="mdi-email"
                                hint="todog@example.com"
                                :loading="this.$store.getters['loader/loader']"
                                :rules="[rules.required, rules.email]"
                            />
                            <v-text-field
                                name="password"
                                tabindex="2"
                                type="password"
                                clearable
                                persistent-hint
                                prepend-inner-icon="mdi-lock-question"
                                :counter="counter"
                                :loading="this.$store.getters['loader/loader']"
                                :rules="[rules.required, rules.counter]"
                            />
                            <v-checkbox
                                name="remember"
                                value="true"
                                label="ログイン状態を保持する"
                            />
                        </v-card-text>
                        <v-card-actions>
                            <v-btn
                                text
                                outlined
                                to="/"
                            >TOPページへ戻る
                            </v-btn>

                            <v-spacer/>

                            <input type="hidden" name="_token" :value="token"/>
                            <v-btn
                                type="submit"
                                text
                                outlined
                                color="primary"
                                @click="login"
                            >ログイン
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </form>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                token: '',
                loading: false,
                counter: 0,
                rules: {
                    required: value => !!value || 'この項目は必須です。',
                    counter: value => {
                        return String(value).length >= 8 || '8文字以上で入力してください。'
                    },
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'メールの形式で入力してください。';
                    },
                },
            }
        },
        created() {
            this.token = getCSRFToken();
        },
        methods: {
            login() {
                this.$store.dispatch('loader/setLoader', true);
            }
        }
    }

    function getCSRFToken() {
        return document.getElementsByName('csrf-token').item(0).content
    }

</script>

<style lang="scss" scoped>

</style>
