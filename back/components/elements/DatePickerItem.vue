<script setup lang="ts">
import {addDays, addMonths, addYears, eachDayOfInterval, startOfMonth, endOfMonth, startOfYear, endOfYear} from 'date-fns';

const {locale} = useI18n();
const route = useRoute();
const router = useRouter();

const props = defineProps({
  range: Boolean,
  maxDate: {
    default: function () {
      return null
    },
    type: [Object, Date, String]
  },
  displayedDateFormat: {
    default: 'd, MMM',
  },
  minDate: {
    default: function () {
      return null
    },
    type: [Object, Date, String]
  },
  maxRangeSelection: {
    default: 389,
    type: [Number, String]
  },
  loading: {
    default: function () {
      return false
    },
    type: [Boolean]
  },
  type: {
    default: function () {
      return 'month'
    },
    type: [String]
  },
  format: {
    default: function () {
      return 'fullMonth'
    },
    type: [String]
  },
})

const model = defineModel()
const menuSettings = ref(false)

const date = ref(null)

const menu = ref(false)
const loading = ref(false)
const radios = ref(0)

const daysOptions = ref([
  {title: 'today', value: 1, days: 1},
  {title: 'yesterday', value: 2, days: 1},
  {title: 'last 7 days', value: 3, days: 7},
  {title: 'last 14 days', value: 4, days: 14},
  {title: 'last 31 days', value: 5, days: 31},
  {title: 'last 365 days', value: 6, days: 365 },
])
const monthOptions = ref([
  {title: 'this month', value: 7, days: 31},
  {title: 'past month', value: 8, days: 31}
])

const yearOptions = ref([
  {title: 'this year', value: 9, days: 365},
  {title: 'past year', value: 10, days: 365},
])

watch(() => radios.value, (val) => {
  let start = new Date()
  let end = new Date()

  if (val === 1) {
    start = addDays(start, -1)
  }
  if (val === 2) {
    start = addDays(start, -2)
    end = addDays(end, -1)
  }
  if (val === 3) {
    start = addDays(start, -7)
  }
  if (val === 4) {
    start = addDays(start, -14)
  }

  if (val === 7) {
    start = startOfMonth(start)
    end = endOfMonth(end)
  }

  if (val === 8) {
    start = startOfMonth(addMonths(start, -1))
    end = endOfMonth(addMonths(end, -1))
  }

  if (val === 5) {
    start = addDays(start, -31)
  }


  if (val === 6) {
    start = addDays(start, -365)
  }

  if (val === 9) {
    start = startOfYear(start)
    end = endOfYear(end)
  }

  if (val === 10) {
    start = startOfYear(addYears(start, -1))
    end = endOfYear(addYears(end, -1))
  }

  model.value = eachDayOfInterval({start: start, end: end});
})

const formattedResult = computed(() => {
  const {$format} = useNuxtApp()
  if (!model.value) {
    return '';
  }
  if (Array.isArray(model.value) && model.value.length >= 2) {
    return $format(model.value[0], props.displayedDateFormat) + ' - ' + $format(model.value[model.value.length - 1], props.displayedDateFormat);
  }
  if (Array.isArray(model.value) && model.value.length) {
    return $format(model.value[0], props.displayedDateFormat);
  }
  return '';
})

</script>

<template>
  <v-btn-group
      variant="flat"
      color="secondary"
      density="comfortable"
      rounded="lg"
  >
    <v-menu
        v-model="menuSettings"
        transition="scale-transition"
        :close-on-content-click="false"
    >
      <template v-slot:activator="{ props }">
        <v-btn
            rounded="0"
            v-bind="props"
            :loading="loading"
        >
          <v-icon small icon="mdi-tune-variant"/>
        </v-btn>
      </template>
      <v-card elevation="3" min-width="350px">
        <v-card-text>
          <v-radio-group v-model="radios">
            <v-row>
              <v-col cols="6">
                <div class="text-caption text-center text-uppercase">{{ $t('site.by days') }}</div>
                <v-radio :value="option.value" v-for="option in daysOptions.filter(obj => obj.days <= maxRangeSelection)">
                  <template v-slot:label>
                    <div class="text-caption">{{ $t('site.' + option.title) }}</div>
                  </template>
                </v-radio>
              </v-col>
              <v-col cols="6">
                <div class="text-caption text-center text-uppercase">{{ $t('site.by month') }}</div>
                <v-radio :value="option.value" v-for="option in monthOptions.filter(obj => obj.days <= maxRangeSelection)">
                  <template v-slot:label>
                    <div class="text-caption">{{ $t('site.' + option.title) }}</div>
                  </template>
                </v-radio>
                <v-divider class="mb-5" v-if="maxRangeSelection >= 365">></v-divider>
                <div class="text-caption text-center text-uppercase" v-if="maxRangeSelection >= 365">{{ $t('site.by year') }}</div>
                <v-radio :value="option.value" v-for="option in yearOptions.filter(obj => obj.days <= maxRangeSelection)">
                  <template v-slot:label>
                    <div class="text-caption">{{ $t('site.' + option.title) }}</div>
                  </template>
                </v-radio>
              </v-col>
            </v-row>
          </v-radio-group>
        </v-card-text>
      </v-card>
    </v-menu>
    <v-menu
        offset-y
        transition="scale-transition"
        :close-on-content-click="false"
    >
      <template v-slot:activator="{ props }">

        <v-btn
            rounded="0"
            v-bind="props"
            :loading="loading"
        >
          <v-icon small icon="$calendar"/>
          <div class="text-caption pl-2">
            {{ formattedResult }}
          </div>
        </v-btn>
      </template>
      <v-date-picker
          rounded="lg"
          color="secondary"
          :model-value="model"
          @update:modelValue="model = $event"
          :locale="locale"
          :multiple="range ? 'range' : false"
      >
      </v-date-picker>
    </v-menu>
  </v-btn-group>
</template>

<style scoped>

</style>
