import Vue from 'vue'

const files = require.context('./', false, /\.vue$/)

files.keys().forEach((key) => {

    let fileName = key.replace(/(\.\/|\.vue)/g, '')

    Vue.component("input" + fileName, require('./' + fileName))

})
