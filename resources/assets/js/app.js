// function createjscssfile(filename, filetype){
//     if (filetype=="js"){ //if filename is a external JavaScript file
//         var fileref=document.createElement('script')
//         fileref.setAttribute("type","text/javascript")
//         fileref.setAttribute("src", filename)
//     }
//
//     else if (filetype=="css"){ //if filename is an external CSS file
//         var fileref=document.createElement("link")
//         fileref.setAttribute("rel", "stylesheet")
//         fileref.setAttribute("type", "text/css")
//         fileref.setAttribute("href", filename)
//     }
//     return fileref
// }
//
// function replacejscssfile(oldfilename, newfilename, filetype){
//     var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist using
//     var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
//     var allsuspects=document.getElementsByTagName(targetelement)
//     for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
//         if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(oldfilename)!=-1){
//             var newelement=createjscssfile(newfilename, filetype)
//             allsuspects[i].parentNode.replaceChild(newelement, allsuspects[i])
//         }
//     }
// }




require('./bootstrap');
import store from './store'
import inputs from './components/inputs'
import Draggable from 'vuedraggable'
import bus from './bus'

var x = store.state.selectedTheme;



Vue.component('oard', require('./components/MobBoard'));
Vue.component('front-panel', require('./components/FrontPanel'));
Vue.component('admin-panel', require('./components/AdminPanel'));
Vue.component('mob-timer', require('./components/Timer'));
Vue.component('mob-notes', require('./components/Notes'));
Vue.component('mob-tasks', require('./components/Tasks'));
Vue.component('mob-comments', require('./components/Comments'));
Vue.component('participants', require('./components/Participants'));
Vue.component('mob-monitor', require('./components/Monitor'));
Vue.component('confirm-alert', require('./components/Confirm'));
Vue.component('mob-bookmarks', require('./components/Bookmarks'));

Vue.component('draggable', Draggable);
const app = new Vue({
    store,
    el: '#app'
});
bus.$on('selectedTheme', value => {
   // if(value == 'flatly'){
   //      require("../sass/flatly/bootstrap.min.css");
   //  }
    // else if(value == 'cerulean'){
    //     require("../sass/cerulean/bootstrap.min.css");
    // }else if(value == 'solar'){
    //     require("../sass/solar/bootstrap.min.css");
    // }else if(value == 'cosmo'){
    //     require("../sass/cosmo/bootstrap.min.css");
    // }



    // Variables











});