<template>
  <div class="media">
    <div class="header-btn-area">
      <!-- <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button> -->
    </div>
    <div>
      <el-form label-width="100px">
        <el-form-item label="手机号:">
          <el-input v-model="mobile" placeholder="请输入手机号" style="width:200px;"></el-input>
          <el-button @click.prevent="search">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <el-table :data="perList">
      <el-table-column type="index"></el-table-column>
      <!-- <el-table-column label="人物头像">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column> -->
      <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.nickname }}</template>
      </el-table-column>
      <el-table-column label="手机号">
        <template slot-scope="scope">{{ scope.row.mobile }}</template>
      </el-table-column>
      <!-- <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.nickname }}</template>
      </el-table-column> -->
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
          <el-button v-if="scope.row.isSys===0" size="mini" type="primary" @click="authOperate(scope.row.id)">成为管理员</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="人物信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <!-- <el-form-item label="头像">
          <el-input v-model="inputLogoUrl" placeholder="请输入人物头像"></el-input>
        </el-form-item> -->
        <el-form-item label="头像">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="姓名">
          <el-input v-model="inputName" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="手机号">
          <el-input v-model="inputIntro"></el-input>
        </el-form-item>
        <el-form-item label="密码">
          <el-input v-model="inputPosition"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>
    <!--Pagination-->
    <el-pagination class="footer-page-box" @size-change="onSizeChange" @current-change="onCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
    </el-pagination>
  </div>
</template>
<script>
export default {
  data () {
    return {
      perList: [],
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputPosition: '',
      inputIntro: '',
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      mobile: ''
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getUserList', {
        pageno: this.pageno,
        perpage: this.perpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.perList = res.data.data.dataList
          this.dataCount = res.data.data.dataCount
        }
      })
    },
    showAdd () {
      this.mediaId = ''
      this.inputName = ''
      this.inputLogoUrl = ''
      this.inputPosition = ''
      this.inputIntro = ''
      this.showDialog = true
    },
    search () {
      this.$http.post('/api/getUserSearch', {
        mobile: this.mobile
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.perList = res.data.data.dataList
          this.dataCount = res.data.data.dataCount
        }
      })
    },
    showEdit (index) {
      var perData = this.perList[index]
      this.mediaId = perData.id
      this.inputName = perData.nickname
      this.inputLogoUrl = perData.avatarUrl
      this.inputPosition = perData.passwd
      this.inputIntro = perData.mobile
      this.showDialog = true
    },
    authOperate (Id) {
      this.$http.post('/api/authOperate', {
        id: Id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '授权成功!' })
          this.updateData()
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    submit () {
      if (this.mediaId) {
        this.updPerson()
      } else {
        this.addPerson()
      }
    },
    showDel (id) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delPerson(id)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    addPerson () {
      this.$http.post('/api/addAdmin', {
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updPerson () {
      this.$http.post('/api/updAdmin', {
        mediaId: this.mediaId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delPerson (id) {
      this.$http.post('/api/delAdmin', {
        mediaId: id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
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
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
