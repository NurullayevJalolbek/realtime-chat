import {createApp, ref} from 'vue'

const chat = createApp({
    setup(){
        const newInput = ref("");
        const SendMessage = () =>{
            axios.post('/message', {
        }
    }
}

chat.mount( '#chat' )

