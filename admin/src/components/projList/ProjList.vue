<template>
  <div class="proj-list">
    <div class="content-container">
      <div class="header-btn-area">
        <router-link to="/addProject">
          <el-button type="primary" icon="el-icon-plus">新建项目</el-button>
        </router-link>
      </div>
      <el-table :data="projList">
        <el-table-column label="项目ID" prop="id" width="150px"></el-table-column>
        <el-table-column label="项目logo">
          <template slot-scope="scope">
            <img class="table-image" :src="scope.row.logoUrl" alt="">
          </template>
        </el-table-column>
        <el-table-column label="项目名称" prop="nameCn"></el-table-column>
        <el-table-column label="创建时间" prop="createdAt">
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <router-link :to="'/editProject/' + scope.row.id">
              <el-button size="mini">编辑</el-button>
            </router-link>
            <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      projList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjBasicList', {
        pageno: 1,
        perpage: 30
      }).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          this.projList = resData.data.dataList
        }
      })
    },
    showDel (projId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delProject(projId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delProject (projId) {
      this.$http.post('/api/delProject', {
        projId: projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.content-container {
  padding: 20px;
}
</style>
