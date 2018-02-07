<template>
  <div class="advisor">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="advisorList">
      <el-table-column label="照片">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.photoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="公司">
        <template slot-scope="scope">{{ scope.row.company }}</template>
      </el-table-column>
      <el-table-column label="简介">
        <template slot-scope="scope">{{ scope.row.intro }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="项目成员信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="姓名">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="照片">
          <el-upload class="upload-box" name="picture" action="/api/uploadFile" :on-success="onPhotoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputPhotoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="职位">
          <el-input v-model="inputCompany"></el-input>
        </el-form-item>
        <el-form-item label="简介">
          <el-input type="textarea" v-model="inputIntro"></el-input>
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
      inputPhotoUrl: '',
      inputCompany: '',
      inputIntro: '',
      advisorId: '',
      advisorList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjAdvisorList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.advisorList = res.data.data.dataList
        }
      })
    },
    onPhotoSuccess (res) {
      if (res.errcode === 0) {
        this.inputPhotoUrl = res.data.url
      }
    },
    showEdit (index) {
      var advisorInfo = this.advisorList[index]
      this.advisorId = advisorInfo.id
      this.inputName = advisorInfo.name
      this.inputPhotoUrl = advisorInfo.photoUrl
      this.inputCompany = advisorInfo.company
      this.inputIntro = advisorInfo.intro
      this.showDialog = true
    },
    showDel (advisorId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delAdvisor(advisorId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.advisorId) {
        this.updAdvisor()
      } else {
        this.addAdvisor()
      }
    },
    addAdvisor () {
      this.$http.post('/api/addProjAdvisor', {
        projId: this.$route.params.id,
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        company: this.inputCompany,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputPhotoUrl = ''
          this.inputCompany = ''
          this.inputIntro = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updAdvisor () {
      this.$http.post('/api/updProjAdvisor', {
        advisorId: this.advisorId,
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        company: this.inputCompany,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputName = ''
          this.inputPhotoUrl = ''
          this.inputCompany = ''
          this.inputIntro = ''
          this.showDialog = false
          this.advisorId = ''
          this.updateData()
        }
      })
    },
    delAdvisor (advisorId) {
      this.$http.post('/api/delProjAdvisor', {
        advisorId: advisorId
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
