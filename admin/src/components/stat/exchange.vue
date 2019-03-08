<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="我的发布">
        <div class="header-btn-area" style="text-align: left;">
          <el-button type="primary" size="medium" @click="showAdd()">充值</el-button>
          <el-button type="success" size="medium">
            <router-link :to="'/admin/rechargeRecord/' + this.tokenId.tokenid">充值记录</router-link>
          </el-button>
        </div>
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
          <el-table-column label="已售出额度(包括赠送额度)">
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
          <el-table-column label="当前价格">
            <template slot-scope="scope">{{ scope.row.price }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <router-link style="color: green" :to="'/admin/exchangeDetail/' + scope.row.id">详情</router-link>
              <router-link style="color: red"  :to="'/admin/myExchange/' + scope.row.id">资产</router-link>
              <el-button style="margin-left: 5px;" type="success" size="mini" @click="editShow(scope.row)">编辑</el-button>
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
      <el-dialog :title="'充值 ' + this.token" :visible.sync="showDialog" center>
      <el-form label-width="360px">
        <el-form-item label="">
          <vue-qr text="0xa7e24e29386e6d304cc00ebf66ed690c27308e38" :margin="10" class="qrcode"></vue-qr>
        </el-form-item>
        <el-form-item label="充值地址：">
          <span>0xa7e24e29386e6d304cc00ebf66ed690c27308e38</span>
        </el-form-item>
        <el-form-item label="提示：">
          <span style="color: red;">建议使用币威钱包转账充值, 可立即到账</span>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog :title="'设置兑换 ' + this.token + ' 价格'" :visible.sync="priceDialog" center>
      <el-form label-width="250px">
        <el-form-item label="价格：">
          <el-input v-model="price" placeholder="最大输入市场价格的 20 %"></el-input>
        </el-form-item>
        <el-form-item label="">
          <span style="color: red;">当前市场价格：{{ this.priceData['ABCB'] }}</span>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="priceDialog = false">取 消</el-button>
        <el-button type="primary" @click="submit">确 定</el-button>
      </span>
    </el-dialog>
    </el-tabs>
  </div>
</template>
<script>

import VueQr from 'vue-qr'
export default {
  components: {
    VueQr
  },
  data () {
    return {
      token: '',
      exchangeLists: [],
      monthData: [],
      loading: true,
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      showDialog: false,
      tokenId: 0,
      priceDialog: false,
      price: '',
      priceData: []
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
          this.tokenId = res.data.data.tokenId
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
          this.priceData = res.data.data.priceData
          if (this.exchangeLists) {
            this.loading = false
          }
        }
      })
    },
    showAdd () {
      this.showDialog = true
    },
    editShow (row) {
      this.priceDialog = true
    },
    submit () {
      if (!this.price) {
        return this.$message({ type: 'error', message: '请输入 token 价格' })
      }
      if (this.price > this.priceData['ABCB'] * 1.2) {
        return this.$message({ type: 'error', message: '最大输入市场价格的 20 %' })
      }
      this.$confirm('当前设置的价格是: ' + this.price + '元', '请确认设置的价格', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.$http.post('/api/updateTokenPrice', {
          symbol: this.token,
          price: this.price
        }).then((res) => {
          if (res.data.errcode === 0) {
            this.$message({ type: 'success', message: res.data.errmsg })
            this.priceDialog = false
            this.getExchangeRecords(this.token)
          } else {
            this.$message({ type: 'error', message: res.data.errmsg })
          }
        })
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
