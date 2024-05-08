<template>
    <v-row>
        <v-col>
            <v-card outlined>
            <v-toolbar
                color="menu"
                dark
                outlined
            >
                <v-toolbar-title class="mr-2 text-capitalize">
                    {{ title }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <slot name="toolbar-center"></slot>
                <v-spacer></v-spacer>
                <slot v-if="$slots['toolbar-right']" name="toolbar-right"></slot>
                <v-spacer v-if="$slots['toolbar-right']"></v-spacer>
                <slot v-if="$slots['toolbar-actions']" name="toolbar-actions"></slot>
            </v-toolbar>
            <v-row>
                <slot name="content"></slot>
            </v-row>
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
