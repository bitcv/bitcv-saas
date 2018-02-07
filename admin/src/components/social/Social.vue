<template>
  <div class="social">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="socialList">
      <el-table-column label="社群Logo">
        <template slot-scope="scope">
          <i class="fab" :class="scope.row.fontClass"></i>
        </template>
      </el-table-column>
      <el-table-column label="社群名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="社群信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="社群名称">
          <el-input v-model="inputName" placeholder="请输入社群名称"></el-input>
        </el-form-item>
        <el-form-item label="Logo字体图标类">
          <el-input v-model="inputFontClass" placeholder="请输入社群logo的字体图标类"></el-input>
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
      inputFontClass: '',
      socialList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.get('/api/getSocialList').then((res) => {
        if (res.data.errcode === 0) {
          this.socialList = res.data.data.dataList
        }
      })
    },
    showAdd () {
      this.socialId = ''
      this.inputName = ''
      this.inputFontClass = ''
      this.showDialog = true
    },
    showEdit (index) {
      var socialData = this.socialList[index]
      this.socialId = socialData.id
      this.inputName = socialData.name
      this.inputFontClass = socialData.fontClass
      this.showDialog = true
    },
    showDel (socialId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delSocial(socialId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.socialId) {
        this.updSocial()
      } else {
        this.addSocial()
      }
    },
    addSocial () {
      this.$http.post('/api/addSocial', {
        name: this.inputName,
        fontClass: this.inputFontClass
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updSocial () {
      this.$http.post('/api/updSocial', {
        socialId: this.socialId,
        name: this.inputName,
        fontClass: this.inputFontClass
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delSocial (socialId) {
      this.$http.post('/api/delSocial', {
        socialId: socialId
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
