<template>
  <div class="partner">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="partnerList">
      <el-table-column label="Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="主页url">
        <template slot-scope="scope">{{ scope.row.homeUrl }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="合作伙伴信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="名称">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="主页url">
          <el-input v-model="inputHomeUrl">
            <template slot="prepend" placeholder="请输入主页地址">http://</template>
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
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputHomeUrl: '',
      partnerList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjPartnerList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.partnerList = res.data.data.dataList
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    showEdit (index) {
      var partnerInfo = this.partnerList[index]
      this.partnerId = partnerInfo.id
      this.inputName = partnerInfo.name
      this.inputLogoUrl = partnerInfo.logoUrl
      this.inputHomeUrl = partnerInfo.homeUrl
      this.showDialog = true
    },
    showDel (partnerId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delPartner(partnerId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.partnerId) {
        this.updPartner()
      } else {
        this.addPartner()
      }
    },
    addPartner () {
      this.$http.post('/api/addProjPartner', {
        projId: this.$route.params.id,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        homeUrl: this.inputHomeUrl
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputHomeUrl = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updPartner () {
      this.$http.post('/api/updProjPartner', {
        partnerId: this.partnerId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        homeUrl: this.inputHomeUrl
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputHomeUrl = ''
          this.showDialog = false
          this.partnerId = ''
          this.updateData()
        }
      })
    },
    delPartner (partnerId) {
      this.$http.post('/api/delProjPartner', {
        partnerId: partnerId
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
