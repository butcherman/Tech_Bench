<script>
import { Doughnut } from 'vue-chartjs'

export default {
  extends: Doughnut,
  props: [
    'chartdata'
  ],
   data() {
        return {
            options: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    },
  mounted () {
    this.renderChart(this.chartdata, this.options)
  }
}
</script>
