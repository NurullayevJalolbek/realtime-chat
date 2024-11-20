<template>
    <div class="chat-container">
        <!-- Chap yon panel -->
        <div class="sidebar">
            <h2>Contacts</h2>
            <div class="contact">
                <div v-for="user in data.users" :key="user.id" class="contact-info">
                    <button class="contact-button" @click="openChat(user.id)">
                        {{ user.name }}
                    </button>
                </div>
            </div>


        </div>


        <!-- Chat oyna -->
        <div class="chat-window">
            <div v-if="receiverUSER" class="chat-header">
                <h3>{{ receiverUSER.name }}</h3>
<!--                <h3 style="margin-left: auto;">{{ senderUSER.name }}</h3>-->
                <button class="toggle-btn" onclick="toggleSidebarAndChatInput()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                    </svg>
                </button>

            </div>


            <div v-if="MESSAGES" class="chat-content" ref="chatContent">
                <div v-for="MESSAGE in MESSAGES" :key="MESSAGE.id"
                     :class="{'message user': MESSAGE.sender_id === senderUSER.id, 'message friend': MESSAGE.sender_id === receiverUSER.id}">
                    <p>{{ MESSAGE.text }}</p>
                    <span class="message-time">{{ MESSAGE.created_at }}</span>
                </div>
            </div>
            <!-- `v-show` orqali elementni ko'rsatish/yashirish -->
            <div v-show="MESSAGES" class="chat-input">
                <form @submit.prevent="inputText">
                    <input
                        v-model="message"
                        type="text"
                        placeholder="Type a message..."
                    />
                    <input type="hidden" :value="senderUSER.id" name="sender_id"/>
                    <input type="hidden" :value="receiverUSER.id" name="receiver_id"/>
                    <button type="submit">Send</button>
                </form>
            </div>




        </div>
        <!-- O'ng yon panel -->
        <div v-if="senderUSER">
            <div class="user-info-sidebar">
                <h2>Akaunt ma'lumotlari</h2>
                <div class="user-info">
                    <img :src="getImageUrl()" alt="Foydalanuvchi rasmi">
                    <h3>{{ senderUSER.name }}</h3>
                    <p>Email: {{ senderUSER.email }}</p>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {ref} from 'vue';
import axios from 'axios';
import { format } from 'date-fns';



export default {
    setup() {
        const data = ref([])
        axios.get("http://localhost:8000/contacts")
            .then(response => {
                data.value = response.data
                console.log(data.value)
            })
            .catch(error => {
                console.error(error);
            });

        const messages = ref([])
        const senderUSER = ref([])
        const receiverUSER = ref([])
        const barchaMessage = ref([])
        const MESSAGES = ref([])
        const openChat = (id) => {
            axios.get(`http://localhost:8000/contact/user_id/${id}`)
                .then(response => {
                    senderUSER.value = response.data.senderUSER
                    receiverUSER.value = response.data.receiverUSER
                    // messages.value = response.data.messages
                })
                .catch(error => {
                    console.error(error);
                });
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
            setInterval(BarchaMessages, 1000);


        }

        const message = ref('');
        const sender_id = ref("");
        const receiver_id = ref("");
        const inputText = () => {
            sender_id.value = senderUSER.value.id;
            receiver_id.value = receiverUSER.value.id;

            axios.post('http://localhost:8000/send-message', {
                sender_id: sender_id.value, // To'g'ri: sender_id.value to'g'ridan-to'g'ri qiymat
                receiver_id: receiver_id.value, // To'g'ri: receiver_id.value to'g'ridan-to'g'ri qiymat
                message: message.value
            })
                .then(response => {
                    console.log('Message sent successfully:', response.data);
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });

            message.value = ''; // Inputni tozalash
        }

        // Rasm yo'lini qaytaruvchi funksiya
        const getImageUrl = () => {
            return `${window.location.origin}/images/user.jpeg`;
        };

        const formatDate = (date) => {
            // ISO formatdagi sanani faqat soat va daqiqaga o'zgartirish
            return format(new Date(date), 'HH:mm');  // Soat va daqiqa
        };


        return {
            data,
            openChat,
            messages,
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
/* Umumiy sozlamalar */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #000; /* Fond qora rangda */
    color: #fff; /* Matn oq rangda */
    line-height: 1.6;
    font-size: 16px;
    overflow-x: hidden;
}

/* Chat konteyneri */
.chat-container {
    display: flex;
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

/* Chap yon panel */
.sidebar {
    width: 250px;
    background-color: #222; /* Qora rang */
    color: white;
    padding: 20px;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease;
}

.sidebar.hidden {
    transform: translateX(-100%); /* Chapga yashirish */
}

/* Chat oynasi */
.chat-window {
    margin-left: 250px;
    margin-right: 300px;
    width: calc(100% - 550px); /* Sidebarlar mavjud bo'lganda kenglik */
    display: flex;
    flex-direction: column;
    height: 100%;
    background-color: #000;
    transition: margin 0.3s ease, width 0.3s ease;
}

.chat-window.full-width {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
}

/* Chat header */
.chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #000;
    color: white;
    padding: 15px;
}

.chat-header h3 {
    margin: 0;
}

.toggle-btn {
    background-color: transparent; /* Tugma foni yo'q */
    border: none; /* Ramkasiz tugma */
    cursor: pointer; /* Tugma ustida ko'rsatkich */
    padding: 10px; /* Tugma ichidagi bo'sh joy */
    display: flex;
    justify-content: center; /* Ikonkani markazga joylashtirish */
    align-items: center; /* Ikonkani markazga joylashtirish */
}

/* Ikonka uchun o'lcham va rang */
.toggle-btn svg {
    width: 24px;  /* Ikonkani kengligi */
    height: 24px; /* Ikonkani balandligi */
    color: white; /* Ikonkani rangi */
}

/* Chat container */
.chat-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
    overflow-y: auto; /* Scroll qo'shish */
}

/* Umumiy xabar styli */
.message {
    max-width: 70%;
    padding: 7px;
    border-radius: 10px;
    font-size: 16px;
    line-height: 1.5;
    display: inline-block;
}
/* created_at vaqtini qora rangda va pastda ko'rsatish */
.message-time {
    text-align: right; /* Matnni o'nga joylashtiradi */
    display: block;
    margin-top: 5px;
    color: black;  /* Qora rang */
    font-size: 12px;  /* Vaqt uchun kichikroq shrift */
    font-style: italic;  /* Vaqtni qiyshaytirish */
}


/* Sender (o'ng tomonga) */
.message.user {
    align-self: flex-end; /* O'ng tomonga joylashadi */
    background-color: #cce5ff; /* Moviy fon */
    color: #004085; /* Matn rangi */
}

/* Receiver (chap tomonga) */
.message.friend {
    align-self: flex-start; /* Chap tomonga joylashadi */
    background-color: #d4edda; /* Yashil fon */
    color: #155724; /* Matn rangi */
}


/* O'ng yon panel */
.user-info-sidebar {
    width: 300px;
    background-color: #222; /* Qora rang */
    color: white;
    padding: 20px;
    height: 100%;
    position: fixed;
    right: 0;
    top: 0;
    transition: transform 0.3s ease;
}

.user-info-sidebar.hidden {
    transform: translateX(100%); /* O‘ngga yashirish */
}

.user-info-sidebar h2 {
    color: white;
    margin-bottom: 20px;
}

.user-info {
    text-align: center;
}

.user-info img {
    width: 80px; /* Rasmning eni */
    height: 80px; /* Rasmning balandligi */
    border-radius: 50%; /* Doira shakli uchun */
    object-fit: cover; /* Rasmni doira ichida to'liq ko'rsatish */
    margin-bottom: 20px;
}

.user-info h3 {
    margin: 0;
    color: white;
    font-size: 18px;
}

.user-info p {
    color: #ccc;
    font-size: 14px;
    margin-top: 5px;
}
/* Umumiy chat inputning dizayni */
.chat-input {
    position: relative;
    width: 100%;
    top: 450px;
    padding: 10px;
    display: flex;
    gap: 10px;
    align-items: center;
    transition: width 0.3s ease, padding 0.3s ease;
}

/* Default holat: input kichik */
.chat-input input {
    width: 480%; /* Default holatda kichik bo'ladi */
    flex: 1;
    padding: 10px 15px;
    border: none;
    border-radius: 25px;
    background-color: #444;
    color: white;
    font-size: 16px;
    outline: none;
    transition: width 0.3s ease, background-color 0.3s ease;
}

.chat-input input:focus {
    background-color: #555;
}

/* Toggle bosilganda kengayuvchi chat input */
.chat-input.expanded {
    width: 565%; /* Kengaygan holat */
    padding: 10px 30px;
}

.chat-input.expanded input {
    width: 670%; /* Kengaygan input */
}

/* Collapse holati */
.chat-input.collapsed button {
    display: none;
    opacity: 0; /* Tugma ko'rinmaydi */
}

.chat-input {
    position: sticky;
    bottom: 0;
    width: 100%;
}

/* Media queries (responsive dizayn) */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: static;
    }

    .chat-window {
        margin-left: 0;
        width: 100%;
    }

    .user-info-sidebar {
        width: 100%;
        position: static;
        margin-top: 20px;
    }
}

.contact {
    display: flex;
    flex-direction: column;
}

.contact-info {
    margin-bottom: 10px;
}

.contact-button {
    background-color: #222; /* Qora rang */
    color: white; /* Matn rangi */
    border: none; /* Ramka yo'q */
    padding: 15px; /* Ichki bo'shliq */
    text-align: left; /* Matnni chapga joylashtirish */
    text-decoration: none; /* Matn ostidagi chiziqni olib tashlash */
    display: block; /* Tugmani blok shaklida ko'rsatish */
    font-size: 18px; /* Matnning o'lchami */
    width: 100%; /* Kengligini to'liq qilamiz */
    border-radius: 10px; /* Yumshoq burchaklar */
    cursor: pointer; /* Ko'rsatkichi qo'lga o'zgaradi */
    box-sizing: border-box; /* Padding hisobga olinadi */
}

.contact-button h3 {
    margin: 0; /* Matnning yuqori va pastki bo'shliqni olib tashlash */
}

.contact-button:hover {
    background-color: #444; /* Hover holatida fon rangi o'zgaradi */
}

</style>
