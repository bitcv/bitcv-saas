<template>
  <div class="basic">
    <div class="form-container">
      <el-form label-position="right" label-width="120px" :rules="ruleList" ref="formData" :model="formData">
        <el-form-item label="项目中文名" prop="nameCn">
          <el-input v-model="formData.nameCn" placeholder="请输入项目中文名"></el-input>
        </el-form-item>
        <el-form-item label="项目英文名" prop="nameEn">
          <el-input v-model="formData.nameEn" placeholder="请输入项目英文名"></el-input>
        </el-form-item>
        <el-form-item label="项目主页" prop="homeUrl">
          <el-input v-model="formData.homeUrl" placeholder="请输入项目主页地址"></el-input>
          <a :href="formData.homeUrl" target="_blank" v-if="formData.homeUrl" style="color:red">项目主页链接</a>
        </el-form-item>
        <el-form-item label="项目logo" prop="logoUrl" required>
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false" style="display: inline-flex">
            <i class="el-icon-plus"></i>
            <img :src="formData.logoUrl" alt="">
          </el-upload>
          <span>建议 128*128 的 png 格式图片</span>
        </el-form-item>
        <el-form-item label="项目横幅" prop="bannerUrl">
          <el-upload class="upload-box banner" name="picture" action="/api/uploadFile" :on-success="onBannerSuccess" :show-file-list="false" style="display: inline-flex">
            <i class="el-icon-plus"></i>
            <img :src="formData.bannerUrl" alt="">
          </el-upload>
          <span>建议上传宽度为 600 像素的图片</span>
        </el-form-item>
        <el-form-item label="一句话描述" prop="shortDesc">
          <el-input v-model="formData.shortDesc" placeholder="请输入项目的一句话概括"></el-input>
        </el-form-item>
        <el-form-item class="tag-item" label="项目标签" prop="tagList">
          <el-tag class="tag-text" :key="index" v-for="(tag, index) in formData.tagList" closable :disable-transitions="false" @close="removeTag(index)"> {{tag}} </el-tag>
          <el-input v-if="tagInputVisible" class="tag-input" ref="tagInput" v-model="inputTag" size="small" @keyup.enter.native="addTag" @blur="addTag" >
          </el-input>
            <el-button v-else class="tag-btn" size="small" @click="showTagInput">+ 添加标签</el-button>
        </el-form-item>
        <el-form-item label="项目成立日期" prop="foundDate" required>
          <el-date-picker v-model="formData.foundDate" type="date" placeholder="请选择项目成立日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="项目简介" prop="abstract">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 8}" v-model="formData.abstract" placeholder="请输入项目简介"></el-input>
        </el-form-item>
        <el-form-item label="项目白皮书" prop="whitePaperUrl" required>
          <el-upload class="upload-box" name="whitePaper" action="/api/uploadFile" :on-success="onWhitePaperSuccess" :show-file-list="false" style="display: inline-flex" accept="*.pdf">
            <i class="el-icon-plus"></i>
            <img :src="formData.whitePaperUrl ? '/storage/static/pdf.jpg' : ''" alt="">
          </el-upload>
          <a :href="formData.whitePaperUrl" target="_blank" style="color: red;">白皮书链接</a>
          <span>请上传项目白皮书 pdf 文件</span>
        </el-form-item>
        <el-form-item :label="select.label" v-for="(select, field) in selectList" :key="field" required>
          <el-select v-model="formData[field]">
            <el-option v-for="option in select.optionList" :key="option.value" :label="option.label" :value="option.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="融资开始日期" prop="fundStartTime">
          <el-date-picker v-model="formData.fundStartTime" type="date" placeholder="请选择融资开始日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="融资结束日期" prop="fundEndTime">
          <el-date-picker v-model="formData.fundEndTime" type="date" placeholder="请选择融资结束日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="融资阶段" prop="fundStage">
          <el-select v-model="formData.fundStage">
            <el-option label="保密" :value="0"></el-option>
            <el-option label="未融资" :value="1"></el-option>
            <el-option label="融资中" :value="2"></el-option>
            <el-option label="已融资" :value="3"></el-option>
          </el-select>
        </el-form-item>
        <!--<el-form-item label="通证名称" prop="tokenName">-->
          <!--<el-input v-model="formData.tokenName" placeholder="请输入通证名称"></el-input>-->
        <!--</el-form-item>-->
        <!--<el-form-item label="通证符号" prop="tokenSymbol">-->
          <!--<el-input v-model="formData.tokenSymbol" placeholder="请输入通证符号"></el-input>-->
        <!--</el-form-item>-->
        <!--<el-form-item label="通证价格">-->
          <!--<el-input v-model="formData.tokenPrice" placeholder="请输入通证价格"></el-input>-->
        <!--</el-form-item>-->
        <el-form-item label="合约地址">
          <el-input v-model="formData.contractAddr" placeholder="请输入ERC20合约地址"></el-input>
        </el-form-item>
        <el-form-item label="联系邮箱">
          <el-input v-model="formData.companyEmail" placeholder="请输入联系邮箱"></el-input>
        </el-form-item>
        <el-form-item label="联系地址">
          <el-input v-model="formData.companyAddr" placeholder="请输入联系地址"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer-btn-area">
      <el-button type="primary" @click="submit">提交修改</el-button>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      inputTag: '',
      tagList: [],
      tagInputVisible: false,
      selectList: {},
      formData: {
        projId: '',
        nameCn: '',
        nameEn: '',
        shortDesc: '',
        abstract: '',
        homeUrl: '',
        logoUrl: '',
        bannerUrl: '',
        tagList: '',
        whitePaperUrl: '',
        region: '',
        buzType: '',
        stage: '',
        foundDate: '',
        fundStartTime: '',
        fundEndTime: '',
        fundStage: '',
        companyEmail: '',
        companyAddr: '',
        tokenName: '',
        tokenSymbol: '',
        tokenPrice: '',
        contractAddr: ''
      },
      ruleList: {
        nameCn: [
          { required: true, message: '请输入项目中文名', triger: 'blur' }
        ],
        nameEn: [
          { required: true, message: '请输入项目英文名', triger: 'blur' }
        ],
        homeUrl: [
          { required: true, message: '请输入项目主页地址', triger: 'blur' }
        ],
        shortDesc: [
          { required: true, message: '请输入项目一句话简介', triger: 'blur' }
        ],
        abstract: [
          { required: true, message: '请输入项目简介', triger: 'blur' }
        ],
        region: [
          { required: true, message: '请选择项目地区', triger: 'blur' }
        ],
        buzType: [
          { required: true, message: '请选择项目类型', triger: 'blur' }
        ],
        stage: [
          { required: true, message: '请选择项目阶段', triger: 'blur' }
        ],
        tokenName: [
          { required: true, message: '请输入通证名称', triger: 'blur' }
        ],
        tokenSymbol: [
          { required: true, message: '请输入通证符号', triger: 'blur' }
        ]
      }
    }
  },
  mounted () {
    this.formData.projId = this.$route.params.id
    this.$http.get('/api/getProjTagList').then((res) => {
      if (res.data.errcode === 0) {
        this.selectList = res.data.data
      }
    })
    this.updateData()
  },
  methods: {
    updateData: function () {
      this.$http.post('/api/getProjBasicInfo', {
        projId: this.formData.projId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.formData = res.data.data
          this.formData.projId = res.data.data.id
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.formData.logoUrl = res.data.url
      }
    },
    onBannerSuccess (res) {
      if (res.errcode === 0) {
        this.formData.bannerUrl = res.data.url
      }
    },
    onWhitePaperSuccess (res) {
      if (res.errcode === 0) {
        this.formData.whitePaperUrl = res.data.url
      }
    },
    showTagInput () {
      this.tagInputVisible = true
      this.$nextTick(_ => {
        this.$refs.tagInput.$refs.input.focus()
      })
    },
    addTag () {
      if (this.inputTag) {
        this.formData.tagList.push(this.inputTag)
      }
      this.tagInputVisible = false
      this.inputTag = ''
    },
    removeTag (index) {
      this.tagList.splice(index, 1)
    },
    submit () {
      this.$refs['formData'].validate((valid) => {
        if (!valid) {
          this.$message({
            message: '请填写所有必填项',
            type: 'warning'
          })
        } else {
          this.$http.post('/api/updProjBasicInfo', this.formData).then((res) => {
            if (res.data.errcode === 0) {
              this.$message({ type: 'success', message: '修改成功!' })
              this.updateData()
            }
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.form-container {
  padding: 0 20px;
  .tag-item {
    .tag-text {
      margin-right: 10px;
    }
    .tag-input {
      width: 90px;
    }
  }
  .upload-box.banner {
    width: 250px;
  }
}
</style>
