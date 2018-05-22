<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <!--<div class="header-btn-area">
        <el-button type="primary" icon="el-icon-plus">添加</el-button>
      </div>-->
      <el-tab-pane label="SaaS 列表">
        <el-table :data="datalist">
          <el-table-column label="申请机构">
            <template slot-scope="scope">{{ scope.row.org }}</template>
          </el-table-column>
          <el-table-column label="联系人">
            <template slot-scope="scope">{{ scope.row.username }}</template>
          </el-table-column>
          <el-table-column label="联系人电话">
            <template slot-scope="scope">{{ scope.row.mobile }}</template>
          </el-table-column>
          <el-table-column label="子域名">
            <template slot-scope="scope">{{ scope.row.subname }}</template>
          </el-table-column>
          <el-table-column label="独立域名">
            <template slot-scope="scope">{{ scope.row.domain }}</template>
          </el-table-column>
          <el-table-column label="申请时间">
            <template slot-scope="scope">{{ scope.row.ctime }}</template>
          </el-table-column>
          <el-table-column label="审核状态">
            <template slot-scope="scope">{{ scope.row.statusname }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="success" size="mini" @click="check(1,scope.row.proj_id)">通过</el-button>
              <el-button type="warning" size="mini" @click="check(2,scope.row.proj_id)">拒绝</el-button>
              <!--<el-button size="mini" style="margin-left: 0px;margin-top: 5px;" type="danger" @click="DelSaas(scope.row.proj_id)">删除</el-button>-->
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
      pageno: 1,
      perpage: 10,
      datalist: []
    }
  },
  mounted () {
    this.saasData()
  },
  methods: {
    saasData () {
      this.$http.post('/api/getApplySaasList', {
        pageno: this.pageno,
        perpage: this.perpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.datalist = res.data.data.datalist
        }
      })
    },
    check: function (e, sid) {
      this.$http.post('/api/checkSaas', {
        status: e,
        proj_id: sid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: status === 1 ? '审核通过' : '审核拒绝' })
          this.saasData()
        }
      })
    },
    DelSaas (pid) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delsaas(pid)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delsaas (pid) {
      this.$http.post('/api/deleteSaas', {
        proj_id: pid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.saasData()
        }
      })
    }
  }
}

</script>
<style lang="scss" scoped>
</style>
