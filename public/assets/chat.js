let noChat = document.getElementById("chat-empty");
let userInfo = document.getElementById("user-info");
let bodyChat = document.getElementById("body-chat");
let username = document.getElementById("username");
let idCon = document.getElementById("conversation-id");
let chatRoom = document.getElementById("chat-room");

// inputChat.addEventListener("")

function sendMessage(message) {
    // console.log(event)
    let html =
        '<div class="flex justify-end items-end w-full"><div class="bg-emerald-500 w-fit rounded-lg p-2 shadow"><p class="text-gray-800">' +
        message +
        "</p></div></div>";
    chatRoom.insertAdjacentHTML("beforeend", html);
    chatRoom.scrollTo({
        top: chatRoom.scrollHeight,
        behavior: "smooth",
    });
}
function chooseFriend(id) {
    let me = document.getElementById("me").value;
    noChat.classList.remove("flex");
    noChat.classList.add("hidden");

    userInfo.classList.remove("hidden");
    userInfo.classList.add("flex");

    const data = {
        friend_id: id,
    };

    axios
        .post("/conversation", data)
        .then((res) => {
            // console.log(me)
            chatRoom.innerHTML = "";
            const roomId = res.data.data.conversation.id;
            username.innerText = res.data.data.user.name;
            idCon.value = res.data.data.conversation.id;

            let messages = res.data.data.conversation.messages;
            if (messages.length > 0) {
                for (let index = 0; index < messages.length; index++) {
                    const element = messages[index];
                    // console.log(element.user_id)
                    if (me == element.user_id) {
                        chatRoom.innerHTML +=
                            '<div class="flex justify-end items-end w-full"><div class="bg-red-500 rounded-lg p-2 shadow"><p class="text-gray-800">' +
                            element.body +
                            "</p></div></div>";
                    } else {
                        chatRoom.innerHTML +=
                            '<div class="flex justify-start w-full"><div class="bg-white rounded-lg p-2 shadow"><p class="text-gray-800">' +
                            element.body +
                            "</p></div></div>";
                    }
                }
            }

            Echo.join(`chat.${roomId}`)
                .here((users) => {
                    console.log("join chanel success");
                    document
                        .getElementById("body-chat")
                        .addEventListener("keydown", (e) => {
                            let body = bodyChat.value;
                            if (e.key === "Enter") {
                                const data = {
                                    conversation_id: idCon.value,
                                    body: body,
                                };
                                axios
                                    .post("/chat/store", data)
                                    .then((res) => {
                                        console.log(res);
                                        if (res.data.success) {
                                            bodyChat.value = "";
                                        }
                                    })
                                    .catch((err) => {
                                        console.log(err);
                                    });

                                sendMessage(data.body);
                                // this.value = ""
                            }
                        });
                })
                .listen("SendMessage", (e) => {
                    console.log(e);
                })
                .joining((user) => {
                    console.log(user.name);
                })
                .leaving((user) => {
                    console.log(user.name);
                })
                .error((error) => {
                    console.error(error);
                });
        })
        .catch((err) => {
            console.log(err);
        });
}
