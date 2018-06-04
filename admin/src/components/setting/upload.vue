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
                                <img :src="scope.row.packetCover" alt="" style="max-width: 150px;max-height: 150px;">
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
                    <el-form label-width="165px" v-if="pid != 4">
                        <el-form-item label="上传糖包儿宣传图片：" required>
                            <el-upload class="upload-box" name="saasPacketPic" action="/api/uploadFile" :on-success="onRarSuccess" :show-file-list="false" style="display: inline-flex">
                                <i class="el-icon-plus"></i>
                                <img :src="formData.pic ? formData.pic : ''" alt="">
                            </el-upload>
                        </el-form-item>
                        <el-form-item>
                            <p style="color: red">请上传 1240(宽) x 720(高) 像素，图片主色调是 fd6565，查看红包页面的红包颜色是 ee3230</p>
                        </el-form-item>
                    </el-form>
                    <el-form label-width="165px" v-else>
                        <el-form-item label="上传糖包儿宣传图片：" required>
                            <el-upload class="upload-demo" name="redpacket" action="/api/uploadFile" :on-success="successUpload" :on-remove="handleRemove" :file-list="fileList2" list-type="picture">
                            <el-button size="small" type="primary">点击上传</el-button>
                            <!--<div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>-->
                            </el-upload>
                        </el-form-item>
                        <!--<el-form-item>-->
                            <!--<p style="color: red">请上传 1240(宽) x 720(高) 像素，图片主色调是 fd6565，查看红包页面的红包颜色是sd ee3230</p>-->
                        <!--</el-form-item>-->
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
      },
      file2: [],
      temppic: '',
      fileList2: [],
      completepic: ''
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
          var pictures = res.data.data.pic
          this.completepic = res.data.data.pic
          this.picture = pictures[0].packet_cover.split(',')
          for (var i = 0; i < this.picture.length; i++) {
            if (this.picture[i] === '' || typeof (this.picture[i]) === 'undefined') {
              this.picture.splice(i, 1)
              i = i - 1
            }
          }
          this.picture = [{pid: pictures[0]['id'], packetCover: this.picture[0]}]
        }
      })
    },
    onRarSuccess (res) {
      if (res.errcode === 0) {
        this.formData.pic = res.data.url
      }
    },
    submit () {
      if (this.pid === 4) {
        this.temppic = this.file2.join(',')
        var result = []
        // 拼接已上传的图片的 url
        for (var i = 0; i < this.fileList2.length; i++) {
          result.push(this.fileList2[i].url)
        }
        var picurl = this.temppic
        this.temppic = result.join(',') + ',' + picurl
      } else {
        this.temppic = this.formData.pic
      }
      this.$http.post('/api/addPacketPic', {
        pid: this.pid,
        pic: this.temppic
      }).then((res) => {
        this.$message({ type: 'success', message: this.pid ? '更新成功!' : '添加成功' })
        this.showDialog = false
        this.getPacketPic()
      })
    },
    showEdit (index) {
      var pictureData = this.picture[index]
      // this.vid = pictureData.vid
      var completePicData = this.completepic[index]
      if (this.pid !== 4) {
        this.formData.pic = pictureData.packetCover
      } else {
        var aa = []
        for (var i = 0; i < completePicData.packet_cover.split(',').length; i++) {
          if (completePicData.packet_cover.split(',')[i]) {
            aa.push({url: completePicData.packet_cover.split(',')[i]})
          }
        }
        this.fileList2 = aa
      }
      this.showDialog = true
    },
    handleRemove (file, fileList) {
      if (file.status === 'success') {
        file.url = ''
      }
    },
    successUpload (response, file) {
      if (response.errcode === 0) {
        this.file2.push(response.data.url)
      }
    }
  }
}

</script>
<style lang="scss" scoped>
</style>
