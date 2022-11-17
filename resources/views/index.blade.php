<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="flex">
        <section class="flex flex-col w-1/4 bg-white min-h-screen p-4 gap-8">
            <div class="flex flex-col items-center border-b border-zinc-300 py-4 gap-2">
                @if ($user->avatar)
                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="rounded-full w-20 h-20 object-cover"
                        id="img-ava" />
                @else
                    <img src="{{ asset('assets/2.jpg') }}" class="rounded-full w-20 h-20 object-cover" id="img-ava" />
                @endif
                <p class="font-medium" id="myName">{{ $user->name }}</p>
                <button type="button" class="outline-none" id="btn-modal">
                    <span class="material-icons">settings</span>
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-rose-500 outline-none text-white px-2 py-1 font-medium rounded-lg text-sm border border-transparent ring-0">
                        <i class="bi bi-signout"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
            <x-modal modalId="modal-setting" />
            <div class="flex flex-col gap-4 lg:gap-6">
                @foreach ($users as $item)
                    <div class="flex items-center gap-2 cursor-pointer" onclick="chooseFriend({{ $item->id }})">
                        @if ($item->avatar)
                            <img src="{{ asset('storage/avatars/' . $item->avatar) }}"
                                class="rounded-full w-12 h-12 object-cover" />
                        @else
                            <img src="{{ asset('assets/2.jpg') }}" class="rounded-full w-12" />
                        @endif
                        <div class="flex flex-col">
                            <p class="text-sm font-medium">{{ $item->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section id="chat-empty" class="flex items-center w-3/4 bg-stone-100 min-h-screen">
            <div class="mx-auto">
                <p class="text-lime-800 text-lg font-semibold">Mulai Obrolan</p>
            </div>
        </section>
        <section class="hidden flex-col w-3/4 bg-stone-100 relative h-screen" id="user-info">
            <div class="flex w-full p-4 items-center gap-2 border-b drop-shadow-sm">
                <img src="{{ asset('assets/2.jpg') }}" class="w-16 rounded-full" />
                <div class="flex flex-col">
                    <p class="font-medium" id="username"></p>
                    <p class="font-medium text-xs text-gray-500" id="status"></p>
                </div>
            </div>

            {{-- Chat body --}}

            <section id="chat-room" class="flex flex-col gap-2 px-4 py-4 overflow-auto mb-14">

            </section>
            <form id="form-chat" action="{{ route('chat.store') }}"
                class="flex bottom-3 right-0 left-0 absolute px-4 md:px-8 gap-2 items-center" method="POST">
                @csrf
                <input type="text" name="body"
                    class="bg-white px-3 py-2 w-full rounded-lg lg:rounded-2xl ring-1 focus:ring-sky-500 focus:outline-none"
                    placeholder="Start typing..." />
                <input type="hidden" name="conversation_id" id="conversation-id" class="bg-white" />
                <input type="hidden" id="me" class="bg-white" value="{{ auth()->user()->id }}" />
                <button type="submit">
                    <span class="material-icons text-blue-600 text-xl md:text-3xl">
                        send
                    </span>
                </button>
            </form>
        </section>
    </div>
    @vite('resources/js/app.js')
    {{-- <script src="{{ asset('assets/chat.js') }}" type="text/javascript"></script> --}}
    <script type="text/javascript">
        let noChat = document.getElementById("chat-empty");
        let userInfo = document.getElementById("user-info");
        // let bodyChat = document.getElementById("body-chat");
        let username = document.getElementById("username");
        let status = document.getElementById("status");
        let conversationId = document.getElementById("conversation-id");
        let chatRoom = document.getElementById("chat-room");
        let btnModal = document.getElementById("btn-modal");
        let modal = document.getElementById("modal-setting");
        let btnUpdate = document.getElementById("btn-update");
        let userId = document.getElementById("me").value;
        let myName = document.getElementById("myName")
        let name = document.querySelector("#name")
        let avatar = document.querySelector("#avatar")

        document.addEventListener("DOMContentLoaded", () => {

            Echo.private(`update.${userId}`)
                .subscribed(() => {
                    console.log("subscribed")

                })
                .listen("UpdateProfile", (e) => {
                    let ava = document.getElementById("img-ava")
                    myName.innerText = e.name
                    if (e.avatar != null) {
                        ava.src = 'storage/avatars/' + e.avatar
                    }
                })
        })

        btnModal.addEventListener("click", () => {
            modal.classList.remove("hidden")
            axios.get("/user/show")
                .then((res) => {
                    // console.log(res.data)
                    let name = document.querySelector("#name")
                    name.value = res.data.data.name
                })
                .catch((err) => {
                    console.log(err)
                })
        })

        modal.querySelector("#close").addEventListener("click", () => {
            modal.classList.add("hidden")
        })

        let formProfile = document.querySelector("#form-profile")
        formProfile.addEventListener("submit", (e) => {
            e.preventDefault()

            let form = new FormData()
            form.append("name", name.value)
            if (avatar.files.length > 0) {
                form.append("avatar", avatar.files[0])
            }

            axios.post("/user/update", form, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((res) => {
                // console.log(res.data)
                if (res.data.success) {
                    modal.classList.add("hidden")
                }
            }).catch((err) => {
                console.log(err)
            })
        })

        function sendMessage(message, data) {
            // console.log(event)
            if (message != '') {
                let html =
                    '<div class="flex justify-end items-end w-full"><div class="bg-blue-500 w-fit rounded-l-xl rounded-tr-xl p-2 shadow"><p class="text-white">' +
                    message +
                    "</p></div></div>";
                chatRoom.insertAdjacentHTML("beforeend", html);
                chatRoom.scrollTo({
                    top: chatRoom.scrollHeight,
                    behavior: "smooth",
                });

                axios
                    .post("/chat/store", data)
                    .then((res) => {
                        // console.log(res);
                        let body = document.querySelector("input[name=body]")
                        body.value = ""

                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }

        }

        let form = document.getElementById("form-chat")
        form.addEventListener("submit", (e) => {
            e.preventDefault()
            let body = document.querySelector("input[name=body]")
            let data = {
                body: body.value,
                conversation_id: conversationId.value
            }
            sendMessage(body.value, data)

        })

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
                    console.log(res.data)
                    chatRoom.innerHTML = "";
                    const friendId = res.data.data.user.id
                    const roomId = res.data.data.conversation.id;
                    username.innerText = res.data.data.user.name;
                    conversationId.value = res.data.data.conversation.id;

                    Echo.join(`chat.${roomId}`)
                        .here((users) => {
                            console.log("join chanel success");
                        })
                        .joining((user) => {
                            console.log(user);
                            if (friendId == user.id) {
                                status.innerText = "online"
                            }
                        })
                        .leaving((user) => {
                            if (friendId == user.id) {
                                status.innerText = ""
                            }
                            console.log("leaving");
                        })
                        .error((error) => {
                            console.error(error);
                        })
                        .listen("SaveChat", (e) => {
                            console.log(e);
                            let html =
                                '<div class="flex w-full"><div class="bg-white w-fit rounded-l-xl rounded-tr-xl p-2 shadow"><p class="text-gray-800">' +
                                e.message +
                                "</p></div></div>";
                            chatRoom.insertAdjacentHTML("beforeend", html);
                            chatRoom.scrollTo({
                                top: chatRoom.scrollHeight,
                                behavior: "smooth",
                            });
                        })

                    let messages = res.data.data.conversation.messages;
                    if (messages.length > 0) {
                        for (let index = 0; index < messages.length; index++) {
                            const element = messages[index];
                            // console.log(element.user_id)
                            if (me == element.user_id) {
                                chatRoom.innerHTML +=
                                    '<div class="flex justify-end items-end w-full"><div class="bg-blue-500 rounded-l-xl rounded-tr-xl p-2 shadow"><p class="text-white">' +
                                    element.body +
                                    "</p></div></div>";
                            } else {
                                chatRoom.innerHTML +=
                                    '<div class="flex justify-start w-full"><div class="bg-white rounded-r-xl rounded-tl-xl p-2 shadow"><p class="text-gray-800">' +
                                    element.body +
                                    "</p></div></div>";
                            }
                        }
                    }

                })
                .catch((err) => {
                    console.log(err);
                });
        }
    </script>
</body>

</html>
