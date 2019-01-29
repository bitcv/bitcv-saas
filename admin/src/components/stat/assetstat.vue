<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="资产统计">
        <el-table :data="assetList" v-loading="loading" element-loading-text="拼命加载中" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
          <el-table-column label="币种">
            <template slot-scope="scope">{{ scope.row.symbol }}</template>
          </el-table-column>
          <el-table-column label="持币人数">
            <template slot-scope="scope">{{ scope.row.count }}</template>
          </el-table-column>
          <el-table-column label="持币数量">
            <template slot-scope="scope">{{ scope.row.amount }}</template>
          </el-table-column>
          <el-table-column label="价值(人民币)">
            <template slot-scope="scope">{{ scope.row.value }}</template>
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
      assetList: [],
      loading: true
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
          this.token = res.data.data.tokenId.tokenid
          if (this.token) {
            this.getAssetStat()
          }
        }
      })
    },
    getAssetStat () {
      this.$http.post('/api/getAssetStat', {
        symbol: this.token
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.assetList = res.data.data.dataList
          if (this.assetList) {
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
