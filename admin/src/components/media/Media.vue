<template>
  <div class="media">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="mediaList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="媒体名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="新闻标题正则">
        <template slot-scope="scope">{{ scope.row.titleReg }}</template>
      </el-table-column>
      <el-table-column label="新闻发表时间正则">
        <template slot-scope="scope">{{ scope.row.releaseTimeReg }}</template>
      </el-table-column>
      <el-table-column label="新闻头图正则">
        <template slot-scope="scope">{{ scope.row.bannerUrlReg }}</template>
      </el-table-column>
      <el-table-column label="新闻内容正则">
        <template slot-scope="scope">{{ scope.row.contentReg }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="媒体信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="媒体名称">
          <el-input v-model="inputName" placeholder="请输入媒体名称"></el-input>
        </el-form-item>
        <el-form-item label="媒体Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="新闻标题正则">
          <el-input v-model="inputTitleReg" placeholder="请输入新闻标题正则表达式"></el-input>
        </el-form-item>
        <el-form-item label="新闻发布时间正则">
          <el-input v-model="inputReleaseTimeReg" placeholder="请输入新闻发布时间正则表达式"></el-input>
        </el-form-item>
        <el-form-item label="新闻头图正则">
          <el-input v-model="inputBannerUrlReg" placeholder="请输入新闻头图正则表达式"></el-input>
        </el-form-item>
        <el-form-item label="新闻内容正则">
          <el-input v-model="inputContentReg" placeholder="请输入新闻内容正则表达式"></el-input>
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
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputTitleReg: '',
      inputReleaseTimeReg: '',
      inputBannerUrlReg: '',
      inputContentReg: '',
      mediaList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.get('/api/getMediaList').then((res) => {
        if (res.data.errcode === 0) {
          this.mediaList = res.data.data.dataList
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    showAdd () {
      this.mediaId = ''
      this.inputName = ''
      this.inputLogoUrl = ''
      this.inputTitleReg = ''
      this.inputReleaseTimeReg = ''
      this.inputBannerUrlReg = ''
      this.inputContentReg = ''
      this.showDialog = true
    },
    showEdit (index) {
      var mediaData = this.mediaList[index]
      this.mediaId = mediaData.id
      this.inputName = mediaData.name
      this.inputLogoUrl = mediaData.logoUrl
      this.inputTitleReg = mediaData.titleReg
      this.inputReleaseTimeReg = mediaData.releaseTimeReg
      this.inputBannerUrlReg = mediaData.bannerUrlReg
      this.inputContentReg = mediaData.contentReg
      this.showDialog = true
    },
    showDel (mediaId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delMedia(mediaId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.mediaId) {
        this.updMedia()
      } else {
        this.addMedia()
      }
    },
    addMedia () {
      this.$http.post('/api/addMedia', {
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        titleReg: this.inputTitleReg,
        releaseTimeReg: this.inputReleaseTimeReg,
        bannerUrlReg: this.inputBannerUrlReg,
        contentReg: this.inputContentReg
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updMedia () {
      this.$http.post('/api/updMedia', {
        mediaId: this.mediaId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        titleReg: this.inputTitleReg,
        releaseTimeReg: this.inputReleaseTimeReg,
        bannerUrlReg: this.inputBannerUrlReg,
        contentReg: this.inputContentReg
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delMedia (mediaId) {
      this.$http.post('/api/delMedia', {
        mediaId: mediaId
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
