<script setup lang="ts">

const props = defineProps({
  xaxis: Object,
  series: Array,
})

const chartOptions = computed(() => {
  return {
    chart: {
      type: 'bar',
      stacked: true,
      id: 'vuechart-example',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      }
    },
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          position: 'bottom',
          offsetX: -10,
          offsetY: 0
        }
      }
    }],
    dataLabels: {
      formatter: (val, opt) => {
        return  val ? Math.round(val / 1000) + 'K' : ''
      },
    },
    colors: ['#0b253c', '#00bfff', '#05789f', '#4f5f94', '#07111a'],
    plotOptions: {
      bar: {
        horizontal: false,
        borderRadius: 5,
        dataLabels: {
          total: {
            enabled: true,
            style: {
              fontSize: '13px',
              fontWeight: 900
            }
          }
        }
      },
    },
    xaxis: props.xaxis,
    legend: {
      position: 'right',
      offsetY: 40,
      showForZeroSeries: false,
      showForNullSeries: true,
    },
    fill: {
      opacity: 1
    }
  }
});
</script>

<template>
  <client-only>
    <apexchart
        height="300px"
        type="bar"
        :options="chartOptions"
        :series="props.series"
    ></apexchart>
  </client-only>
</template>

<style scoped>

</style>