<template>
  <div class="event">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="eventList">
      <el-table-column label="时间">
        <template slot-scope="scope">{{ scope.row.occurTime }}</template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="详情">
        <template slot-scope="scope">{{ scope.row.detail }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="项目重大事件" :visible.sync="showDialog" center>
      <el-form label-width="50px">
        <el-form-item label="时间">
          <el-date-picker v-model="inputOccurTime" type="datetime" default-value="2017-01-01T00:00:00" placeholder="请选择时间"></el-date-picker>
        </el-form-item>
        <el-form-item label="标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="描述">
          <el-input type="textarea" v-model="inputDetail"></el-input>
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
      inputOccurTime: '',
      inputTitle: '',
      inputDetail: '',
      eventList: [],
      eventId: ''
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjEventList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.eventList = res.data.data.dataList
        }
      })
    },
    showEdit (index) {
      var eventInfo = this.eventList[index]
      this.eventId = eventInfo.id
      this.inputOccurTime = eventInfo.occurTime
      this.inputTitle = eventInfo.title
      this.inputDetail = eventInfo.detail
      this.showDialog = true
    },
    showDel (eventId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delEvent(eventId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.eventId) {
        this.updEvent()
      } else {
        this.addEvent()
      }
    },
    addEvent () {
      this.$http.post('/api/addProjEvent', {
        projId: this.$route.params.id,
        occurTime: this.inputOccurTime,
        title: this.inputTitle,
        detail: this.inputDetail
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputOccurTime = ''
          this.inputTitle = ''
          this.inputDetail = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updEvent () {
      this.$http.post('/api/updProjEvent', {
        eventId: this.eventId,
        occurTime: this.inputOccurTime,
        title: this.inputTitle,
        detail: this.inputDetail
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputOccurTime = ''
          this.inputTitle = ''
          this.inputDetail = ''
          this.showDialog = false
          this.updateData()
          this.eventId = ''
          this.updateData()
        }
      })
    },
    delEvent (eventId) {
      this.$http.post('/api/delProjEvent', {
        eventId: eventId
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
.event {
  .event-container {
    padding: 20px;
    border: 1px solid yellow;
    margin-bottom: 30px;
    .title {
      width: 100%;
      text-align: center;
      line-height: 28px;
      margin-bottom: 10px;
    }
  }
}
</style>
