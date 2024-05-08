<template>
    <v-row>
        <v-col class="pa-1">
            <v-card>
                <v-toolbar
                    color="menu"
                    dark
                    outlined
                    style="z-index: 1"
                >
                    <v-row>
                        <v-col>
                            <v-toolbar-title class="mr-2 text-capitalize">
                                {{ title }}
                            </v-toolbar-title>
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-col>
                            <slot name="toolbar-center"></slot>
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-col>
                            <slot name="toolbar-right"></slot>
                        </v-col>
                    </v-row>
                </v-toolbar>
                <v-card>
                    <v-row class="pa-1">
                        <slot name="content"></slot>
                    </v-row>
                </v-card>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    name: "StandardTemplate",
    props: {
        title: {
            default: function () {
                return 'Title props not set'
            },
            type: String
        },
    }
}
</script>

<style scoped>

</style>
