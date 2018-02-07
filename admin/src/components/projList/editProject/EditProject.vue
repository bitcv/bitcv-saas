<template>
  <div class="edit-project">
    <el-tabs type="border-card">
      <el-tab-pane label="基本信息">
        <basic></basic>
      </el-tab-pane>
      <el-tab-pane label="团队成员">
        <member></member>
      </el-tab-pane>
      <el-tab-pane label="发展历程">
        <event></event>
      </el-tab-pane>
      <el-tab-pane label="社交信息">
        <social></social>
      </el-tab-pane>
      <el-tab-pane label="项目顾问">
        <advisor></advisor>
      </el-tab-pane>
      <el-tab-pane label="合作伙伴">
        <partner></partner>
      </el-tab-pane>
      <el-tab-pane label="媒体报道">
        <report></report>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import Basic from '@/components/projList/editProject/Basic'
import Member from '@/components/projList/editProject/Member'
import Event from '@/components/projList/editProject/Event'
import Social from '@/components/projList/editProject/Social'
import Advisor from '@/components/projList/editProject/Advisor'
import Partner from '@/components/projList/editProject/Partner'
import Report from '@/components/projList/editProject/Report'

export default {
  data () {
    return {
      projId: 0
    }
  },
  components: {
    Basic,
    Event,
    Member,
    Social,
    Advisor,
    Partner,
    Report
  }
}
</script>

<style lang="scss" scoped>
</style>
