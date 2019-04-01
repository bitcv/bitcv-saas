<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="OTC 统计">
        <el-form :inline="true" label-width="180px">
          <el-form-item>
            <div class="block">
              <span class="demonstration">开始日期：</span>
              <el-date-picker v-model="sdatadate" type="date" placeholder="选择日期" value-format="yyyy-MM-dd"></el-date-picker>
            </div>
          </el-form-item>
          <el-form-item>
            <div class="block">
              <span class="demonstration">结束日期：</span>
              <el-date-picker v-model="edatadate" type="date" placeholder="选择日期" value-format="yyyy-MM-dd"></el-date-picker>
            </div>
          </el-form-item>
          <el-form-item>
            <el-button type="success" @click.prevent="searchData">搜索</el-button>
          </el-form-item>
          <el-form-item label="累计成交订单总额：">
            {{ this.sumData.totalValue }} 元
          </el-form-item>
          <el-form-item label="累计成交出售（元）：">
            {{ this.sumData.totalSellValue }} 元
          </el-form-item>
          <el-form-item label="累计成交购买（元）：">
            {{ this.sumData.totalBuyValue }} 元
          </el-form-item>
          <el-form-item label="累计成交订单数：">
            {{ this.sumData.totalOrderCount }}
          </el-form-item>
          <el-form-item label="累计成交出售单：">
            {{ this.sumData.totalOrderSellCount }}
          </el-form-item>
          <el-form-item label="累计成交购买单：">
            {{ this.sumData.totalOrderBuyCount }}
          </el-form-item>
          <el-form-item label="投诉订单总数：">
            {{ this.sumData.totalAppealOrderCount }}
          </el-form-item>
        </el-form>
          <el-table :data="otcStatList">
            <el-table-column label="日期">
              <template slot-scope="scope">{{ scope.row.day }}</template>
            </el-table-column>
            <el-table-column label="每天交易总额（元）">
              <template slot-scope="scope">{{ scope.row.orderTotalValue }}</template>
            </el-table-column>
            <el-table-column label="每天交易订单">
              <template slot-scope="scope">{{ scope.row.orderTotalCount }}</template>
            </el-table-column>
            <el-table-column label="成交出售订单">
              <template slot-scope="scope">{{ scope.row.orderSellCount }}</template>
            </el-table-column>
            <el-table-column label="出售订单人数">
              <template slot-scope="scope">{{ scope.row.orderSellPeople }}</template>
            </el-table-column>
            <el-table-column label="成交购买订单">
              <template slot-scope="scope">{{ scope.row.orderBuyCount }}</template>
            </el-table-column>
            <el-table-column label="购买订单成交人数">
              <template slot-scope="scope">{{ scope.row.orderBuyPeople }}</template>
            </el-table-column>
            <el-table-column label="投诉订单">
              <template slot-scope="scope">{{ scope.row.orderAppealCount }}</template>
            </el-table-column>
          </el-table>
          <el-pagination v-if="otcStatList && otcStatList.length > 0" class="footer-page-box" @size-change="onStatSizeChange" @current-change="onStatCurChange" :current-page="statpageno" :page-sizes="[10, 20, 30, 40]" :page-size="statperpage" layout="total, sizes, prev, pager, next, jumper" :total="statDataCount">
            </el-pagination>
        </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>

export default {
  data () {
    return {
      token: '',
      assetList: [],
      statpageno: 1,
      statperpage: 10,
      statDataCount: 0,
      otcStatList: [],
      sumData: [],
      sdatadate: '',
      edatadate: ''
    }
  },
  mounted () {
    this.getPid()
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.token = res.data.data.symbol
          if (this.token) {
            this.getOtcStatList({countPages: true})
          }
        }
      })
    },
    getOtcStatList (param) {
      this.$http.post('/api/getOtcStatList', {
        pageno: this.statpageno,
        perpage: this.statperpage,
        sdate: this.sdatadate,
        edate: this.edatadate,
        symbol: this.token
      }).then((res) => {
        if (res.data.errcode === 0) {
          // 如果需要修改页码，传入countPage
          if (param && param.countPages) {
            this.statDataCount = res.data.data.totalCount
          }
          this.otcStatList = res.data.data.statData
          this.sumData = res.data.data.sumData
        }
      })
    },
    searchData () {
      if (this.statpageno > 1) {
        this.statpageno = 1
      }
      this.getOtcStatList({countPages: true})
    },
    onStatSizeChange (perpage) {
      this.statperpage = perpage
      this.getOtcStatList()
    },
    onStatCurChange (pageno) {
      this.statpageno = pageno
      this.getOtcStatList()
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
