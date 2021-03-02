<template>
	<div>
		<div v-for="(message, index) in allMessages" :key="index" >

			<template  v-if="message.message!=''">

				<div class="chat-right" v-if="user.id == message.user_id">
					<template v-if="message.image">
						<img :src="'/producerseye/Code/public/img/profile_picture/'+message.image" width="100" height="100">
					</template>
					<template v-else>
						<img src="/producerseye/Code/public/front/images/196.jpg" alt="img" width="100" height="100">
					</template>
					<div class="chat-content">
						<p class="chat-msg">{{message.message}}</p>
						<div class="chat-caption">
							<span class="time-left">{{ message.created_at | moment("h:mm a") }}</span>
						</div>
					</div>
				</div>

				<div class="chat-left" v-else>

					<template v-if="userImage">
						<img :src="'/producerseye/Code/public/img/profile_picture/'+userImage" width="100" height="100">
					</template>
					<template v-else>
						<img src="/producerseye/Code/public/front/images/196.jpg" alt="img" width="100" height="100">
					</template>

					<div class="chat-content">
						<p class="chat-msg">{{message.message}}</p>
						<div class="chat-caption">
							<span class="time-left">{{ message.created_at | moment("h:mm a") }}</span>
						</div>
					</div>
				</div>
			</template>
		</div>
		<div v-bind:class="[user.id == typingFriend.user_id ? 'chat-right' : 'chat-left']" class="chat-typing" v-if="typingFriend.username">
			<template v-if="typingFriend.profile_picture">
				<img :src="'/producerseye/Code/public/img/profile_picture/'+typingFriend.profile_picture" width="100" height="100">
			</template>
			<template v-else>
				<img src="/producerseye/Code/public/front/images/196.jpg" alt="img" width="100" height="100">
			</template>
			<span class="typing"><img src="/producerseye/Code/public/front/images/chat-loading.gif" alt="chat-loading"></span>
		</div>

	</div>
</template>

<script>
export default {
	props:['user','allMessages','userImage','typingFriend'],

}
</script>
