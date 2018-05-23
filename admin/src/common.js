/* eslint-disable */
exports.install = function (Vue, options) {
  Vue.prototype.convertFundStage = function (index) {
    switch (index) {
      case 0:
        return '保密'
      case 1:
        return '未融资'
      case 2:
        return '融资中'
      case 3:
        return '已融资'
    }
  }
  Vue.prototype.convertBuzType = function (index) {
    switch (index) {
      case 1:
        return '金融'
      case 2:
        return '数字货币'
      case 3:
        return '娱乐'
      case 4:
        return '供应链管理'
      case 5:
        return '法律服务'
      case 6:
        return '医疗'
      case 7:
        return '能源服务'
      case 8:
        return '公益'
      case 9:
        return '物联网'
      case 10:
        return '农业'
      case 11:
        return '社交'
      default:
        return '其它'
    }
  }
  Vue.prototype.getShortStr = function (string, num) {
    if (string.length <= num) {
      return string
    }
    return string.substr(0, num) + '...' + string.substr(-1 * num)
  }
  Vue.prototype.convertOrderStatus = function (index) {
    switch (index) {
      case 0:
        return '待支付'
        break
      case 1:
        return '已完成'
        break
      case 2:
        return '已取消'
        break
      case 3:
        return '已过期'
        break
      default:
        return '未知状态'
        
    }
  }
  Vue.prototype.convertDate = function (dateTimeStamp) {
    var minute = 1000 * 60;
    var hour = minute * 60;
    var day = hour * 24;
    var halfamonth = day * 15;
    var month = day * 30;

    var now = new Date().getTime();
    var diffValue = now - new Date(dateTimeStamp).getTime();

    var monthC = diffValue/month;
    var weekC = diffValue/(7*day);
    var dayC = diffValue/day;
    var hourC = diffValue/hour;
    var minC = diffValue/minute;
    if(hourC > 24){
      var result = parseInt(dayC) + '天前';
    } else if(hourC>=1){
      var result = parseInt(hourC) +"个小时前";
    } else if(minC>=1){
      var result = parseInt(minC) +"分钟前";
    } else {
      var result = "刚刚"
    }
    return result;
  }
  Array.prototype.indexOf = function (val) {
    for (var i = 0; i < this.length; i++) {
      if (this[i] === val) return i
    }
    return -1
  }
  Array.prototype.remove = function (val) {
    var index = this.indexOf(val)
    if (index > -1) {
      this.splice(index, 1)
    }
  }
}