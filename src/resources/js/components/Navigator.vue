<template>
    <div>
        <v-app-bar fixed dense>
            <v-app-bar-nav-icon
                v-if="isLoggedIn"
                @click="childDrawerControl"
            />

            <v-toolbar-title>Todog</v-toolbar-title>

            <v-progress-linear
                :active="this.$store.getters['loader/loader']"
                :indeterminate="this.$store.getters['loader/loader']"
                color="primary"
                absolute
                bottom
            />

            <v-spacer/>

            <v-btn icon @click="reload">
                <v-icon>mdi-refresh</v-icon>
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
                        <v-icon>mdi-apps</v-icon>
                    </v-btn>
                </template>

                <v-card>

                    <v-container>
                        <v-row>
                            <v-col class="flex text-sm-center">
                                現在準備中です。
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

                <template
                    v-slot:activator="{ on }"
                >
                    <v-btn
                        v-show="isLoggedIn"
                        icon
                        v-on="on"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-card>
                    <v-container>
                        <v-row>
                            <v-col class="flex text-sm-center">
                                現在準備中です。
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>

            </v-menu>

        </v-app-bar>

        <nav-drawer ref="drawer"/>

    </div>
</template>

<script>

    import NavDrawer from "./NavDrawer";

    export default {
        name: "Navigator",
        components: {NavDrawer},
        props: {
            isLoggedIn: {
                type: Boolean,
                require: true,
                default: function () {
                    return true;
                }
            }
        },
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
            toSetting() {
                this.$router.push({name: 'setting'})
                this.configMenu = false
            }
        }
    }
</script>

<style scoped>

</style>
