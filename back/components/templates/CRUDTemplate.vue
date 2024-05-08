<script setup lang="ts">
import {addDays, eachDayOfInterval, format} from "date-fns";

const auth = useAuthStore();

const dates = ref(eachDayOfInterval({start: addDays(new Date(), -31), end: new Date()}));

const props = defineProps({
    routes: {
        type: Object,
        required: true
    },
    headers: {
        type: Array<object>,
        required: true
    },
    itemsPerPageOptions: {
        default: () => [
            {value: 50, title: '50'},
            {value: 100, title: '100'},
            {value: 250, title: '250'},
            {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'}
        ],
        type: Array,
    }
})

const options = reactive(
    {
        itemsPerPage: 50,
        itemsPerPageOptions: props.itemsPerPageOptions,
        search: {
            start: format(dates.value[0], 'y-MM-dd'),
            end: format(dates.value[dates.value.length - 1], 'y-MM-dd')
        },
    }
)

const valueForReloadData = ref(0);

const {data, pending} = await useLazyAsyncData(props.routes.index, async () => {
        const [models] = await Promise.all([
            useApiFetch(props.routes.index, {
                body: {
                    options: options
                }
            })
        ])
        return {models}
    },
    {
        server: false,
        watch: [valueForReloadData, () => auth.isAuthorized, options]
    }
)

const {$format} = useNuxtApp()

watch(dates, (val) => {
    if (val.length > 1) {
        options.search.start = $format(val[0])
        options.search.end = $format(val[val.length - 1])
        valueForReloadData.value++;
    }
})

const itemsPerPage = ref(50)

const models = computed(() => {
    return data.value?.models?.data;
})
const total = computed(() => {
    return data.value?.models?.count;
})

const loadItems = () => {
    valueForReloadData.value++;
}
</script>

<template>
    <TemplatesStandardTemplate
        :title="$t('site.' + routes.index)"
    >
        <template v-slot:toolbar-right>
            <ElementsDatePickerItem
                :loading="pending"
                range
                v-model="dates"
            />
        </template>
        <template v-slot:content>
            <v-card>
                <v-card-text>
                    <v-data-table
                        v-model:items-per-page="options.itemsPerPage"
                        :headers="headers"
                        :items="models"
                        :items-length="total"
                        :loading="pending"
                        :options.sync="options"
                        :server-items-length="total"
                        @update:options="loadItems"
                    >
                    </v-data-table>
                </v-card-text>
            </v-card>
        </template>
    </TemplatesStandardTemplate>
</template>

<style scoped>

</style>
