<template>
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
                            v-model="email"
                            tabindex="1"
                            clearable
                            persistent-hint
                            prepend-inner-icon="mdi-email"
                            hint="todog@example.com"
                            :loading="this.$store.getters['loader/loader']"
                            :rules="[rules.required, rules.email]"
                        />
                        <v-text-field
                            v-model="password"
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
                            v-model="remember"
                            tabindex="3"
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
</template>

<script>
    import mixin from "../mixins/mixin";

    export default {
        name: "Login",
        mixins: [mixin],
        data() {
            return {
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
                email: '',
                password: '',
                remember: false,
            }
        },
        methods: {
            async login() {
                await this.$store.dispatch('loader/setLoader', true);

                const params = new FormData();
                params.append('email', this.email);
                params.append('password', this.password);
                if (this.remember) {
                    params.append('remember', this.remember);
                }
                const res = await this.api('post', 'api/login', params);
                if (res.status === 200) {
                    await this.$router.push('home');
                } else if (res.status === 422) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', this.getLaravelErrorMessages(res.data.errors));
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else if (res.status === 500) {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', res.data.message);
                    await this.$store.dispatch('snackbar/setColor', 'error');
                } else {
                    await this.$store.dispatch('snackbar/setSnackbar', true);
                    await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
                    await this.$store.dispatch('snackbar/setColor', 'error');
                }

                await this.$store.dispatch('loader/setLoader', false);
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
