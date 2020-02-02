<template>
    <v-navigation-drawer v-model="drawer"
                         temporary
                         fixed
                         app
    >
        <v-list-item>
            <v-list-item-title class="title">Todog</v-list-item-title>
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
            <div>
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
                    {title: 'トップページ', icon: 'mdi-view-dashboard', link: "/", admin_only: false},
                    {title: 'ホーム', icon: 'mdi-home', link: "/home", admin_only: false},
                ],
                dropdownItems: [
                    // {
                    //     title: '管理者機能',
                    //     icon: 'supervised_user_circle',
                    //     admin_only: true,
                    //     subItems: [
                    //         {
                    //             title: "ランチ会作成",
                    //             icon: "restaurant",
                    //             link: "/admin",
                    //         },
                    //     ]
                    // },
                    // {
                    //     title: '過去のランチ会',
                    //     icon: 'history',
                    //     admin_only: true,
                    //     subItems: [
                    //         {
                    //             title: "ランチ会一覧",
                    //             icon: "list",
                    //             link: "/admin/meetings",
                    //         }
                    //     ]
                    // },
                ],
                user: null
            }
        },
        methods: {
            drawerControl() {
                this.drawer = !this.drawer
            },
        },
        created() {
            this.token = getCSRFToken()
        }
    }

    function getCSRFToken() {
        return document.getElementsByName('csrf-token').item(0).content
    }
</script>

<style scoped>

</style>
