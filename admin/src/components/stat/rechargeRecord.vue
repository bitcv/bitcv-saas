<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="充值记录">
        <el-table :data="rechargeRecords" v-loading="loading">
          <el-table-column label="交易时间">
            <template slot-scope="scope">{{ scope.row.createdAt }}</template>
          </el-table-column>
          <el-table-column label="记录">
            <template slot-scope="scope">{{ scope.row.amount }}</template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>

export default {
  data () {
    return {
      tokenId: 0,
      rechargeRecords: [],
      loading: true,
      token: ''
    }
  },
  mounted () {
    this.getPid()
  },
  created () {
    this.tokenId = this.$route.params.id
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.token = res.data.data.symbol
          console.log(this.token)
          if (this.token) {
            this.loading = false
            this.getRechargeRecord()
          }
        }
      })
    },
    getRechargeRecord () {
      this.$http.post('/api/getRechargeRecord', {
        tokenId: this.tokenId
      }).then((res) => {
        if (res.data.errcode === 0) {
          console.log(res)
          this.rechargeRecords = res.data.data
        }
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
