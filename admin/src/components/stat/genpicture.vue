<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="生成链讯">
        <div class="header-btn-area">
          <el-button type="primary" @click="showAdd()">添加</el-button>
        </div>
        <el-table>
          <el-table-column label="图片">
            <template slot-scope="scope"></template>
          </el-table-column>
          <el-table-column label="生成时间">
            <template slot-scope="scope"></template>
          </el-table-column>
        </el-table>
        <el-dialog :title="'生成链讯'" :visible.sync="showDialog" center>
          <el-form label-width="120px">
             <el-form-item label="编号：">
              <el-input type="number" v-model="formData.no">
              </el-input>
            </el-form-item>
            <el-form-item label="时间：">
              <el-input type="text"  placeholder="日期格式：2019-01-01" v-model="formData.oldTime">
              </el-input>
            </el-form-item>
            <el-form-item label="标题：">
              <el-input type="text" placeholder="标题，不超过20字" v-model="formData.title">
              </el-input>
            </el-form-item>
            <el-form-item label="内容：">
              <el-input type="textarea" :autosize="{ minRows: 4, maxRows: 12}" placeholder="内容" v-model="formData.content">
              </el-input>
            </el-form-item>
          </el-form>
          <div slot="footer">
              <el-button @click="showDialog = false">取消</el-button>
              <el-button type="primary" @click="submit()">生成图片</el-button>
          </div>
        </el-dialog>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>

export default {
  data () {
    return {
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      token: '',
      loading: true,
      showDialog: false,
      projId: 0,
      formData: {
        oldTime: '',
        title: '',
        content: '',
        no: 0
      }
    }
  },
  mounted () {
    this.getPid()
    this.getGenPicList()
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.projId = res.data.data.projectid
        }
      })
    },
    showAdd () {
      this.formData.no = ''
      this.formData.title = ''
      this.formData.oldTime = ''
      this.formData.content = ''
      this.showDialog = true
    },
    submit () {
      if (this.projId !== 2) {
        this.showDialog = false
      }
      this.$http.post('/api/genLianXunPic', {
        projId: this.projId,
        no: this.formData.no,
        title: this.formData.title,
        oldTime: this.formData.oldTime,
        content: this.formData.content
      }).then((res) => {
        // this.$message({ type: 'success', message: this.mid ? '更新成功!' : '添加成功' })
        console.log(res)
        this.showDialog = false
      })
    },
    getGenPicList () {
      this.$http.post('/api/getLianXunPicList', {
        pageno: this.pageno,
        perpage: this.perpage,
        projId: this.projId
      }).then((res) => {
        console.log(res)
        this.showDialog = false
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
