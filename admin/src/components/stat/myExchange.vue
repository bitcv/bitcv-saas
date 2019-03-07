<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="我的兑换资产">
        <el-table :data="myExchange">
          <el-table-column label="Token">
            <template slot-scope="scope">{{ scope.row.symbol }}</template>
          </el-table-column>
          <el-table-column label="数量">
            <template slot-scope="scope">{{ scope.row.amount }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <!-- <el-button type="primary" v-if="scope.row.amount > 0" size="mini" @click="showEdit(scope.row)">提取</el-button> -->
              <el-button type="primary" size="mini">后续开放</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-dialog :title="'提取' + this.token" :visible.sync="showDialog" center>
        <el-form label-width="220px">
          <el-form-item label="当前资产：">
            <span>{{ this.currentAmount }} {{ this.currentSymbol }}</span>
          </el-form-item>
          <el-form-item label="输入数量：">
            <el-input type="text" placeholder="请输入数量" v-model="extraAmount"></el-input>
          </el-form-item>
          <el-form-item label="提示：">
            <span style="color: red;" v-if="this.token === 'ABCB'">提取到币威钱包 (账号：13811138084)</span>
            <span style="color: red;" v-else-if="this.token === 'TS'">提取到币威钱包 (账号：17621543259)</span>
            <span style="color: red;" v-else>提取到币威钱包 (账号：18519667007)</span>
          </el-form-item>
        </el-form>
        <div slot="footer">
          <el-button @click="showDialog = false">取消</el-button>
          <el-button type="primary" @click="submit">确定</el-button>
        </div>
        </el-dialog>
      </el-tab-pane>
      <el-tab-pane label="提取记录">
        <el-table :data="myExtraRecord">
          <el-table-column label="Token">
            <template slot-scope="scope">{{ scope.row.extraSymbol }}</template>
          </el-table-column>
          <el-table-column label="数量">
            <template slot-scope="scope">{{ scope.row.extraAmount }}</template>
          </el-table-column>
          <el-table-column label="时间">
            <template slot-scope="scope">{{ scope.row.createdAt }}</template>
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
      myExchange: [],
      myExtraRecord: [],
      token: '',
      showDialog: false,
      extraAmount: 0,
      currentAmount: 0,
      currentSymbol: ''
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
            this.getMyExchange()
          }
        }
      })
    },
    getMyExchange () {
      this.$http.post('/api/getMyExchange', {
        id: this.tokenId
      }).then((res) => {
        if (res.data.errcode === 0) {
          console.log(res.data.data)
          this.myExchange = res.data.data.assetData
          this.myExtraRecord = res.data.data.extraRecord
        }
      })
    },
    showEdit (item) {
      this.currentAmount = item.amount
      this.currentSymbol = item.symbol
      this.showDialog = true
    },
    submit () {
      if (this.extraAmount <= 0) {
        return this.$message({ type: 'error', message: '请输入提取数量' })
      }
      if (this.extraAmount > this.currentAmount) {
        return this.$message({ type: 'error', message: '提取数量不符' })
      }
      this.$confirm('确定提取: ' + this.extraAmount + this.currentSymbol + '到币威钱包吗？', '提醒', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.$http.post('/api/transferToken', {
          symbol: this.currentSymbol,
          extraAmount: this.extraAmount,
          extraSymbol: this.currentSymbol
        }).then((res) => {
          if (res.data.errcode === 0) {
            this.$message({ type: 'success', message: '提取成功，请到币威钱包里查看' })
            this.getMyExchange()
            this.showDialog = false
          } else {
            return this.$message({ type: 'error', message: res.data.errmsg })
          }
        })
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
