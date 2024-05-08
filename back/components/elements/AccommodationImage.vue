<script setup lang="ts">

const props = defineProps({
  accommodation: {
    default: null,
    type: [Object, null],
  },
  imageLink: {
    default: '',
    type: String,
  },
});
</script>

<template>
  <v-avatar
      class="ma-1"
  >
    <v-img
        v-if="props.imageLink || props.accommodation"
        :lazy-src="props.imageLink ? props.imageLink : props.accommodation.image.xs"
        :src="props.imageLink ? props.imageLink : props.accommodation.image.xs"
        aspect-ratio="1"
        max-width="64"
        min-width="64"
    />
  </v-avatar>
</template>


<style scoped>

</style>
