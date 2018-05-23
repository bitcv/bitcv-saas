<template>
  <div class="add-project">
    <div class="form-container">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="项目中文名">
          <el-input v-model="formData.nameCn" placeholder="请输入项目中文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目英文名">
          <el-input v-model="formData.nameEn" placeholder="请输入项目英文名" required></el-input>
        </el-form-item>
        <el-form-item label="项目创建时间">
          <el-date-picker v-model="formData.foundDate" type="datetime" default-value="2018-01-01T00:00:00" placeholder="请选择项目创建日期">
          </el-date-picker>
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
        <el-form-item label="一句话描述">
          <el-input v-model="formData.shortDesc" placeholder="请输入项目的一句话概括"></el-input>
        </el-form-item>
        <el-form-item label="项目简介">
          <el-input type="textarea" v-model="formData.abstract" placeholder="请输入项目简介" :autosize="{ minRows: 3, maxRows: 10}"></el-input>
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
        <el-form-item label="融资阶段">
          <el-select v-model="formData.fundStage">
            <el-option label="保密" :value="0"></el-option>
            <el-option label="未融资" :value="1"></el-option>
            <el-option label="融资中" :value="2"></el-option>
            <el-option label="已融资" :value="3"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer-btn-area">
      <el-button type="primary" @click="submit">提交</el-button>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      selectList: {},
      selectResult: {},
      formData: {
        nameCn: '',
        nameEn: '',
        shortDesc: '',
        abstract: '',
        homeUrl: '',
        logoUrl: '',
        whitePaperUrl: '',
        region: '',
        buzType: '',
        stage: '',
        foundDate: '',
        fundStage: ''
      }
    }
  },
  mounted () {
    this.$http.get('/api/getProjTagList').then((res) => {
      if (res.data.errcode === 0) {
        var resData = res.data.data
        this.selectList = resData
        for (let field in resData) {
          this.$set(this.selectResult, field, '')
        }
      }
    })
  },
  methods: {
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.formData.logoUrl = res.data.url
      }
    },
    onWhitePaperSuccess (res) {
      if (res.errcode === 0) {
        this.formData.whitePaperUrl = res.data.url
      }
    },
    submit () {
      this.$http.post('/api/addProject', this.formData).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '创建成功!' })
          this.$router.push('/admin/project')
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
