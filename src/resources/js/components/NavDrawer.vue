<template>
    <v-navigation-drawer v-model="drawer"
                         temporary
                         fixed
                         app
    >
        <v-list-item>
            <v-list-item-title class="title">Luncher</v-list-item-title>
            <v-btn icon @click="drawer = !drawer">
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
        </v-list-item>

        <v-divider></v-divider>

        <v-list dense>
            <v-list-item v-for="item in normalItems"
                         :key="item.title"
                         @click="drawer=false"
                         :to="item.link"
                         v-if="isDisplay(item.admin_only)"
            >
                <v-list-item-action>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-action>

                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-group v-for="(item, index) in dropdownItems"
                          :key="index"
                          :prepend-icon="item.icon"
                          no-action
                          v-if="isDisplay(item.admin_only)"
            >
                <template v-slot:activator>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>{{item.title}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
                <v-list-item v-for="(subItem, i) in item.subItems"
                             :key="i"
                             v-if="isDisplay(item.admin_only)"
                             :to="subItem.link"
                             @click="drawer=false"
                >
                    <v-list-item-action>
                        <v-icon>{{ subItem.icon }}</v-icon>
                    </v-list-item-action>

                    <v-list-item-content>
                        <v-list-item-title>{{subItem.title}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
            <div v-if="isLogin"
                 class="m-1"
            >
                <form action="/logout" method="post">
                    <input type="hidden" name="_token" :value="token"/>
                    <v-btn type="submit" block color="primary" outlined>ログアウト</v-btn>
                </form>
            </div>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    // import Mixins from '../mixins/mixin'

    export default {
        // mixins: [Mixins],
        data() {
            return {
                drawer: false,
                token: '',
                normalItems: [
                    {title: 'テスト', icon: '', link: "/test", admin_only: true},
                    {title: 'ホーム', icon: 'dashboard', link: "/", admin_only: false},
                    {title: 'マイページ', icon: 'home', link: "/mypage", admin_only: false},
                ],
                dropdownItems: [
                    {
                        title: '管理者機能',
                        icon: 'supervised_user_circle',
                        admin_only: true,
                        subItems: [
                            {
                                title: "ランチ会作成",
                                icon: "restaurant",
                                link: "/admin",
                            },
                        ]
                    },
                    {
                        title: '過去のランチ会',
                        icon: 'history',
                        admin_only: true,
                        subItems: [
                            {
                                title: "ランチ会一覧",
                                icon: "list",
                                link: "/admin/meetings",
                            }
                        ]
                    },
                ],
                user: null
            }
        },
        methods: {
            drawerControl() {
                this.drawer = !this.drawer
            },
            isDisplay(adminOnly) {
                if (adminOnly) {
                    return this.$store.getters['auth/isAdmin'];
                }
                return true;
            }
        },
        created() {
            this.token = getCSRFToken()
            this.user = this.$store.getters['auth/user']
        }
    }

    function getCSRFToken() {
        return document.getElementsByName('csrf-token').item(0).content
    }
</script>

<style scoped>

</style>
