import * as browser from '../helpers/browser'
import api from './api'

export const advanceParticipant = ({ state, commit, getters }) => {
    for(let i = 0; i < getters.contributors.length; i++) {

        if(getters.contributors[i].active && typeof getters.contributors[i+1] !== "undefined") {
            commit('setParticipantActive', getters.contributors[i])
            commit('setParticipantActive', getters.contributors[i+1])
            break;
        } else if (getters.contributors[i].active && typeof getters.contributors[i+1] === "undefined") {
            commit('setParticipantActive', getters.contributors[i])
            commit('setParticipantActive', getters.contributors[0])
            break;
        }
    }
}

export const participantDelete = ({ dispatch, state }, index) => {
    if(state.participants[index].active) {
        dispatch("advanceParticipant")
    }
    state.participants.splice(index, 1)
}

export const timerReignOver = ( {commit, dispatch, state} ) => {

    if(state.timerOptions.flashBrowser) {
        browser.flashToTitle("The gig is up")
    }

    if(state.timerOptions.playSound) {
        commit('timerPlayFinishSound')
    }
    dispatch("advanceParticipant")
    commit("timerIncrement")
    dispatch("timerReset")

    if(state.timerOptions.suggestBreaks && state.timer.count >= state.timerOptions.breakIntervals) {
        commit("timerResetCounter")
        commit("timerBreakOn")
    }
}

export const timerStart = ({ commit, dispatch, state  }) => {
    let interval = 1000;
    commit("timerUnpaused")

    if (Object.keys(state.timer.interval).length === 0 && state.timer.interval.constructor === Object ) {
        commit('timerSetInterval', setInterval( function () {
            if (!state.timer.paused) {

                if (state.timer.duration._milliseconds > 0) {
                    commit('timerSetDuration', moment.duration(state.timer.duration.asMilliseconds() - interval, 'milliseconds'))
                } else {
                    commit("timerPaused")
                    dispatch("timerReignOver")
                }
            }
        }, interval))
    }
}

export const timerSetTime = ({ commit }, value) => {
    commit('timerSetSessionLength', value)
    commit('timerBuildDuration')
}


export const timerReset = ({ commit, state }) => {
    clearInterval(state.timer.interval)
    commit('timerSetInterval', {})
    commit("timerBuildDuration")
    commit("timerPaused")
    // commit("timerStopFinishSound")
}

export const persist = ({ getters, state, dispatch, commit }) => {

    if (state.persist) {

        if (!state.created) {
            api.createRecord(
                state.mobName,
                getters.assembled,
                data => dispatch("created", { data }),
                () => dispatch("failed"),
                value => commit("setAdminLoader", value)
            )

        } else {
            api.saveRecord(
                state.slug,
                getters.assembled,
                () => dispatch("failed"),
                value => commit("setAdminLoader", value)
            )
        }
    }
}

export const created = ({state, commit}, { data }) => {
    commit("createdOn")
    commit("setSlug", data.slug)

    window.history.pushState({}, "", data.slug)
}

export const failed = () => {
    console.log("Called failed")
}

export const loadMob = ({ state, dispatch, commit }) => {

    api.fetchMob(
        state.slug,
        data => commit("load", { data }),  //onSuccess
        () => dispatch("failed"),               //onFail
        value => commit("setAdminLoader", value) //loaderImg
    )
}

export const deleteMob = ({ state, dispatch, commit }) => {

    api.deleteRecord(
        state.slug,
        () => dispatch("failed"),               //onFail
        value => commit("setAdminLoader", value) //loaderImg
    )
}