<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="兑换详情">
        <el-form :inline="true" label-width="180px">
          <el-form-item label="总额度：">
            {{ this.exchangeStatData.totalAmount }} {{ this.exchangeStatData.symbol }}
          </el-form-item>
          <el-form-item label="剩余额度：">
            {{ this.exchangeStatData.remainAmount }} {{ this.exchangeStatData.symbol }}
          </el-form-item>
          <el-form-item label="剩余比例：">
            {{ this.exchangeStatData.remainRate }}
          </el-form-item>
          <el-form-item label="最小购买额度：">{{ this.exchangeStatData.minAmount }}</el-form-item>
          <el-form-item label="最大购买额度：">{{ this.exchangeStatData.totalAmount }}</el-form-item>
          <el-form-item label="已购人数：">{{ this.exchangeStatData.count }}</el-form-item>
          <el-form-item label="卖出：">BTC: {{ this.exchangeStatData.sellSymbolBTC }}, ETH：{{ this.exchangeStatData.sellSymbolETH }} BCV: {{ this.exchangeStatData.sellSymbolBCV }}</el-form-item>
          <el-form-item label="平均购买：">{{ this.exchangeStatData.average }}</el-form-item>
        </el-form>
        <el-table :data="exchangeRecords" v-loading="loading">
          <el-table-column label="用户昵称">
            <template slot-scope="scope">{{ scope.row.userInfo.nickname }}</template>
          </el-table-column>
          <el-table-column label="手机号">
            <template slot-scope="scope">{{ scope.row.userInfo.mobile }}</template>
          </el-table-column>
          <el-table-column label="卖出 Token">
            <template slot-scope="scope">{{ scope.row.sellSymbol }}</template>
          </el-table-column>
          <el-table-column label="卖出数量">
            <template slot-scope="scope">{{ scope.row.sellAmount }}</template>
          </el-table-column>
          <el-table-column label="买入 Token">
            <template slot-scope="scope">{{ scope.row.buySymbol }}</template>
          </el-table-column>
          <el-table-column label="买入的数量">
            <template slot-scope="scope">{{ scope.row.buyAmount }}</template>
          </el-table-column>
          <el-table-column label="兑换比例">
            <template slot-scope="scope">{{ scope.row.currentRate }}</template>
          </el-table-column>
          <el-table-column label="对USDT价格">
            <template slot-scope="scope">{{ scope.row.toUsdt }}</template>
          </el-table-column>
          <el-table-column label="交易时间">
            <template slot-scope="scope">{{ scope.row.createdAt }}</template>
          </el-table-column>
        </el-table>
        <el-pagination v-if="exchangeRecords && exchangeRecords.length > 0" class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="pageno" :page-sizes="[30, 40, 50, 60]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
        </el-pagination>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>

export default {
  data () {
    return {
      tokenId: 0,
      exchangeRecords: [],
      exchangeData: [],
      loading: true,
      pageno: 1,
      perpage: 30,
      dataCount: 0,
      exchangeStatData: [],
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
          if (this.token) {
            this.getExchangeRecords(this.token)
            this.getExchangeStatData()
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
          this.exchangeRecords = res.data.data.records
          this.exchangeData = res.data.data.tokenData
          if (this.exchangeRecords) {
            this.loading = false
          }
        }
      })
    },
    getExchangeStatData () {
      this.$http.post('/api/getExchangeStatData', {
        id: this.tokenId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.exchangeStatData = res.data.data
          this.dataCount = res.data.data.count
        }
      })
    },
    onBoxSizeChange (perpage) {
      this.perpage = perpage
      this.getExchangeRecords(this.token)
    },
    onBoxCurChange (pageno) {
      this.pageno = pageno
      this.getExchangeRecords(this.token)
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
