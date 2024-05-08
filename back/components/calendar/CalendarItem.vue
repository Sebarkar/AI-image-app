<script setup lang="ts">
import { format, parseISO, isAfter, isBefore, isSameDay } from 'date-fns';

const router = useRouter();

const props = defineProps({
  accommodation: Object,
  dates: Array,
  optionsProp: Object
})

const menu = ref([]);
const itemWidth = ref(50);
const loadingEdit = ref(false);

const options = computed(() => {
  return props.optionsProp;
})

const formatBookingStyle = (calendarData) => {
  if (!calendarData.nights) {
    return  '';
  }
  let styleString = '';
  let width = 0;
  let start = 52;
  width = itemWidth.value * calendarData.nights;

  if (calendarData.date === props.dates[0].date) {
    start = 0;
    width += itemWidth.value / 2;
  }

  if (calendarData.date !== props.dates[0].date) {
    width += -1;
  }

  styleString += 'left:' + start + '%;' + 'width:' + width + 'px;';
  return styleString;
}

const formatBookingClass = (booking) => {
  let classNames;
  let calendarEnd = parseISO(props.dates[props.dates.length - 1].date);
  let calendarStart = parseISO(props.dates[0].date);
  let bookingStart = parseISO(booking.check_in);
  let bookingEnd = parseISO(booking.check_out);

  if (isAfter(calendarEnd, bookingEnd)) {
    classNames = 'rounded-be-xl rounded-te-xl';
  }
  if (isBefore(calendarStart, bookingStart)) {
    classNames += ' rounded-bs-xl rounded-ts-xl';
  }

  if (isSameDay(calendarStart, bookingStart)) {
    classNames += ' rounded-bs-xl rounded-ts-xl ml-4';
  }

  if (isSameDay(calendarEnd, bookingEnd)) {
    classNames += ' rounded-be-xl rounded-te-xl';
  }

  return classNames;
}

const linkClicked = (path, query) => {
  loadingEdit.value = true;
  setTimeout(() => {
    router.push({name: path, query: query})
  }, 1000)
}
</script>

<template>
  <v-row
      class="d-flex flex-row pa-0"
      no-gutters
      color="grey lighten-2"
      justify="center"
  >
        <v-col cols="12" wrap dense class="d-flex pa-0">
            <v-sheet
                v-for="(date, index) in dates"
                :id="format(parseISO(date.date), 'y-MM-dd') + '-' + accommodation.id"
                :key="index"
                :class="(date.booking_id ? (' bid-' + date.booking_id) : '')"
            >
                <v-sheet
                    border
                    :class="{'busy': !date.available && date.price, 'available': date.available && date.price, 'inactive': !date.booking_id && !date.price, 'animate-pulse': date.skeleton}"
                    class="pa-0 text-center position-relative text-caption calendar_sell"
                >
                    <v-sheet
                         :style="formatBookingStyle(date.calendarData)"
                         class="calendar_booking__active px-0"
                         :class="formatBookingClass(date.calendarData.booking)"
                         color="secondary"
                         v-if="date.calendarData"
                    >
                        <v-list-item two-line class="px-1">
                          <template v-slot:prepend>

                              <calendar-booking-info
                                  @close="menu.splice(index , 1)"
                                  :booking="date.calendarData.booking"
                              />
                          </template>
                          <template v-slot:title="{ title }">
                            <div class="calendar_client_name">
                              {{ date.calendarData.booking.client.first_name }}
                              {{ date.calendarData.booking.client.second_name }}
                            </div>
                          </template>
                          <template v-slot:subtitle="{ subtitle }" v-if="date.calendarData.booking.source" >
                            <div class="text-caption">
                              {{ date.calendarData.booking.source.title }}
                            </div>
                          </template>
                        </v-list-item>
                    </v-sheet>
                    <v-card-text v-if="date.booking_id" class="pt-4 pr-0 pl-0 text-caption">
                        <span>{{ date.booking_id }}</span>
                    </v-card-text>
                    <v-card-text v-if="!date.booking_id" class="pt-0 pb-0 text-caption">
                        <span>{{ date.restriction }}</span>
                    </v-card-text>
                    <v-card-text v-if="!date.booking_id && date.price" class="pa-1 text-caption">
                        <span>{{ Number(date.price) }}</span>
                    </v-card-text>
                    <span class="item_calendar_date text-caption">{{ format(parseISO(date.date), "dd") }}</span>
                </v-sheet>
            </v-sheet>
        </v-col>
  </v-row>
</template>

<style>
.calendar_booking__active {
  z-index: 1;
  width: 100%;
  opacity: 0.9;
  height: 95%;
  top: 50%;
  transform: translate(0, -50%);
  position: absolute;
  display: flex;
  flex-direction: column;
  justify-content: center;
  transition: all 1s ease-out;
}

.item_calendar_date {
  position: absolute;
  font-size: 13px;
  top: -2px;
  right: 2px;
  color: #b3b3b3;
}

.calendar_booking {
  background-color: #ff5e0070;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  position: absolute;
}

</style>
