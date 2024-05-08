<template>
        <v-card
            :loading="loadingProp"
            elevation="4"
            :height="height"
        >
            <v-toolbar
                color="submenu"
                dark
            >
                <v-toolbar-title class="mr-2">
                    {{ title }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <slot name="toolbar-center"></slot>
                <v-spacer></v-spacer>
                <slot name="toolbar-right"></slot>
            </v-toolbar>
            <v-card-text>
                <slot name="content"></slot>
            </v-card-text>
        </v-card>
</template>

<script>
    export default {
        name: "StandardTemplate",
        props: {
            title: {
                default: function () {
                    return false;
                },
                type: [String, Boolean]
            },
            height: {
                default: function () {
                    return 520;
                },
                type: [String, Number]
            },
            loadingProp: {
                default: function () {
                    return false;
                },
                type: Boolean
            },
        }
    }
</script>

<style scoped>

</style>
