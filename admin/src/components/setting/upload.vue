<template>
    <div class="deposit-box">
        <el-tabs type="border-card">
            <div class="header-btn-area">
              <!--<el-button type="primary" @click="showAdd">添加图片</el-button>-->
            </div>
            <el-tab-pane label="图片上传：">
                <el-table :data="picture">
                    <el-table-column label="图片">
                        <template slot-scope="scope">
                            <a :href="scope.row.packet_cover" target="_blank">
                                <img :src="scope.row.packet_cover" alt="" style="max-width: 150px;max-height: 150px;">
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button type="success" size="mini" @click="showEdit(scope.$index)">编辑</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-dialog title="上传图片" :visible.sync="showDialog" center>
                    <el-form label-width="165px">
                        <el-form-item label="上传糖包儿宣传图片：" required>
                            <el-upload class="upload-box" name="saasPacketPic" action="/api/uploadFile" :on-success="onRarSuccess" :show-file-list="false" style="display: inline-flex">
                                <i class="el-icon-plus"></i>
                                <img :src="formData.pic ? formData.pic : ''" alt="">
                            </el-upload>
                        </el-form-item>
                        <el-form-item>
                            <p style="color: red">请上传 1240(宽) x 720(高) 像素图片</p>
                        </el-form-item>
                    </el-form>
                    <div slot="footer">
                        <el-button @click="showDialog = false">取消</el-button>
                        <el-button type="primary" @click="submit">提交</el-button>
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
      pid: '',
      picture: [],
      formData: {
        pic: ''
      }
    }
  },
  mounted () {
    this.$http.post('/api/getPid', {
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.pid = res.data.data.tokenId.tokenid
        this.getPacketPic()
      }
    })
  },
  methods: {
    showAdd () {
      this.formData.pic = ''
      this.showDialog = true
    },
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.pid = res.data.data.tokenId.tokenid
        }
      })
    },
    getPacketPic () {
      this.$http.post('/api/getPacketPic', {
        pid: this.pid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.picture = res.data.data.pic
        }
      })
    },
    onRarSuccess (res) {
      if (res.errcode === 0) {
        this.formData.pic = res.data.url
      }
    },
    submit () {
      this.$http.post('/api/addPacketPic', {
        pid: this.pid,
        pic: this.formData.pic
      }).then((res) => {
        this.$message({ type: 'success', message: this.pid ? '更新成功!' : '添加成功' })
        this.showDialog = false
        this.getPacketPic()
      })
    },
    showEdit (index) {
      var pictureData = this.picture[index]
      this.vid = pictureData.vid
      this.formData.pic = pictureData.packet_cover
      this.showDialog = true
    }
  }
}

</script>
<style lang="scss" scoped>
</style>
