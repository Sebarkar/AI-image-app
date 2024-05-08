<script setup lang="ts">
const props = defineProps({
  title: {
    default: function () {
      return false;
    },
    type: [String, Boolean]
  },
  tabs: {
    default: function () {
      return false;
    },
    type: [Boolean, String]
  },
})
</script>


<template>
  <v-row>
    <v-col class="d-print-none" cols="12"
           v-if="props.title">
      <v-card>
        <v-toolbar
            color="white"
            rounded="lg"
            class="d-flex justify-space-between"
            style="z-index: 1"
        >
          <v-toolbar-title class="mr-2 text-capitalize ">
            <h1 class="text-h5 font-weight-bold">{{ props.title }}</h1>
          </v-toolbar-title>
          <v-spacer v-if="props['toolbar-center']"></v-spacer>
          <slot name="toolbar-center"></slot>
          <v-spacer v-if="props['toolbar-right']"></v-spacer>
          <div class="pr-3">
            <slot name="toolbar-right" ></slot>
          </div>
          <template v-slot:extension v-if="props.tabs">
            <slot name="tabs"></slot>
          </template>
        </v-toolbar>
      </v-card>
    </v-col>
    <v-col cols="12" v-if="$slots.additional">
      <slot name="additional"></slot>
    </v-col>
    <v-col cols="12">
      <slot name="content"></slot>
    </v-col>
  </v-row>
</template>

<style scoped>

</style>
