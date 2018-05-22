<template>
    <div class="deposit-box">
        <el-tabs type="border-card">
            <div class="header-btn-area">
              <el-button type="primary" @click="showAdd">添加图片</el-button>
            </div>
            <el-tab-pane label="图片上传：">
                <el-table>
                    <el-table-column label="图片">
                        <template slot-scope="scope"></template>
                    </el-table-column>

                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button type="success" size="mini">详情</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-dialog title="上传图片" :visible.sync="showDialog" center>
                    <el-form label-width="120px">
                        <el-form-item label="上传红包图片：" required>
                            <el-upload class="upload-box" name="saasPacketPic" action="/api/uploadFile" :on-success="onRarSuccess" :show-file-list="false" style="display: inline-flex">
                                <i class="el-icon-plus"></i>
                                <img :src="formData.pic ? formData.pic : ''" alt="">
                            </el-upload>
                        </el-form-item>
                    </el-form>
                    <div slot="footer">
                        <el-button @click="showDialog = false">取消</el-button>
                        <el-button type="primary">提交</el-button>
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
      showDialog: false,
      formData: {
        pic: ''
      }
    }
  },
  mounted () {
  },
  methods: {
    showAdd () {
      this.formData.pic = ''
      this.showDialog = true
    },
    getPacketPic () {
      this.$http.post('/api/getPacketPic', {

      }).then((res) => {
      })
    },
    onRarSuccess (res) {
      if (res.errcode === 0) {
         this.formData.pic = res.data.url
      }
    },
  }
}

</script>
<style lang="scss" scoped>
</style>
