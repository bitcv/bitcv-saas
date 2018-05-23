<template>
  <div class="partner">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="partnerList">
      <el-table-column label="Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="交易所名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="主页url">
        <template slot-scope="scope">{{ scope.row.homeUrl }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <!-- <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button> -->
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="交易所信息" :visible.sync="showDialog" center>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="直接选择" name="first">
          <el-form label-width="80px">
            <el-form-item label="名称">
              <el-select v-model="exchangeId" placeholder="请选择交易所信息">
              <el-option v-for="(exchange, index) in exchangeOptionList" :key="index" :value="exchange.id" :label="exchange.name"></el-option>
              </el-select>
            </el-form-item>
          </el-form>
        </el-tab-pane>
        <el-tab-pane label="手动输入" name="second">
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
        </el-tab-pane>
      </el-tabs>
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
      activeName: 'first',
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputHomeUrl: '',
      exchangeId: '',
      partnerList: [],
      exchangeOptionList: []
    }
  },
  mounted () {
    this.updateData()
    this.getExchangeOptionList()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjExchangeList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.partnerList = res.data.data.dataList
        }
      })
    },
    handleClick (tab, event) {
    },
    getExchangeOptionList () {
      this.$http.get('/api/getExchangeNameList').then((res) => {
        if (res.data.errcode === 0) {
          this.exchangeOptionList = res.data.data.exchangeList
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
      var partnerOption = this.exchangeOptionList[index]
      this.partnerId = partnerInfo.id
      this.exchangeId = partnerOption.id
      this.inputName = partnerInfo.name
      this.inputLogoUrl = partnerInfo.logoUrl
      this.inputHomeUrl = partnerInfo.homeUrl
      this.showDialog = true
    },
    showAdd () {
      this.exchangeId = ''
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
      if (this.activeName === 'first') {
        this.addPartner()
      } else {
        this.addInputPartner()
      }
    },
    addPartner () {
      this.$http.post('/api/addProjExchange', {
        projId: this.$route.params.id,
        memberId: this.exchangeId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputHomeUrl = ''
          this.exchangeId = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    addInputPartner () {
      this.$http.post('/api/addProjIExchange', {
        projId: this.$route.params.id,
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
          this.exchangeId = ''
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
          this.exchangeId = ''
          this.updateData()
        }
      })
    },
    delPartner (partnerId) {
      this.$http.post('/api/delProjExchange', {
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
