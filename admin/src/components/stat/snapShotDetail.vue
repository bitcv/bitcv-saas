<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="快照统计">
        <el-table :data="airDropDetail" v-loading="loading">
          <el-table-column label="数量">
            <template slot-scope="scope">{{ scope.row.amount }}</template>
          </el-table-column>
          <el-table-column label="状态">
            <template slot-scope="scope">{{ scope.row.statusName }}</template>
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
      airDropDetail: [],
      loading: true,
      airdropId: 0
    }
  },
  mounted () {
    this.getPid()
  },
  created () {
    this.airdropId = this.$route.params.id
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.token = res.data.data.tokenId.tokenid
          if (this.token) {
            this.getAssetSnapShot()
          }
        }
      })
    },
    getAssetSnapShot () {
      this.$http.post('/api/getAssetSnapShot', {
        tokenId: this.token,
        airdropId: this.airdropId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.airDropDetail = res.data.data.airDropDetail
          console.log(this.airDropDetail)
          if (this.airDropDetail) {
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
