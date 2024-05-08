<script setup lang="ts">
import {format} from 'date-fns';

const {t} = useI18n()

const props = defineProps({
  booking: {
    type: Object,
    default: () => ({})
  }
});

let phoneDialog = ref(false);
const menu = ref(false)

const emit = defineEmits([
  'close'
])

</script>

<template>
  <v-menu
      v-model="menu"
      :close-on-content-click="false"
      :nudge-width="200"
      offset-x
  >
    <template v-slot:activator="{ props }">
      <v-btn
          small
          size="35"
          color="secondary"
          plain
          icon="$show"
          v-bind="props"
      />
    </template>
    <v-card
        max-width="500"
        class="mx-auto"
        flat
        tile
        elevation="4"
    >
      <v-img
          src="https://admin.barkar.apartments/images/odessa.jpg"
          height="200px"
          cover
          width="100%"
          gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
          dark
      >
        <v-row class="fill-height">
          <v-card-title class="pl-6 pt-6 text-white">
            <div class="display-1 pl-6 pt-6">{{ props.booking.client.first_name }}
              {{ props.booking.client.second_name }}
            </div>
          </v-card-title>
        </v-row>
      </v-img>
      <v-list lines="three">
        <v-list-item
            @click=""
            :subtitle="t('site.mobile')"
            :title="props.booking.client.phone"
        >
          <phone-dialog
              @closePhoneDialog="phoneDialog = false"
              :dialog-prop="phoneDialog"
              :phone="props.booking.client.phone"
          />

          <template v-slot:prepend>
            <v-icon icon="$phone" color="indigo"/>
          </template>
        </v-list-item>

        <v-divider inset></v-divider>

        <v-list-item
            :subtitle="t('site.email')"
            :title="props.booking.client.email"
            @click=""
            v-if="props.booking.client.email"
        >
          <template v-slot:prepend v-if="props.booking.client.email">
            <v-icon icon="$mail"></v-icon>
          </template>
        </v-list-item>

        <v-list-item
            @click=""
            :subtitle="t('site.dates')"
            :title="t('site.from') + ' ' + props.booking.check_in + ' ' + t('site.to') + ' ' + props.booking.check_out"
        >
          <template v-slot:prepend>
            <v-icon icon="$calendar"></v-icon>
          </template>
          <template v-slot:append>
            <v-icon icon="$nights" color="indigo" @click="phoneDialog = true"/>
            {{ booking.total_nights }}
          </template>
        </v-list-item>

        <v-divider inset></v-divider>

        <v-list-item
            @click=""
            :subtitle="t('site.total price')"
            :title="props.booking.price"
        >
          <template v-slot:prepend>
            <v-icon icon="$cash"></v-icon>
          </template>
        </v-list-item>
      </v-list>
      <template #actions>
        <v-spacer/>
        <v-btn class="ma-2" outlined @click="menu = false" color="secondary">
          <v-icon left icon="$close"/>
          {{ t('site.close') }}
        </v-btn>
      </template>
    </v-card>
  </v-menu>
</template>

<style>
</style>
