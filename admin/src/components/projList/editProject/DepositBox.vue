<template>
  <div class="deposit-box">
    <template v-if="projData.status && projData.contractAddr">
      <div class="header-btn-area">
        <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
      </div>
      <el-table :data="depositBoxList">
        <el-table-column label="总额度">
          <template slot-scope="scope">{{ scope.row.totalAmount }}</template>
        </el-table-column>
        <el-table-column label="起购额度">
          <template slot-scope="scope">{{ scope.row.minAmount }}</template>
        </el-table-column>
        <el-table-column label="年化回报">
          <template slot-scope="scope">{{ scope.row.interestRate * 100 }}%</template>
        </el-table-column>
        <el-table-column label="接收地址">
          <template slot-scope="scope">
            <a class="link" :href="'https://etherscan.io/token/EOS?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.toAddr, 6) }}</a>
          </template>
        </el-table-column>
        <el-table-column label="操作或状态">
          <template slot-scope="scope">
            <div v-if="scope.row.status===0">
              <el-button size="mini" type="primary" @click="showOrderDialog(scope.$index)">支付</el-button>
              <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
            </div>
            <div v-else>{{scope.row.status === 1 ? '进行中' : scope.row.status}}</div>
          </template>
        </el-table-column>
      </el-table>

      <el-dialog title="余币宝信息" :visible.sync="showDialog" center>
        <el-form label-width="120px">
          <el-form-item label="总额度（枚）">
            <el-input v-model="inputTotalAmount"></el-input>
          </el-form-item>
          <el-form-item label="起始额度（枚）">
            <el-input v-model="inputMinAmount"></el-input>
          </el-form-item>
          <el-form-item label="锁仓期">
            <el-select v-model="inputLockTime">
              <el-option v-for="(option, index) in lockTimeList" :key="index" :label="option.label" :value="option.value"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="'年化回报(' + inputInterestRate +'%)'">
            <el-slider v-model="inputInterestRate" :step="3" :min="6" :max="18" show-stops></el-slider>
          </el-form-item>
          <el-form-item label="需支付回报">
            <span>{{ inputTotalAmount * inputInterestRate * 0.01 * inputLockTime / 12 }}枚</span>
          </el-form-item>
          <el-form-item label="回报打出地址">
            <el-input v-model="inputFromAddr" placeholder="请输入您用于支付回报的钱包地址"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer">
          <el-button @click="showDialog = false">取消</el-button>
          <el-button type="primary" @click="submit">确定</el-button>
        </div>
      </el-dialog>

      <el-dialog title="请支付余币宝利息" :visible.sync="showOrder" center>
        <el-form label-width="80px">
          <el-form-item label="打出地址">
            <span>{{ fromAddr }}</span>
          </el-form-item>
          <el-form-item label="接收地址">
            <span>{{ toAddr }}</span>
          </el-form-item>
          <el-form-item label="扫码支付">
            <div id="qrcode" v-if="clear"></div>
          </el-form-item>
          <el-form-item label="支付金额">
            <span>{{ totalInterest }}</span>
          </el-form-item>
        </el-form>
        <el-table :data="txRecordList" v-if="inScan">
          <el-table-column label="交易金额" prop="txAmount"></el-table-column>
          <el-table-column label="交易时间" prop="txTime"></el-table-column>
          <el-table-column label="交易哈希" prop="txHash">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/tx/' + scope.row.txHash" target="_blank">{{ getShortStr(scope.row.txHash, 5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-checkbox @change="selectRecord($event, scope.row.id)"></el-checkbox>
            </template>
          </el-table-column>
        </el-table>
        <div slot="footer">
          <template v-if="!inScan">
            <el-button type="primary" @click="getTxRecord">开始确认</el-button>
          </template>
          <template v-else>
            <el-button type="default" @click="getTxRecord">刷新</el-button>
            <el-button type="primary" @click="confirmTx">确认</el-button>
          </template>
        </div>
      </el-dialog>
    </template>
    <div v-else>
      <div class="empty" v-if="!projData.contractAddr">请在基本信息页配置通证的ERC20智能合约地址</div>
      <div class="empty" v-if="!projData.status">项目通过管理员审核后才能创建余币宝</div>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    click: Number
  },
  data () {
    return {
      showDialog: false,
      inputTotalAmount: '',
      inputMinAmount: '',
      inputLockTime: 3,
      inputInterestRate: 3,
      inputFromAddr: '',
      depositBoxList: [],
      fromAddr: '',
      toAddr: '',
      totalInterest: '',
      depositBoxId: '',
      showOrder: false,
      inScan: false,
      txRecordList: [],
      txRecordIdList: [],
      hasQrcode: false,
      clear: true,
      projId: '',
      projData: '',
      lockTimeList: [{
        label: '1个月',
        value: 1
      }, {
        label: '3个月',
        value: 3
      }, {
        label: '6个月',
        value: 6
      }, {
        label: '9个月',
        value: 9
      }, {
        label: '一年',
        value: 12
      }]
    }
  },
  mounted () {
    this.updateData()
    this.projId = this.$route.params.id
    this.$http.post('/api/getProjBasicInfo', {
      projId: this.projId
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.projData = res.data.data
      }
    })
  },
  watch: {
    click () {
      this.projId = this.$route.params.id
      this.$http.post('/api/getProjBasicInfo', {
        projId: this.projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.projData = res.data.data
        }
      })
    }
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjDepositBoxList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.depositBoxList = res.data.data.dataList
        }
      })
    },
    showAdd () {
      this.depositBoxId = ''
      this.inputTotalAmount = ''
      this.inputMinAmount = ''
      this.inputLockTime = 3
      this.inputInterestRate = 3
      this.inputFromAddr = ''
      this.showDialog = true
    },
    showOrderDialog (index) {
      var depositBox = this.depositBoxList[index]
      this.fromAddr = depositBox.fromAddr
      this.toAddr = depositBox.toAddr
      this.depositBoxId = depositBox.id
      this.totalInterest = depositBox.interestRate * depositBox.totalAmount * depositBox.lockTime / 12
      this.inScan = false
      this.showOrder = true
      this.$nextTick(function () {
        require('@/components/common/jquery.min.js')
        // eslint-disable-next-line
        $('#qrcode').html('')
        this.getQrcode()
      })
    },
    showDel (depositBoxId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delDepositBox(depositBoxId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      this.addDepositBox()
    },
    getQrcode () {
      require('@/components/common/qrcode.min.js')
      // eslint-disable-next-line
      $('#qrcode').qrcode({
        text: this.toAddr,
        width: 150,
        height: 150
      })
    },
    addDepositBox () {
      this.$http.post('/api/addDepositBox', {
        projId: this.$route.params.id,
        minAmount: this.inputMinAmount,
        lockTime: this.inputLockTime + '',
        totalAmount: this.inputTotalAmount,
        interestRate: this.inputInterestRate * 0.01 + '',
        fromAddr: this.inputFromAddr
      }).then((res) => {
        console.log(res)
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delDepositBox (depositBoxId) {
      this.$http.post('/api/delDepositBox', {
        depositBoxId: depositBoxId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    },
    getTxRecord () {
      this.$http.post('/api/getBoxTxRecordList', {
        depositBoxId: this.depositBoxId
      }).then((res) => {
        console.log(res)
        if (res.data.errcode === 0) {
          this.txRecordList = res.data.data.dataList
          this.inScan = true
        }
      })
    },
    selectRecord (e, txRecordId) {
      if (e) {
        this.txRecordIdList.push(txRecordId)
      } else {
        this.txRecordIdList.remove(txRecordId)
      }
    },
    confirmTx () {
      this.$http.post('/api/confirmBoxTx', {
        depositBoxId: this.depositBoxId,
        txRecordIdList: this.txRecordIdList
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.showOrder = false
          this.updateData()
        } else {
          alert(res.data.errmsg)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.empty {
  width: 100%;
  color: #F5A623;
  text-align: center;
  line-height: 30px;
}
.link {
  &:hover {
    color: #FFCF81;
  }
}
</style>
