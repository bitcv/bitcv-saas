<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="快照统计">
        <el-table :data="snapShotList" v-loading="loading">
          <el-table-column label="开始时间">
            <template slot-scope="scope">{{ scope.row.start_time }}</template>
          </el-table-column>
          <el-table-column label="总量">
            <template slot-scope="scope">{{ scope.row.total_amount }}</template>
          </el-table-column>
          <el-table-column label="剩余量">
            <template slot-scope="scope">{{ scope.row.remain_amount }}</template>
          </el-table-column>
          <el-table-column label="比例">
            <template slot-scope="scope">{{ scope.row.rate }}</template>
          </el-table-column>
          <el-table-column label="结束时间">
            <template slot-scope="scope">{{ scope.row.end_time }}</template>
          </el-table-column>
          <el-table-column label="详情">
            <template slot-scope="scope">
              <router-link style="color: green" :to="'/admin/snapShotDetail/' + scope.row.id">详情</router-link>
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
      snapShotList: [],
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
            this.getAssetSnapShot()
          }
        }
      })
    },
    getAssetSnapShot () {
      this.$http.post('/api/getAssetSnapShot', {
        tokenId: this.token
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.snapShotList = res.data.data.airDrop
          console.log(this.snapShotList)
          if (this.snapShotList) {
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
