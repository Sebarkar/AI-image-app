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
      class="p-5 overflow-hidden bubble-shape"
      :class="'bubble-' + color + '-shape'"
  >
    <v-card-text class="pa-6">
      <div class="d-flex align-start mb-5">
        <v-btn
            variant="flat"
            size="large"
            density="comfortable"
            :color="'dark' + color"
            class="text-white "
            :icon="icon"
        />
      </div>
      <div class="ml-auto">
        <slot name="append"></slot>
      </div>
        <h2 class="text-h1">
          <slot name="title">
            {{$currency.format(213123)}} {{ $currency.localized }}
          </slot>
        </h2>
          <span class="text-subtitle-1 text-white">
            <slot name="subtitle">
              {{ $t('site.total earning') }}
              </slot>
          </span>
    </v-card-text>
  </v-card>
</template>

<style scoped>

</style>