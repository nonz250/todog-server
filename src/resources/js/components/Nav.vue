<template>
    <div>
        <v-app-bar fixed>
            <v-app-bar-nav-icon @click="childDrawerControl"></v-app-bar-nav-icon>

            <v-toolbar-title>Todog</v-toolbar-title>

            <v-progress-linear
                :active="this.$store.getters['loader/loader']"
                :indeterminate="this.$store.getters['loader/loader']"
                color="primary"
                absolute
                bottom
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn icon @click="reload">
                <v-icon>refresh</v-icon>
            </v-btn>

            <v-menu
                v-model="toolMenu"
                :close-on-content-click="false"
                :nudge-width="300"
                :nudge-top="0"
                :nudge-right="0"
                offset-y
            >
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on">
                        <v-icon>apps</v-icon>
                    </v-btn>
                </template>

                <v-card>

                    <div class="grey--text text--lighten-1">general</div>

                    <v-container>
                        <v-row>
                            <v-col class="flex text-sm-center">
                                現在準備中です。
                            </v-col>
                        </v-row>
                    </v-container>

                    <v-divider v-if="this.$store.getters['auth/isAdmin']"></v-divider>

                    <div v-if="this.$store.getters['auth/isAdmin']" class="grey--text text--lighten-1">admin-only</div>

                    <v-container v-if="this.$store.getters['auth/isAdmin']">
                        <v-row>
                            <v-col class="flex text-sm-center">
                                <v-btn icon @click="toUsers">
                                    <v-icon x-large>account_box</v-icon>
                                </v-btn>
                            </v-col>
                            <v-col class="flex text-sm-center">
                                <v-btn icon @click="toParts">
                                    <v-icon x-large>business_center</v-icon>
                                </v-btn>
                            </v-col>
                            <v-col class="flex text-sm-center">
                                <v-btn icon @click="toBases">
                                    <v-icon x-large>apartment</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>

                </v-card>

            </v-menu>

            <v-menu
                v-model="configMenu"
                :close-on-content-click="false"
                :nudge-width="300"
                :nudge-top="0"
                :nudge-right="0"
                offset-y
            >

                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on">
                        <v-icon>more_vert</v-icon>
                    </v-btn>
                </template>

                <v-card>
                    <v-container>
                        <v-row>
                            <v-col class="flex text-sm-center">
                                <v-btn icon @click="toSetting">
                                    <v-icon x-large>build</v-icon>
                                </v-btn>
                            </v-col>
                            <v-col class="flex text-sm-center">
                            </v-col>
                            <v-col class="flex text-sm-center">
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>

            </v-menu>

        </v-app-bar>

        <nav-drawer-component ref="drawer"></nav-drawer-component>

    </div>
</template>

<script>

    export default {
        name: "Nav",
        // mixins: [],
        data() {
            return {
                toolMenu: false,
                configMenu: false
            }
        },
        methods: {
            childDrawerControl() {
                this.$refs.drawer.drawerControl();
            },
            reload() {
                this.$store.dispatch('loader/setLoader', true)
                this.$router.go({
                    path: this.$router.currentRoute.path, force: true
                });
            },
            toUsers() {
                this.$router.push({name: 'users'})
                this.toolMenu = false
            },
            toParts() {
                this.$router.push({name: 'parts'})
                this.toolMenu = false
            },
            toBases() {
                this.$router.push({name: 'bases'})
                this.toolMenu = false
            },
            toSetting() {
                this.$router.push({name: 'setting'})
                this.configMenu = false
            }
        }
    }
</script>

<style scoped>

</style>
