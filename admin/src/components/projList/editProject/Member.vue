<template>
  <div class="memeber">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="memberList">
      <el-table-column label="照片">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.photoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="职位">
        <template slot-scope="scope">{{ scope.row.position }}</template>
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
      <!-- <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="直接选择" name="first">
          <el-form label-width="80px">
            <el-form-item label="成员名称">
            <el-select v-model="memberId" placeholder="请选择成员">
              <el-option v-for="(social, index) in socialOptionList" :key="index" :value="social.id" :label="social.name"></el-option>
            </el-select>
            </el-form-item>
          </el-form>
        </el-tab-pane>
        <el-tab-pane label="手动输入" name="second"> -->
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
            <el-input v-model="inputPosition"></el-input>
          </el-form-item>
          <el-form-item label="简介">
            <el-input type="textarea" v-model="inputIntro"></el-input>
          </el-form-item>
        </el-form>
        <!-- </el-tab-pane>
      </el-tabs> -->
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
      activeName: 'second',
      showDialog: false,
      inputName: '',
      inputPhotoUrl: '',
      inputPosition: '',
      inputIntro: '',
      memberList: [],
      memberId: '',
      projReportId: '',
      socialOptionList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjMemberList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.memberList = res.data.data.dataList
        }
      })
    },
    getSocialOptionList () {
      this.$http.get('/api/getPerList').then((res) => {
        if (res.data.errcode === 0) {
          this.socialOptionList = res.data.data.perList
        }
      })
    },
    onPhotoSuccess (res) {
      if (res.errcode === 0) {
        this.inputPhotoUrl = res.data.url
      }
    },
    handleClick (tab, event) {
    },
    showAdd () {
      this.projReportId = ''
      this.memberId = ''
      this.showDialog = true
    },
    showEdit (index) {
      var memberInfo = this.memberList[index]
      this.memberId = memberInfo.id
      this.inputName = memberInfo.name
      this.inputPhotoUrl = memberInfo.photoUrl
      this.inputPosition = memberInfo.position
      this.inputIntro = memberInfo.intro
      this.showDialog = true
    },
    showDel (memberId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delMember(memberId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.memberId) {
        this.updMember()
      } else {
        this.addInputMember()
      }
    },
    addInputMember () {
      this.$http.post('/api/addProjIMember', {
        projId: this.$route.params.id,
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputPhotoUrl = ''
          this.inputPosition = ''
          this.inputIntro = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    addMember () {
      this.$http.post('/api/addProjMember', {
        projId: this.$route.params.id,
        memberId: this.memberId,
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputPhotoUrl = ''
          this.inputPosition = ''
          this.inputIntro = ''
          this.memberId = ''
          this.personId = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updMember () {
      this.$http.post('/api/updProjMember', {
        memberId: this.memberId,
        projId: this.$route.params.id,
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputName = ''
          this.inputPhotoUrl = ''
          this.inputPosition = ''
          this.inputIntro = ''
          this.showDialog = false
          this.memberId = ''
          this.personId = ''
          this.updateData()
        }
      })
    },
    delMember (memberId) {
      console.log(memberId)
      this.$http.post('/api/delProjMember', {
        memberId: memberId
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
