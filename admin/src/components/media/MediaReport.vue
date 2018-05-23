<template>
  <div class="media">
    <!-- <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div> -->
    <el-table :data="mediaReportList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="项目项目">
        <template slot-scope="scope">{{ scope.row.nameCn }}</template>
      </el-table-column>
      <el-table-column label="社交来源">
        <template slot-scope="scope">
          <i class="fab" :class="scope.row.fontClass"></i>
        </template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.officialName }}</template>
      </el-table-column>
      <el-table-column label="发布时间">
        <template slot-scope="scope">{{ scope.row.postTime }}</template>
      </el-table-column>
      <el-table-column label="更新时间">
        <template slot-scope="scope">{{ scope.row.updateAt }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <!-- <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button> -->
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!--Pagination-->
    <el-pagination class="footer-page-box" @size-change="onMediaSizeChange" @current-change="onMediaCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
    </el-pagination>
  </div>
</template>
<script>
export default {
  data () {
    return {
      mediaReportList: [],
      pageno: 1,
      perpage: 10,
      dataCount: 0
    }
  },
  mounted () {
    this.updateData()
    this.getMediaCount()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getMediaReportList', {
        pageno: this.pageno,
        perpage: this.perpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.mediaReportList = res.data.data.medisReportList
        }
      })
    },
    getMediaCount () {
      this.$http.post('/api/getMediaReportCount').then((res) => {
        if (res.data.errcode === 0) {
          this.dataCount = res.data.data.dataCount
        }
      })
    },
    showDel (id) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delMediaReport(id)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delMediaReport (id) {
      this.$http.post('/api/delMediaReport', {
        id: id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    },
    onMediaSizeChange (perpage) {
      this.perpage = perpage
      this.updateData()
    },
    onMediaCurChange (pageno) {
      this.pageno = pageno
      this.updateData()
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
