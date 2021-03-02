<template>
    <div class="chat-wrap-main">
        <section class="chat-list-left">
            <div class="previous-chat">

                <div class="top-wrap">
                    <div class="search-bar">
                        <a href="javascript:void(0);" class="search-icon"><i class="fas fa-search"/></a>
                        <input placeholder="Search.." v-model="username" @keyup="searchUser">
                    </div>
                </div>
                <div v-for="friend in friends" :key="friend.id">
                    <!-- <div class="chat chat-1"  v-bind:class="{ 'bg-actor': friend.user_type=='1', 'bg-model': friend.user_type=='2', 'bg-musician': friend
                    .user_type=='3' }"> -->
                    <div class="chat chat-1" @click="activeFriend=friend.id">
                        <div class="img-wrap">
                            <template v-if="friend.profile_picture">
                                <a :href="'/producerseye/Code/profile-details/'+friend.username">
                                    <img :src="'/producerseye/Code/public/img/profile_picture/'+friend.profile_picture" width="100" height="100">
                                </a>
                            </template>
                            <template v-else>
                                <a :href="'/producerseye/Code/profile-details/'+friend.username">
                                    <img src="/producerseye/Code/public/front/images/no-image-available.png" alt="img" width="100" height="100">
                                </a>
                            </template>
                        </div>
                        <div class="name" v-bind:class="[activeFriendData.id===friend.id ? 'active' : '']" @click="activeFriend=friend.id">
                            <a :href="'/producerseye/Code/profile-details/'+friend.username"><h2>{{friend.first_name}} {{friend.last_name}}</h2></a>
                            <span class="status"><i class="fa fa-circle" v-bind:class="[(onlineFriends.find(user=>user.id===friend.id)) ? 'online' : 'red']"></i></span>
                        </div>
                        <div class="delete-user">
                            <a href="javascript:;" class="fas fa-trash" @click="deleteUser(friend.id)"></a>
                            <template v-if="friend.isreport === 1">
                                <a href="javascript:;" class="fas fa-exclamation-triangle" @click="alreadyReportedUser()"></a>
                            </template>
                            <template v-else>
                                <a href="javascript:;" class="fas fa-exclamation-triangle" @click="reportUser(friend.id)"></a>
                            </template>
                        </div>
                        <div class="time">
                            <span class="number" v-if="friend.notifycount > 0 ">{{ friend.notifycount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="chat-section">
            <div class="chat-wrap">
                <div class="head-wrap">
                    <div class="to-name">
                        <p>To: <a :href="'/producerseye/Code/profile-details/'+activeFriendData.username" style="color: #A9A9A9;">{{ activeFriendData.first_name }} {{
                            activeFriendData.last_name }}</a></p>
                        <a href="#"><i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="chat-messages" id="privateMessageBox">
                    <message-list :user="user" :all-messages="allMessages" :userImage="userImage" :typingFriend="typingFriend"></message-list>
                </div>
                <!-- <p v-if="typingFriend.username">{{ typingFriend.username }} is Typing...</p> -->

                <div class="write-message" v-if="isReportUser==null">
                    <div class="type-wrap">
                        <textarea placeholder="Type something..." v-model="message" label="Enter Message" single-line @keydown="onTyping"></textarea>
                    </div>
                    <!-- <div class="emoji">
                        <a href="#" @click="toggleEmo"><i class="far fa-smile"></i></a>
                        <div class="floating-div">
                            <picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji…" />
                        </div>
                    </div> -->
                    <div class="btn-btn">
                        <button @click="sendMessage">Send</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="my-profile-intro">
            <template v-if="activeFriendData.id > 0">
                <div class="person-img">
                    <template v-if="activeFriendData.profile_picture">
                        <img :src="'/producerseye/Code/public/img/profile_picture/'+activeFriendData.profile_picture" width="100" height="100">
                    </template>
                    <template v-else>
<!--                        <img src="/producerseye/Code/public/front/images/no-image-available.png" alt="img" width="100" height="100">-->
                    </template>
                </div>
            </template>
<!--            <template v-else>-->
<!--                <div class="person-img">-->
<!--                    <a :href="sdsd">Click here to start messaging someone <img src="/producerseye/Code/public/front/images/online-chat-symbol.jpg" alt="img"></a>-->
<!--                </div>-->
<!--            </template>-->
            <div class="information" v-bind:class="{ 'actor': activeFriendData.user_type=='1', 'model': activeFriendData.user_type=='2', 'musician':
            activeFriendData.user_type=='3', 'crew': activeFriendData.user_type=='4' }">
                <div class="leftbar-toggle">
                    <span/>
                    <span/>
                    <span/>
                </div>
                <template v-if="activeFriendData.id > 0">
                    <div class="info-img">
                        <template v-if="activeFriendData.profile_picture">
                            <a :href="'/producerseye/Code/profile-details/'+activeFriendData.username">
                                <img :src="'/producerseye/Code/public/img/profile_picture/'+activeFriendData.profile_picture" width="100" height="100">
                            </a>
                        </template>
                        <template v-else>
                            <a :href="'/producerseye/Code/profile-details/'+activeFriendData.username">
                                <img src="/producerseye/Code/public/front/images/no-image-available.png" alt="img" width="100" height="100">
                            </a>
                        </template>
                    </div>
                </template>
                <template v-else>
                    <div class="no-chat">
                        <a :href="'/profiles'"><b>Click here to start messaging someone</b>
                            <img src="/producerseye/Code/public/front/images/makefg.png" alt="img">
                        </a>
                    </div>
                </template>
                <div class="info-wrap">
                    <a :href="'/producerseye/Code/profile-details/'+activeFriendData.username"><h3>{{ activeFriendData.first_name }} {{ activeFriendData.last_name }}</h3></a>
                    <div v-if="activeFriendData.city && activeFriendData.country">
                        <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                        <p>{{ activeFriendData.city }},{{ activeFriendData.country }}</p>
                    </div>
                    <div v-else-if="activeFriendData.city">
                        <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                        <p>{{ activeFriendData.city }}</p>
                    </div>
                    <div v-else-if="activeFriendData.country">
                        <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                        <p>{{ activeFriendData.country }}</p>
                    </div>
                </div>
                <div class="short-intro">
                    <p>{{ activeFriendData.caption }}</p>
                </div>
            </div>
        </section>
        <v-dialog/>
    </div>
</template>

<script>
    import MessageList from './_message-list'
    // import { Picker } from 'emoji-mart-vue'
    import VuejsDialog from 'vuejs-dialog';
    import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js';

    import 'vuejs-dialog/dist/vuejs-dialog.min.css';
    import VModal from 'vue-js-modal';

    Vue.use(VModal, {dialog: true});

    Vue.use(VuejsDialog, {
        html: true,
        loader: true,
        okText: 'Proceed',
        cancelText: 'Cancel',
        animation: 'bounce'
    });

    export default {
        props: ['user'],
        components: {
            // Picker,
            MessageList
        },

        data() {
            return {
                message: null,
                files: [],
                activeFriend: null,
                activeFriendData: [],
                typingFriend: {},
                onlineFriends: [],
                allMessages: [],
                isReportUser: {},
                typingClock: null,
                emoStatus: false,
                users: [],
                username: '',
                userImage: '',
                path: '/producerseye/Code',
                token: document.head.querySelector('meta[name="csrf-token"]').content

            }
        },

        computed: {
            friends() {
                return this.users.filter((user) => {
                    return user.id !== this.user.id;
                })
            }
        },

        watch: {
            files: {
                deep: true,
                handler() {
                    let success = this.files[0].success;
                    if (success) {
                        this.fetchMessages();
                    }
                }
            },
            activeFriend(val) {

                this.fetchMessages();
                this.currentUserProfile();
                this.typingFriend = {};
                this.message = "";
                setTimeout(this.scrollToEnd, 100);
                this.changeBg();
            },
            '$refs.upload'(val) {
                console.log(val);
            }
        },

        methods: {

            deleteUser(id) {

                this.$dialog
                    .confirm("Are you sure you would like to delete this chat? All messages will not be able to be recovered.", {
                        loader: true,
                    })
                    .then(dialog => {
                        axios.get(this.path + '/deleteUser/' + id).then(response => {
                            this.fetchUsers();
                            this.fetchMessages();
                            Echo.private('privatechat.' + this.activeFriend).whisper('delete', {
                                user: this.user
                            });
                        });
                        setTimeout(() => {
                            dialog.close();
                            window.location.href = this.path + '/chat';
                        }, 1000);
                    })
                    .catch(() => {
                    });
            },
            reportUser(id) {

                this.$dialog
                    .prompt({
                        title: "Are you sure you would like to report this user !!",
                        body: "Reason for reporting user?",
                        promptHelp: '',

                    })
                    .then(dialog => {
                        var data = {
                            id: id,
                            reason: dialog.data,
                        }
                        axios.post(this.path + '/reportUser', data).then(response => {
                            this.currentUserProfile();
                            dialog.close();
                            setTimeout(() => {
                                this.$modal.show('dialog', {
                                    title: '',
                                    text: 'Thank You. We will be looking into this!',
                                    buttons: [
                                        {
                                            title: 'Close'
                                        }
                                    ]
                                })
                            }, 200);
                        });
                    })
                    .catch(() => {
                    });
            },
            alreadyReportedUser() {
                this.$modal.show('dialog', {
                    title: '',
                    text: 'You already reported this user !',
                    buttons: [
                        {
                            title: 'Close'
                        }
                    ]
                })
            },

            changeBg() {
                setTimeout(function () {
                    var chat_pro = jQuery('.my-profile-intro .person-img img').attr('src');
                    jQuery('.my-profile-intro').css('background-image', 'url(' + chat_pro + ')');
                }, 400);
            },

            searchUser(e) {
                if (this.username.length >= 3) {

                    var data = {
                        search: this.username,
                    }
                    axios.post(this.path + '/chat/searchFriends', data).then((response) => {
                        this.users = response.data;
                        if (this.friends.length > 0) {
                            this.activeFriend = this.friends[0].id;
                        } else {
                            this.fetchUsers();
                        }

                    })
                } else {
                    this.fetchUsers();
                }
                this.changeBg();
            },
            onTyping() {
                Echo.private('privatechat.' + this.activeFriend).whisper('typing', {
                    user: this.user
                });
            },
            sendMessage() {

                if (!this.message) {
                    return alert('Please enter message');
                }
                if (!this.activeFriend) {
                    return alert('Please select friend');
                }
                this.typingFriend = {};
                $('#loading').show();
                axios.post(this.path + '/private-messages/' + this.activeFriend, {message: this.message}).then(response => {
                    this.message = null;
                    response.data.message.image = response.data.image;
                    if (this.allMessages.length === 0) {
                        this.allMessages = [response.data.message];
                    } else {
                        this.allMessages.push(response.data.message)
                    }
                    $('#loading').hide();
                    setTimeout(this.scrollToEnd, 100);
                });
                // this.fetchUsers();
                // setTimeout(()=>{
                // 	this.markAsRead();
                // },3000);
            },
            fetchMessages() {
                if (!this.activeFriend) {
                    return alert('Please select friend');
                }
                axios.get(this.path + '/private-messages/' + this.activeFriend).then(response => {
                    this.allMessages = response.data;
                    setTimeout(this.scrollToEnd, 100);
                    this.changeBg();
                });
            },
            fetchUsers() {
                axios.get(this.path + '/chatUsers').then(response => {
                    this.users = response.data;
                    if (this.friends.length > 0) {
                        this.activeFriend = this.friends[0].id;
                    } else {
                        this.activeFriendData = {}
                    }

                });
            },
            currentUserProfile() {
                axios.get(this.path + '/currentUserProfile/' + this.activeFriend).then(response => {

                    this.isReportUser = response.data.isReportUser;
                    // if(response.data.isReportUser===null){
                    // console.log(this.response.data);
                    // }else{
                    // 	console.log(this.isReportUser);
                    // }
                    this.activeFriendData = response.data.friend;
                    this.userImage = response.data.friend.profile_picture;
                    this.changeBg();
                    axios.get(this.path + '/chatUsers').then(response => {
                        this.users = response.data;
                    });
                    setTimeout(() => {
                        this.markAsRead();
                    }, 5000);
                });
            },
            markAsRead() {
                axios.get(this.path + '/markAsRead/' + this.activeFriend).then(response => {
                    axios.get(this.path + '/chatUsers').then(response => {
                        this.users = response.data;
                    });
                });
            },

            scrollToEnd() {
                document.getElementById('privateMessageBox').scrollTo(0, 99999);
            },
            toggleEmo() {
                this.emoStatus = !this.emoStatus;
            },
            onInput(e) {
                if (!e) {
                    return false;
                }
                if (!this.message) {
                    this.message = e.natnullive;
                } else {
                    this.message = this.message + e.native;
                }
                this.emoStatus = false;
            },

            onResponse(e) {
                console.log('onrespnse file up', e);
            }


        },

        mounted() {
        },

        created() {
            // const userId = $('meta[name="userId"]').attr('content');
            // this.user.id=userId;
            this.fetchUsers();

            Echo.join('plchat')
                .here((users) => {
                    // console.log('online',users);
                    this.onlineFriends = users;
                })
                .joining((user) => {
                    this.onlineFriends.push(user);
                    // console.log('joining',user.name);
                })
                .leaving((user) => {
                    this.onlineFriends.splice(this.onlineFriends.indexOf(user), 1);
                    // console.log('leaving',user.name);
                });

            Echo.private('privatechat.' + this.user.id)
                .listenForWhisper('delete', (e) => {
                    window.location.href = this.path + '/chat';
                })
                .listen('PrivateMessageSent', (e) => {
                    console.log('pmessage sent')
                    this.activeFriend = this.activeFriend;
                    this.fetchMessages();
                    setTimeout(this.scrollToEnd, 100);
                    this.fetchUsers();
                    setTimeout(() => {
                        this.markAsRead();
                    }, 3000);
                })
                .listenForWhisper('typing', (e) => {
                    setTimeout(this.scrollToEnd, 100);
                    if (e.user.id == this.activeFriend) {

                        this.typingFriend = e.user;

                        if (this.typingClock) clearTimeout();

                        this.typingClock = setTimeout(() => {
                            this.typingFriend = {};
                        }, 3000);
                    }


                });

        }

    }
</script>

<style scoped>

    .online-users, .messages {
        overflow-y: scroll;
        height: 100vh;
    }


</style>
