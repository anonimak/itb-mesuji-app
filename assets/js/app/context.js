var context = {
    app:$('#app'),
    module:$('#app').data('module'),
    base_url:base_url,
    load: function (strpath) {
      return `${this.base_url}assets/js/app/${strpath}.js`;
    },
    loadModule: function () {
      return `${this.base_url}assets/js/app/module/${this.module}.js`;
    },
    capitalize : (s) => {
      if (typeof s !== 'string') return ''
      return s.charAt(0).toUpperCase() + s.slice(1)
    }
  }
