<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="我的发布">
        <el-table :data="exchangeLists" v-loading="loading">
          <el-table-column label="币种">
            <template slot-scope="scope">{{ scope.row.symbol }}</template>
          </el-table-column>
          <el-table-column label="总额度">
            <template slot-scope="scope">{{ scope.row.totalAmount }}</template>
          </el-table-column>
          <el-table-column label="剩余额度">
            <template slot-scope="scope">{{ scope.row.remainAmount }}</template>
          </el-table-column>
          <el-table-column label="已售出额度">
            <template slot-scope="scope">{{ scope.row.soldOutAmount }}</template>
          </el-table-column>
          <el-table-column label="已赠送额度">
            <template slot-scope="scope">{{ scope.row.extraTotalAmount }}</template>
          </el-table-column>
          <el-table-column label="剩余比例">
            <template slot-scope="scope">{{ scope.row.remainRate }}</template>
          </el-table-column>
          <el-table-column label="发布时间">
            <template slot-scope="scope">{{ scope.row.createdAt }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <router-link style="color: green" :to="'/admin/exchangeDetail/' + scope.row.id">详情</router-link>
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="月度统计">
        <el-table :data="monthData">
          <el-table-column label="月份">
            <template slot-scope="scope">{{ scope.row.month }}</template>
          </el-table-column>
          <el-table-column label="已售出额度">
            <template slot-scope="scope">{{ scope.row.monthBuyAmount }}</template>
          </el-table-column>
          <el-table-column label="已赠送额度">
            <template slot-scope="scope">{{ scope.row.monthExtraAmount }}</template>
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
      token: '',
      exchangeLists: [],
      monthData: [],
      loading: true,
      pageno: 1,
      perpage: 10,
      dataCount: 0
    }
  },
  mounted () {
    this.getPid()
    // this.getExchangeRecords()
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.token = res.data.data.symbol
          if (this.token) {
            this.getExchangeRecords(this.token)
          }
        }
      })
    },
    getExchangeRecords (token) {
      this.$http.post('/api/getExchangeRecords', {
        pageno: this.pageno,
        perpage: this.perpage,
        symbol: token
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.exchangeLists = res.data.data.tokenData
          this.monthData = res.data.data.monthData
          if (this.exchangeLists) {
            this.loading = false
          }
        }
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
