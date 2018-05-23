<template>
  <div class="proj-list">
    <div class="content-container">
      <div class="header-btn-area">
        <router-link to="/admin/addProject">
          <el-button type="primary" icon="el-icon-plus">新建项目</el-button>
        </router-link>
      </div>
      <el-form :inline="true" label-width="100px">
        <el-form-item label="项目名称:">
            <el-input v-model="projname" placeholder="请输入项目名称"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="success" @click.prevent="search">搜索</el-button>
        </el-form-item>
      </el-form>
      <el-table :data="projList">
        <!-- <el-table-column type="index"></el-table-column> -->
        <el-table-column label="项目编号">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="项目logo">
          <template slot-scope="scope">
            <img class="table-image" :src="scope.row.logo_url" alt="">
          </template>
        </el-table-column>
        <el-table-column label="项目名称">
          <template slot-scope="scope">
            <a class="link" :href="'/projDetail/info/' + scope.row.id" target="_blank">{{ scope.row.name_cn }}</a>
          </template>
        </el-table-column>
        <el-table-column label="创建时间" prop="created_at"></el-table-column>
        <el-table-column label="操作" width="235px">
          <template slot-scope="scope">
            <router-link :to="'/admin/projDepositBox/' + scope.row.id">
              <el-button size="mini">余币宝</el-button>
            </router-link>
            <router-link :to="'/admin/editProject/' + scope.row.id">
              <el-button size="mini">编辑</el-button>
            </router-link>
            <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
            <el-button style="margin-left: 0px; margin-top: 5px;" v-if="scope.row.status===0" size="mini" type="primary" @click="authProject(scope.row.id)">审核通过</el-button>
            <el-button style="margin-left: 0px; margin-top: 5px;" v-if="scope.row.status===1" size="mini" type="warning" @click="clearAuth(scope.row.id)">取消授权</el-button>
          </template>
        </el-table-column>
      </el-table>

    <!--Pagination-->
    <el-pagination class="footer-page-box" @size-change="onSizeChange" @current-change="onCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
    </el-pagination>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      projList: [],
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      user: [],
      projname: ''
    }
  },
  mounted () {
    this.updateData({countPages: true})
    this.getUser()
  },
  methods: {
    updateData (params) {
      this.$http.post('/api/getProjBasicList', {
        pageno: this.pageno,
        perpage: this.perpage,
        projname: this.projname
      }).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          this.projList = resData.data.dataList
          // 如果需要修改页码，传入 countPages
          if (params && params.countPages) {
            this.dataCount = resData.data.dataCount
          }
        }
      })
    },
    getUser () {
      this.$http.post('/api/getUser').then((res) => {
        if (res.data.errcode === 0) {
          this.user = res.data.data
          // console.log(this.user)
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
    },
    authProject (projId) {
      this.$http.post('/api/authProject', {
        projId: projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '授权成功!' })
          this.updateData()
        }
      })
    },
    clearAuth (projId) {
      this.$http.post('/api/clearProjAuth', {
        projId: projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '取消授权成功!' })
          this.updateData()
        }
      })
    },
    onSizeChange (perpage) {
      this.perpage = perpage
      this.updateData()
    },
    onCurChange (pageno) {
      this.pageno = pageno
      this.updateData()
    },
    search () {
      if (this.pageno > 1) {
        this.pageno = 1
      }
      this.updateData({countPages: true})
    }
  }
}
</script>

<style lang="scss" scoped>
.content-container {
  padding: 20px;
}
</style>
