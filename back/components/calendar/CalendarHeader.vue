<script setup lang="ts">
import {parseISO} from "date-fns";

const { locale } = useI18n();

const props = defineProps({
  datesProps: {
    type: Array,
    default: () => []
  }
});

const { $format, parseISO } = useNuxtApp()
const dates = computed(() => {
  let months = {} ;
  props.datesProps?.forEach((date) => {
    let yearMonth = date.getYear() + '-' + date.getMonth();
    let month = date.getMonth();
    if (!months[yearMonth]) {
      months[yearMonth] = {
        month: month,
        full: $format(date, 'LLLL, yy'),
        days: [
          date
        ]
      };
    } else {
      months[yearMonth].days.push(date)
    }
  })
  return months;
})
</script>


<template>
  <v-row
      class="d-flex flex-row pa-0"
      no-gutters
      color="grey lighten-2"
      justify="center"
  >
    <v-col cols="12" wrap dense class="d-flex pa-0 rounded-t-lg overflow-hidden">
      <div class="d-flex pa-0 flex-wrap px-0 flex-column " v-for="month in dates">
        <div
            class="d-flex justify-center px-0 text-uppercase font-weight-bold overflow-hidden"
            :class="{'bg-secondary-lighten-3': (month.month & 1) === 1, 'bg-secondary-lighten-5': (month.month & 1) !== 1}"
            :style="'width:' + 50 * month.days.length + 'px'"
        ><span class="overflow-hidden" style="height: 22px">{{ month.full }}</span></div>
        <div class="w-100 d-flex px-0">
        <div
            v-for="(day, index) in month.days"
            :key="index"
            class="pa-0 d-flex justify-center align-center text-caption"
        >
          <v-sheet
              outlined
              :color="$dateFns.isSaturday(day) || $dateFns.isSunday(day) ? 'red-lighten-5' : ''"
              class="pa-1 text-center calendar_sell"
          >
            <span>{{ $format(day, "dd") }}</span><br>
            <span>{{ $format(day, "EEEEEE") }} </span>
          </v-sheet>
        </div>
        </div>
      </div>
    </v-col>
  </v-row>
</template>


<style>
.border-all {
  border: 1px solid red
}
</style>
