<template>
  <div class="report">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="reportList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="媒体名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="新闻标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="新闻链接">
        <template slot-scope="scope">{{ scope.row.linkUrl }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="媒体报道信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="媒体名称">
          <el-select v-model="inputMediaId" placeholder="请选择媒体">
            <el-option v-for="(media, index) in mediaList" :key="index" :value="media.id" :label="media.name"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="新闻标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="新闻链接">
          <el-input v-model="inputLinkUrl">
            <template slot="prepend" placeholder="请输入新闻地址">http://</template>
          </el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: {
    projData: Object
  },
  data () {
    return {
      showDialog: false,
      inputMediaId: '',
      inputLinkUrl: '',
      inputTitle: '',
      projReportId: '',
      mediaList: [],
      reportList: []
    }
  },
  mounted () {
    this.updateData()
    this.getMediaList()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjReportList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.reportList = res.data.data.dataList
        }
      })
    },
    getMediaList () {
      this.$http.get('/api/getMediaList').then((res) => {
        if (res.data.errcode === 0) {
          this.mediaList = res.data.data.dataList
        }
      })
    },
    showAdd () {
      this.projReportId = ''
      this.inputMediaId = ''
      this.inputLinkUrl = ''
      this.inputTitle = ''
      this.showDialog = true
    },
    showEdit (index) {
      var reportInfo = this.reportList[index]
      this.projReportId = reportInfo.id
      this.inputMediaId = reportInfo.mediaId
      this.inputLinkUrl = reportInfo.linkUrl
      this.inputTitle = reportInfo.title
      this.showDialog = true
    },
    showDel (projReportId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delReport(projReportId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.projReportId) {
        this.updReport()
      } else {
        this.addReport()
      }
    },
    addReport () {
      this.$http.post('/api/addProjReport', {
        projId: this.$route.params.id,
        mediaId: this.inputMediaId,
        linkUrl: this.inputLinkUrl,
        title: this.inputTitle
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updReport () {
      this.$http.post('/api/updProjReport', {
        projReportId: this.projReportId,
        mediaId: this.inputMediaId,
        linkUrl: this.inputLinkUrl,
        title: this.inputTitle
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delReport (projReportId) {
      this.$http.post('/api/delProjReport', {
        projReportId: projReportId
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
</style>
