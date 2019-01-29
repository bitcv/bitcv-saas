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
    </el-tabs>
  </div>
</template>
<script>

export default {
  data () {
    return {
      token: '',
      exchangeLists: [],
      loading: true,
      pageno: 1,
      perpage: 10,
      dataCount: 0
    }
  },
  mounted () {
    this.getPid()
    this.getExchangeRecords()
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.token = res.data.data.symbol
        }
      })
    },
    getExchangeRecords () {
      this.$http.post('/api/getExchangeRecords', {
        pageno: this.pageno,
        perpage: this.perpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.exchangeLists = res.data.data.tokenData
          console.log(this.exchangeLists)
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
