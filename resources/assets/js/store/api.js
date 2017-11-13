export default {

    createRecord (name, assembled, created, failed, loader) {

        loader(true)
        axios.post('/api/mob', {
            'name': name,
            'storage': assembled,
        })
        .then(({ data }) => {
            created(data)
        })
        .catch((_response) => {
            console.log("create Failed");

            failed()
        })
        .then(() => {
            loader(false)
        })

    },

    saveRecord (slug, assembled, failed, loader) {

        loader(true)
        axios.put('api/mob/' + slug, {
            'storage': assembled,
            })
            .catch((_response) => {
                console.log("save Failed");
                failed()
            })
            .then(() => {
                loader(false)
            })

    },

    deleteRecord (slug, failed, loader) {

        loader(true)
        axios.delete('api/mob/' + slug)
            .catch((_response) => {
                console.log("delete Failed");
                failed()
            })
            .then(() => {
                loader(false)
            })

    },

    fetchMob (slug, save, failed, loader) {

        loader(true)
        axios.get('api/mob/' + slug)
            .then(({ data }) => {
                save(data)
            })
            .catch((_response) => {
                failed()
            })
            .then(() => {
                loader(false)
            })

    }
}
