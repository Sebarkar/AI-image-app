<template>
    <v-card>
        <v-toolbar
            color="indigo"
            dark
        >
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer />
            <slot v-if="$slots['menu']" name="menu">
            </slot>
            <v-spacer v-if="closeBtn && menuCenter" />
            <v-btn v-if="closeBtn" :loading="buttonLoading" icon @click="$emit('close')">
                <v-icon>
                    $vuetify.icon.close
                </v-icon>
            </v-btn>
        </v-toolbar>
        <v-card-text>
            <v-container>
                <slot name="content"></slot>
            </v-container>
        </v-card-text>
        <v-card-actions>
            <div class="flex-grow-1"></div>
            <v-btn v-if="saveBtn" color="blue darken-1" :loading="buttonLoading" text @click="$emit('save')">
                {{$t('site.save')}}
            </v-btn>
            <slot name="actions"></slot>
        </v-card-actions>
    </v-card>
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
            menuCenter: true,
            buttonLoading: {
                default: function () {
                    return false
                },
                type: Boolean
            },
            saveBtn: {
                default: function () {
                    return true
                },
                type: Boolean
            },
            closeBtn: {
                default: function () {
                    return false
                },
                type: Boolean
            },
        },
    }
</script>

<style scoped>

</style>
