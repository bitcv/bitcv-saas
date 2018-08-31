<template>
    <div class="deposit-box">
        <el-tabs type="border-card">
            <el-tab-pane label="项目列表">
                <el-table :data="itemList">
                    <el-table-column type="index"></el-table-column>
                    <el-table-column label="项目名称">
                        <template slot-scope="scope">{{ scope.row.name }}</template>
                    </el-table-column>
                    <el-table-column label="总额度">
                        <template slot-scope="scope">{{ scope.row.totalAmount2 }}</template>
                    </el-table-column>
                    <el-table-column label="起始额度">
                        <template slot-scope="scope">{{ scope.row.minAmount2 }}</template>
                    </el-table-column>
                    <el-table-column label="锁仓期(天)">
                        <template slot-scope="scope">{{ scope.row.lockTime }}</template>
                    </el-table-column>
                    <el-table-column label="年化回报率">
                        <template slot-scope="scope">{{ scope.row.interestRate * 100 }} %</template>
                    </el-table-column>
                    <el-table-column label="当前认购人数">
                        <template slot-scope="scope">{{ scope.row.people }}</template>
                    </el-table-column>
                    <el-table-column label="当前认购人次">
                        <template slot-scope="scope">{{ scope.row.countm }}</template>
                    </el-table-column>
                    <el-table-column label="当前认购数量">
                        <template slot-scope="scope">{{ scope.row.totalorder }}</template>
                    </el-table-column>
                    <el-table-column label="剩余数量">
                        <template slot-scope="scope">{{ scope.row.lastamount }}</template>
                    </el-table-column>
                    <el-table-column label="销售完成度">
                        <template slot-scope="scope">{{ scope.row.rate }}</template>
                    </el-table-column>
                    <el-table-column label="状态">
                        <template slot-scope="scope">{{ scope.row.statusName }}</template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
            <el-tab-pane label="订单列表">
                <el-form :inline="true" label-width="100px">
                    <el-form-item label="余币宝名称:">
                        <el-select v-model="name" placeholder="请选择名称" @change="getOrderList({countPages: true})">
                            <el-option v-model="all" label="全部">全部</el-option>
                            <el-option v-for="(op, index) in names" :key="index" :label="op" :value="op"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <el-table :data="orderList">
                    <el-table-column type="index"></el-table-column>
                    <el-table-column label="名称">
                        <template slot-scope="scope">{{ scope.row.name }}</template>
                    </el-table-column>
                    <el-table-column label="手机号">
                        <template slot-scope="scope">{{ scope.row.mobile }}</template>
                    </el-table-column>
                    <el-table-column label="订单金额">
                        <template slot-scope="scope">{{ scope.row.amount2 }}</template>
                    </el-table-column>
                    <el-table-column label="预期回报额">
                        <template slot-scope="scope">{{ scope.row.endGet }}</template>
                    </el-table-column>
                    <!--<el-table-column label="支付地址">-->
                        <!--<template slot-scope="scope">-->
                            <!--<a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.fromAddr" target="_blank">{{ getShortStr(scope.row.from_addr, 5) }}</a>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="接收地址">
                        <template slot-scope="scope">
                            <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.to_addr, 5) }}</a>
                        </template>
                    </el-table-column>
                    <el-table-column label="合约地址">
                        <template slot-scope="scope">
                            <a class="link" :href="'https://etherscan.io/address/' + scope.row.contractAddr" target="_blank">{{ getShortStr(scope.row.contract_addr, 5) }}</a>
                        </template>
                    </el-table-column>-->
                    <el-table-column label="起始时间">
                        <template slot-scope="scope">{{ scope.row.created_at }}</template>
                    </el-table-column>
                    <el-table-column label="到期时间">
                        <template slot-scope="scope">{{ scope.row.endTime }}</template>
                    </el-table-column>
                    <el-table-column label="状态">
                        <template slot-scope="scope">{{ orderStatusDict[scope.row.status] }}</template>
                    </el-table-column>
                </el-table>
                <el-pagination v-if="orderList && orderList.length > 0" class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
                </el-pagination>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: [],
      orderList: [],
      orderStatusDict: [],
      names: [],
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      projId: '',
      name: '',
      all: ''
    }
  },
  mounted () {
    this.$http.post('/api/getPid', {
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.projId = res.data.data.projectid
        this.getItemList()
        this.getOrderList({countPages: true})
      }
    })
  },
  methods: {
    getItemList () {
      this.$http.post('/api/getAdminDepositBoxList', {
        projId: this.projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.itemList = res.data.data.dataList
        }
      })
    },
    getOrderList (param) {
      this.$http.post('/api/getOrderDepositBoxList', {
        pageno: this.pageno,
        perpage: this.perpage,
        projId: this.projId,
        name: this.name
      }).then((res) => {
        if (res.data.errcode === 0) {
          // 如果需要修改页码，传入countPage
          if (param && param.countPages) {
            this.dataCount = res.data.data.dataCount
          }
          this.orderList = res.data.data.dataList
          console.log(this.orderList)
          this.orderStatusDict = res.data.data.statusDict
          this.names = res.data.data.names
        }
      })
    },
    onBoxSizeChange (perpage) {
      this.perpage = perpage
      this.getOrderList()
    },
    onBoxCurChange (pageno) {
      this.pageno = pageno
      this.getOrderList()
    }
  }
}
</script>

<style>
</style>
