<template>
    <div class="chat-container">
        <div class="contacts">
            <h2>Contacts</h2>
            <div>
                <div v-for="user in data.users" :key="user.id" class="contact">
                    <img src="https://via.placeholder.com/40" alt="Profile Picture">
                    <button class="contact-button" @click="openChat(user.id)">
                        {{ user.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-header">


                <div v-if="receiverUSER">
                    <div class="contact">
                        <!--                        <img src="https://via.placeholder.com/40" alt="Profile Picture">-->
                        <button class="contact-button">
                            {{ receiverUSER.name }}
                        </button>
                    </div>
                </div>

            </div>
            <!--            <h1> {{MESSAGES}}</h1>-->

            <div v-if="MESSAGES" class="chat-messages">
                <div v-for="MESSAGE in MESSAGES" :key="MESSAGE.id">
                    <!-- Receiver tomonidan yuborilgan xabar -->
                    <div v-if="MESSAGE.sender_id === receiverUSER.id" class="message received">
                        {{ MESSAGE.text }}
                        <span class="timestamp">{{ formatDate(MESSAGE.created_at) }}</span>
                    </div>

                    <!-- Sender tomonidan yuborilgan xabar -->
                    <div v-else-if="MESSAGE.sender_id === senderUSER.id" class="message sent">
                        {{ MESSAGE.text }}
                        <span class="timestamp">{{ formatDate(MESSAGE.created_at) }}</span>
                    </div>
                </div>
            </div>





            <div class="chat-input">
                <form @submit.prevent="inputText">
                    <input
                        v-model="message"
                        type="text"
                        placeholder="Type a message..."
                    />
                    <!-- Yashirin maydonlar qo'shildi -->
                    <input type="hidden" :value="senderUSER.id" name="sender_id"/>
                    <input type="hidden" :value="receiverUSER.id" name="receiver_id"/>

                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {ref} from 'vue';
import axios from 'axios';


export default {
    setup() {
        const data = ref([])
        axios.get("http://localhost:8000/contacts")
            .then(response => {
                data.value = response.data
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
            setInterval(BarchaMessages, 1000); // 5000ms = 5 soniya


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
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`; // Faqat soat va daqiqa
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
            formatDate
        }
    }
}


</script>
<style>


</style>
