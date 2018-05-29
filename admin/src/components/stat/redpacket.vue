<template>
    <div class="media">
        <el-form :inline="true" label-width="80px">
            <el-form-item label="选择日期:">
                <el-date-picker v-model="month" @change="selectMonth" type="month" placeholder="选择月份" value-format="yyyy-MM"></el-date-picker>
            </el-form-item>
        </el-form>
        <el-table :data="perList">
            <el-table-column label="时间">
                <template slot-scope="scope">{{ scope.row.date }}</template>
            </el-table-column>
            <el-table-column label="发糖包人数">
                <template slot-scope="scope">{{ scope.row.numsOfSendPacketMember }}</template>
            </el-table-column>x
            <el-table-column label="领取糖包人数">
                <template slot-scope="scope">{{ scope.row.numsOfPickPacketMember }}</template>
            </el-table-column>
            <el-table-column label="发放糖包个数">
                <template slot-scope="scope">{{ scope.row.numsOfPacketsSent }}</template>
            </el-table-column>
            <el-table-column label="人均发放比例">
                <template slot-scope="scope">{{ scope.row.sentPacketRate }}</template>
            </el-table-column>
            <el-table-column label="领取糖果数量">
                <template slot-scope="scope">{{ scope.row.amountOfCandyPicked }}</template>
            </el-table-column>
            <el-table-column label="平均单个糖包包含糖果数">
                <template slot-scope="scope">{{ scope.row.avgAmountOfPacket }}</template>
            </el-table-column>
            <el-table-column label="发放糖包总价值金额">
                <template slot-scope="scope">{{ scope.row.cnyValueOfTokenSent }}</template>
            </el-table-column>
            <!-- <el-table-column label="发放token种类" >
                <template slot-scope="scope" >{{ scope.row.kindsOfTokenSent.length }}</template>
            </el-table-column> -->
        </el-table>
    </div>
</template>
<script>
export default {
  data () {
    return {
      perList: [],
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputPosition: '',
      inputIntro: '',
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      projAllPass: 0,
      month: '',
      tokenid: ''
    }
  },
  mounted () {
    this.$http.post('/api/getPid', {
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.tokenid = res.data.data.tokenId.tokenid
        if (this.tokenid) {
          this.updateData()
        }
      }
    })
  },
  methods: {
    updateData () {
      this.$http.post('/api/getPacketStatByMonth', {
        month: new Date(),
        tokenId: this.tokenid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.perList = res.data.data
        }
      })
    },
    selectMonth () {
      this.$http.post('/api/getPacketStatByMonth', {
        month: this.month,
        tokenId: this.tokenid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.perList = res.data.data
        }
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
