export const setAdminDisplayOff = (state) =>  state.adminDisplay = false
export const setAdminDisplayOn = (state) =>  state.adminDisplay = true
export const setAdminLoader = (state, value) =>  state.adminLoader = value
export const persistOn = (state) =>  state.persist = true
export const persistOff = (state) =>  state.persist = false
export const setMobName = (state, name) => state.mobName = name
export const setFeatureNameOption = (state, value) => state.featureNameOption = value
export const setFeatureName = (state, name) => state.featureName = name

export const setJiraUrl = (state, url) => state.url = url
export const setJiraName = (state, username) => state.username = username
export const setJiraPassword = (state, password) => state.password = password

export const setJiraTaskDetails = (state, newTask) => state.jiraTasks.unshift(newTask)
export const commentAdd = (state, newTask) => state.comments.unshift(newTask)
export const commentDelete = (state, index) => state.comments.splice(index, 1)
export const commentsSync = (state, comments) => state.comments = comments
export const commentsSetOrder = (state, value) => state.commentsOptions.order = value
//export const jiraTaskDelete = (state, index) => state.jiraTasks.splice(index, 1)

export const createdOn = (state) => state.created = true
export const createdOff = (state) => state.created = false
export const setSlug = (state, slug) => state.slug = slug

export const noteAdd = (state, newNote) =>  state.notes.unshift(newNote)
export const noteDelete = (state, index) => state.notes.splice(index, 1)
export const notesSync = (state, notes) => state.notes = notes
export const notesSetOrder = (state, value) => state.notesOptions.order = value

export const bookmarkAdd = (state, newBookmark) =>  state.bookmarks.unshift(newBookmark)
export const bookmarkDelete = (state, index) => state.bookmarks.splice(index, 1)
export const bookmarkSync = (state, bookmarks) => state.bookmarks = bookmarks
export const bookmarkSetOrder = (state, value) => state.bookmarksOptions.order = value

export const taskAdd = (state, newTask) =>  state.tasks.unshift(newTask)
export const taskCompleted = (state, task) => task.completed = !task.completed
export const taskSelectedToAddComment = (state,task) => {
    if(task.task_type=='JIRA') {
        state.selectedTaskKey = task.task_key
        state.selectedTaskName = task.task_name
    }else{
        state.selectedTaskKey = ''
    }
}
export const activateTab = (state,value) => {
    state.isActive = value
}
export const taskDelete = (state, index) => state.tasks.splice(index, 1)
export const tasksRemoveCompleted = (state) => state.tasks = state.tasks.filter(task => !task.completed)
export const tasksSync = (state, tasks) => state.tasks = tasks
export const tasksSetOrder = (state, value) => state.tasksOptions.order = value


export const timerSetInterval = (state, interval) => state.timer.interval = interval
export const timerSetDuration = (state, duration) => state.timer.duration = duration
export const timerSetSessionLength = (state, length) => state.timerOptions.sessionLength = length
export const timerSetBreakIntervals = (state, length) => state.timerOptions.breakIntervals = length
export const timerToggleFlashBrowser    = (state, value) => state.timerOptions.flashBrowser = value
export const timerTogglePlaySound       = (state, value) => state.timerOptions.playSound = value
export const timerToggleSuggestBreaks   = (state, value) => state.timerOptions.suggestBreaks = value
export const timerBuildDuration = (state) => state.timer.duration = moment.duration(state.timerOptions.sessionLength * 60000, 'milliseconds')
export const timerCreate = (state) => state.timer.created = true
export const timerPaused = (state) => state.timer.paused = true
export const timerUnpaused = (state) => state.timer.paused = false
export const timerBreakOn = (state) => state.timer.onBreak = true
export const timerBreakOff = (state) => state.timer.onBreak = false
export const timerResetCounter = (state) => state.timer.count = 0
export const timerIncrement = (state) => state.timer.count++
export const timerPlayFinishSound = (state) => state.timer.audio.play()
export const timerStopFinishSound = (state) => {
    state.timer.audio.pause()
    state.timer.audio.currentTime = 0
}

export const participantAdd = (state, newParticipant) =>  {
    state.participants.unshift(newParticipant)
}
export const participantsSync = (state, participants) => state.participants = participants
export const participantsUnsetContributor = (state, participant) => participant.contributor = false
export const participantsSetContributor = (state, participant) => participant.contributor = true

export const setParticipantActive = (state, participant) => {

    state.participants.forEach(function (worker) {
        worker.active = false
    })

    participant.contributor = true
    participant.active = true
}

export const load = (state, { data }) => {

    let storage = JSON.parse(data.storage)
    let sessionLength = state.timerOptions.sessionLength


    state.persist = storage.persist
    state.mobName = data.name
    state.featureNameOption = storage.featureNameOption
    state.featureName = storage.featureName
    state.jiraTasks = storage.jiraTasks
    state.participants = storage.participants
    state.tasks = storage.tasks
    state.tasksOptions = storage.tasksOptions
    state.notes = storage.notes
    state.notesOptions = storage.notesOptions
    state.timerOptions = storage.timerOptions

    if(!sessionLength) {
        state.timer.duration = moment.duration(state.timerOptions.sessionLength * 60000, 'milliseconds')
    }
}
