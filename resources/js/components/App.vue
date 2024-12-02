<template>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Recent Chats</h3>
        <div v-for="user in data" class="user-list">
            <div class="user" @click="openChat(user.id)">
                <img src="https://via.placeholder.com/50" alt="Avatar" class="user-avatar">
                <div class="user-info">
                    <h3 class="user-name">{{ user.name }}</h3>
                    <p class="user-status">Online</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Chat panel -->
    <div class="chat-panel">
        <div v-if="receiverUSER.name" class="chat-header">
            <div>
                <h2>{{ receiverUSER.name }}</h2>
                <p class="user-status"> Online</p>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div v-for="message in MESSAGES" :key="message.id">
                <div v-if="message.sender_id === receiverUSER.id" class="message sender">
                    <img :src="receiverUSER.avatar" alt="Receiver Avatar" class="avatar"/>
                    <p>{{ message.text }}</p>
                    <span class="time">{{ formatDate(message.created_at) }}</span> <!-- Soat va minut -->
                </div>
                <div v-if="message.sender_id === senderUSER.id" class="message receiver">
                    <img :src="senderUSER.avatar" alt="Sender Avatar" class="avatar"/>
                    <p>{{ message.text }}</p>
                    <span class="time">{{ formatDate(message.created_at) }}</span> <!-- Soat va minut -->
                </div>
            </div>
        </div>


        <div v-if="receiverUSER.name" class="chat-input">
            <form @submit.prevent="inputText">
                <input
                    v-model="message"
                    type="text"
                    placeholder="Type a message..."
                />
                <input type="hidden" :value="senderUSER.id" name="sender_id"/>
                <input type="hidden" :value="receiverUSER.id" name="receiver_id"/>

                <button type="submit" id="sendBtn">➤</button>
            </form>
        </div>
    </div>

</template>
<script>
import {onMounted, ref, watch} from 'vue';
import axios from 'axios';
import {format} from 'date-fns';


export default {
    setup() {
        const CONTACTS = ref()
        const SENDERUSER = ref();
        const contacts = () => {
            axios.get("http://localhost:8000/contacts")
                .then(response => {
                    CONTACTS.value = response.data.users
                    SENDERUSER.value = response.data.senderUSER
                })
                .catch(error => {
                    console.error(error);
                });
        }


        const senderUSER = ref([])
        const receiverUSER = ref([])
        const barchaMessage = ref([])
        const MESSAGES = ref([])
        const openChat = (id) => {
            axios.get(`http://localhost:8000/contact/user_id/${id}`)
                .then(response => {
                    senderUSER.value = response.data.senderUSER
                    receiverUSER.value = response.data.receiverUSER
                    MESSAGES.value = response.data.messages
                })
                .catch(error => {
                    console.error(error);
                });

        }


        const BarchaMessages = () => {
            axios.get(`http://localhost:8000/messages/sender_id/${senderUSER.value.id}/receiver_id/${receiverUSER.value.id}`)
                .then(response => {
                    barchaMessage.value = response.data

                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                });


            MESSAGES.value = barchaMessage.value[0].map(item => ({
                id: item.id,
                sender_id: item.sender_id,
                receiver_id: item.receiver_id,
                created_at: item.created_at,
                text: item.text
            }));


        }


        const scrollToBottom = () => {
            const messagesContainer = document.getElementById("chatMessages");
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        };


        const message = ref('');
        const sender_id = ref("");
        const receiver_id = ref("");
        const inputText = () => {
            sender_id.value = senderUSER.value.id;
            receiver_id.value = receiverUSER.value.id;

            axios.post('http://localhost:8000/send-message', {
                sender_id: sender_id.value,
                receiver_id: receiver_id.value,
                message: message.value
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.data) {
                        watch(MESSAGES, () => {
                            scrollToBottom(); // Xabarlar yangilanganda pastga siljish
                        });
                        scrollToBottom(); // Xabarlar tugagandan so'ng pastga siljitish
                        playNotificationSound();
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });

            message.value = ''; // Xabarni bo'shatish
        }

        const getImageUrl = () => {
            return `${window.location.origin}/images/user.jpeg`;
        };

        const formatDate = (date) => {
            return format(new Date(date), 'HH:mm');  // Soat va daqiqa
        };

        //Sound Notification
        const notificationSound = new Audio('/sounds/NotificationSound.wav');
        const playNotificationSound = () => {
            notificationSound.play().catch((error) => {
                console.error('Notification sound failed to play:', error);
            });
        };

        onMounted(() => {
            contacts();
        });


        watch([senderUSER, receiverUSER], () => {
            console.log("SenderUSER", senderUSER.value.id, "ReceiverUSER", receiverUSER.value.id)

            window.Echo.private(`chat_${senderUSER.value.id}_${receiverUSER.value.id}`)
                .listen('GotMessage', (event) => {
                    console.log(event.messages)
                    BarchaMessages();
                })
        })



        return {
            data: CONTACTS,
            openChat,
            senderUSER,
            receiverUSER,
            inputText,
            message,
            MESSAGES,
            formatDate,
            getImageUrl
        }
    }
}


</script>
<style>
/* Universal styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to bottom, rgba(15, 15, 31, 0.9), rgba(30, 30, 45, 0.9));
    color: #fff;
    height: 100vh;
    display: flex;
}

.chat-container {
    display: flex;
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

/* Sidebar styles */
.sidebar {
    width: 25%;
    background: rgba(30, 30, 45, 0.7); /* Transparent background */
    padding: 20px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.sidebar h3 {
    margin-bottom: 20px;
    color: #a0a0b1;
    font-size: 16px;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 25px;
    background: rgba(43, 43, 61, 0.9);
    color: #fff;
    outline: none;
    margin-bottom: 20px;
    font-size: 14px;
}

.user-list {
    margin-bottom: 20px;
}

.user {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px 15px;
    border-radius: 10px;
    background-color: rgba(34, 34, 34, 0.8); /* Qoraroq va shaffof fon */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Qorong‘i soyalar */
    cursor: pointer;
    transition: all 0.3s ease;
}

.user:hover {
    background-color: rgba(54, 54, 54, 0.9); /* Hoverda biroz ochroq rang */
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.7);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.5); /* Shaffof oq chegara */
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
    border-color: rgba(0, 123, 255, 1); /* Hoverda ko‘k rang */
}

.user-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.user-name {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #fff; /* Oq rang */
}

.user-status {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7); /* Shaffof oq */
    margin: 5px 0 0;
}

.user:active {
    transform: scale(0.98);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
}

.user img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #444;
    font-size: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}

.user-info {
    margin-left: 15px;
}

.user-info h4 {
    font-size: 14px;
    margin-bottom: 5px;
}

.user-info p {
    font-size: 12px;
    color: #006400;
}

/* Chat panel styles */
.chat-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: rgba(18, 18, 27, 0.7); /* Transparent background */
}

.chat-header {
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(30, 30, 45, 0.9); /* Slightly opaque */
    border-bottom: 1px solid #333;
}

.chat-header h2 {
    font-size: 18px;
    color: #fff;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    scroll-behavior: smooth;
}

#chatMessages {
    overflow-x: hidden; /* Yon tomondagi scrollni yashirish */
    overflow-y: auto; /* Faol vertikal scrollni yoqish */
    max-height: 787px; /* Chat uchun maksimal balandlik */
    padding: 10px;
}

.message {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-start; /* Xabarni boshlanishdan joylashtirish */
    position: relative;
    gap: 10px; /* Avatar va matn orasida masofa */
}

.message.sender {
    align-items: flex-start;
    flex-direction: row; /* Foydalanuvchi xabarini chapga joylashtirish */
}

.message.receiver {
    align-items: flex-end;
    flex-direction: row-reverse; /* Receiver xabarini o'nga joylashtirish */
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover; /* Rasmni to'liq joylashtirish */
}

.message.sender .avatar {
    margin-left: 0; /* Foydalanuvchi avatarini chapga joylashtirish */
}

.message.receiver .avatar {
    margin-right: 0; /* Foydalanuvchi avatarini o'nga joylashtirish */
}

.message p {
    background: rgba(30, 136, 229, 0.9); /* Transparent sender message */
    color: #fff;
    padding: 10px;
    border-radius: 15px;
    font-size: 14px;
    max-width: 70%; /* Xabarlar kengligini cheklash */
}

.message.receiver p {
    background: rgba(68, 211, 107, 0.9); /* Transparent receiver message */
}

.message .time {
    font-size: 12px;
    color: #aaa;
    margin-top: 5px;
    display: block;
}

.chat-input {
    position: absolute; /* Position absolute qo'shildi */
    bottom: 0; /* Inputni pastga joylashtirish */
    left: 25%; /* O'ngga surish uchun chapdan 5% masofa */
    right: 0; /* O'ng tomondan cheklash */
    display: flex;
    padding: 15px;
    background: rgba(30, 30, 45, 0.9); /* Transparent input background */
    border-top: 1px solid #333;
}


.chat-input input {
    flex: 1;
    width: 670%;
    padding: 10px;
    border: none;
    border-radius: 25px;
    background: rgba(43, 43, 61, 0.9); /* Transparent input field */
    color: #fff;
    margin-right: 10px;
    font-size: 14px;
    outline: none;
}

.chat-input button {
    display: none;
    background: rgba(10, 132, 255, 0.9); /* Transparent send button */
    border: none;
    border-radius: 50%;
    padding: 10px 15px;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s ease;
}

.chat-input button:hover {
    background: rgba(0, 91, 181, 0.9);
}
</style>
