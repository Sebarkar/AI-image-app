<script setup lang="ts">
import {format} from 'date-fns';

const {locale} = useI18n();

const props = defineProps({
  datesProps: {
    type: Array,
    default: () => []
  },
});
const {$format} = useNuxtApp()

const {data, pending} = useAsyncData('availability', () =>
        useApiFetch('get-availability-values', {
          body: {
            formData: {
              start: $format(props.datesProps[0]),
              end: $format(props.datesProps[props.datesProps.length - 1]),
            }
          },
        }),
    {
      server: false,
      watch: [() => props.datesProps]
    }
)

const getColor = (val: number) => {
  if (val <= 100 && val >= 71)
    return 'red';
  if (val <= 70 && val >= 51)
    return 'orange';
  if (val <= 50 && val >= 31)
    return 'yellow';
  if (val <= 30)
    return 'green';
};
</script>

<template>
  <v-col
      class="d-flex pa-0"
      cols="12"
      v-if="data && !pending"
  >
    <div
        v-for="(date, index) in data.data"
        :key="index"
        class="pa-0"
    >
      <v-sheet
          v-if="!date.count"
          class="pa-0 ma-0 text-center card_availability"
      >
      </v-sheet>
      <v-sheet
          v-if="date.count"
          :color="getColor(date.percent)"
          class="pa-0 ma-0 text-center card_availability calendar_sell"
          tile
      >
        <div class="text-caption ">
          <div v-if="date.percent" class="pa-0">{{ date.percent }}%</div>
          <div class="pa-0">{{ date.count }}</div>
        </div>
      </v-sheet>
    </div>
  </v-col>
</template>


<style>

.card_availability {
  color: rebeccapurple;
  border-radius: 5px;
}
</style>
