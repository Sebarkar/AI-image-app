<script setup lang="ts">
const props = defineProps({
  color: {
    default: 'primary',
    type: String,
  },
  icon: {
    default: 'mdi-account',
    type: String,
  },
})

</script>

<template>
  <v-card
      :color="color + '-darken-1'"
      class="overflow-hidden bubble-shape-sm "
      :class="'bubble-' + color + '-shape bubble-' + color"
  >
    <v-card-text class="pa-5">
      <div class="d-flex align-center ga-4">
        <v-btn
            variant="flat"
            size="large"
            density="comfortable"
            :color="'dark' + color + 2"
            class="text-white "
            :icon="icon"
        />
        <div>
          <h4 class="text-h4 font-weight-medium">
            <slot name="title">{{ $currency.format(213123) }} {{ $currency.localized }}
            </slot>
          </h4>
          <span class="text-subtitle-2 text-white">
            <slot name="subtitle">
              {{ $t('site.total earning') }}
            </slot>
            </span>
        </div>
      </div>


    </v-card-text>
  </v-card>
</template>

<style scoped>

</style>