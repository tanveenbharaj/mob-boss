export const activeParticipant = state => {
    return state.participants.filter(participant => participant.active)
}

// get all contributors
export const contributors = state => {
    return state.participants.filter(function (participant) {
        return participant.contributor
    })
}

export const assembled = state => {

    return JSON.stringify({
        persist: state.persist,
        featureNameOption: state.featureNameOption,
        featureName: state.featureName,
        username: state.username,
        password: state.password,
        url: state.url,
        jiraTasks: state.jiraTasks,
        participants: state.participants,
        tasks: state.tasks,
        tasksOptions: state.tasksOptions,
        selectedTaskKey: state.selectedTaskKey,
        selectedTaskName: state.selectedTaskName,
        bookmarks: state.bookmarks,
        bookmarksOptions: state.bookmarksOptions,
        comments : state.comments,
        commentsOptions: state.commentsOptions,
        notes: state.notes,
        notesOptions: state.notesOptions,
        timer: {
            sessionLength: state.timer.sessionLength
        },
        timerOptions: state.timerOptions,
        isActive: state.isActive,
        selectedTheme:state.selectedTheme
    })
}
