<template>
  <div class="basic">
    <div class="form-container">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="项目中文名">
          <el-input v-model="formData.nameCn" placeholder="请输入项目中文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目英文名">
          <el-input v-model="formData.nameEn" placeholder="请输入项目英文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目主页">
          <el-input v-model="formData.homeUrl" placeholder="请输入项目主页地址">
            <template slot="prepend">http://</template>
          </el-input>
        </el-form-item>
        <el-form-item label="项目logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="formData.logoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="项目横幅">
          <el-upload class="upload-box" name="picture" action="/api/uploadFile" :on-success="onBannerSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="formData.bannerUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="一句话描述">
          <el-input v-model="formData.shortDesc" placeholder="请输入项目的一句话概括"></el-input>
        </el-form-item>
        <el-form-item class="tag-item" label="项目标签">
          <el-tag class="tag-text" :key="index" v-for="(tag, index) in formData.tagList" closable :disable-transitions="false" @close="removeTag(index)"> {{tag}} </el-tag>
          <el-input v-if="tagInputVisible" class="tag-input" ref="tagInput" v-model="inputTag" size="small" @keyup.enter.native="addTag" @blur="addTag" >
          </el-input>
            <el-button v-else class="tag-btn" size="small" @click="showTagInput">+ 添加标签</el-button>
        </el-form-item>
        <el-form-item label="项目简介">
          <el-input type="textarea" v-model="formData.abstract" placeholder="请输入项目简介"></el-input>
        </el-form-item>
        <el-form-item label="项目白皮书">
          <el-upload class="upload-box" name="whitePaper" action="/api/uploadFile" :on-success="onWhitePaperSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="formData.whitePaperUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item :label="select.label" v-for="(select, field) in selectList" :key="field">
          <el-select v-model="formData[field]">
            <el-option v-for="(option, index) in select.optionList" :key="option.value" v-if="index" :label="option.label" :value="option.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="融资开始时间">
          <el-date-picker v-model="formData.fundStartTime" type="datetime" default-value="2018-01-01T00:00:00" placeholder="请选择融资开始日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="融资结束时间">
          <el-date-picker v-model="formData.fundEndTime" type="datetime" default-value="2018-01-01T00:00:00" placeholder="请选择融资结束日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="融资阶段">
          <el-select v-model="formData.fundStage">
            <el-option label="保密" :value="0"></el-option>
            <el-option label="未融资" :value="1"></el-option>
            <el-option label="融资中" :value="2"></el-option>
            <el-option label="已融资" :value="3"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="代币名称">
          <el-input v-model="formData.tokenName" placeholder="请输入代币名称"></el-input>
        </el-form-item>
        <el-form-item label="代币符号">
          <el-input v-model="formData.tokenSymbol" placeholder="请输入代币符号"></el-input>
        </el-form-item>
        <el-form-item label="代币价格">
          <el-input v-model="formData.tokenPrice" placeholder="请输入代币价格"></el-input>
        </el-form-item>
        <el-form-item label="公司邮箱">
          <el-input v-model="formData.companyEmail" placeholder="请输入公司邮箱"></el-input>
        </el-form-item>
        <el-form-item label="公司地址">
          <el-input v-model="formData.companyAddr" placeholder="请输入公司地址"></el-input>
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
        tokenPrice: ''
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
      this.$http.post('/api/updProjBasicInfo', this.formData).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '修改成功!' })
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
}
</style>
