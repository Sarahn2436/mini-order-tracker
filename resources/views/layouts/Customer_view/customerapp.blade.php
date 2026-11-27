<html>

<head>
     <script src="https://stripe.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'customer app')</title>
        <style>
        .nav {
            background-color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .nav-links {
            display: flex;
        }

        .nav a {
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .nav a.active {
            background-color: #ddd;
            color: black;
        }

        .search-container {
            padding: 0 16px;
        }

        .search-container form {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .search-container input[type=text],
        .search-container button,
        .logout-btn {
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            vertical-align: middle;
            margin: 0;
            line-height: normal;
        }

        .search-container input[type=text] {
            padding: 6px 10px;
            font-size: 14px;
            border: none;
            border-radius: 4px 0 0 4px;
            outline: none;
            color: #1b1b1b;
        }

        .search-container button {
            padding: 6px 10px;
            background: #ddd;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
            color: #1b1b1b;
        }

        .search-container button:hover {
            background: #ccc;
        }

        .autocomplete {
            position: relative;
            display: inline-block;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
            color: #1b1b1b;
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff !important;
        }

        .logout-btn {
            background: #ddd;
            color: black;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-family: inherit;
            transition: background 0.2s ease;
        }

        .logout-btn:hover {
            background: #ccc;
        }

        body {
            font-family: "Lato", sans-serif;
        }

        .customer-service-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            border-radius: 8px;
            z-index: 1000;
            font-family: system-ui, -apple-system, sans-serif;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 120px;
            height: 40px;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .minimized-btn {
            width: 100%;
            height: 100%;
            background: transparent;
            color: white;
            border: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            outline: none;
        }

        .chat-expanded-view {
            display: flex;
            flex-direction: column;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
        }

        .chat-header {
            background: #222;
            color: white;
            padding: 14px 16px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close-x {
            color: #aaa;
            font-size: 16px;
        }
        .close-x:hover {
            color: white;
        }

        .chat-messages {
            flex: 1; 
            background: #2c2c2c;
            padding: 16px;
            overflow-y: auto;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .message {
            color: white;
            background: #444;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            max-width: 85%;
            line-height: 1.4;
            width: fit-content;
            overflow-wrap: break-word;
            word-break: break-word; 
        }

        .chat-bar {
            background: #222;
            padding: 10px;
            display: flex;
            gap: 8px;
        }
        .chat-bar input {
            flex: 1;
            padding: 8px 12px;
            background: #444;
            border: none;
            border-radius: 4px;
            color: white;
            outline: none;
        }
        .chat-bar input::placeholder {
            color: #aaa;
        }
        .send-btn {
            background: #333;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .send-btn:hover {
            background: #555;
        }

        .customer-service-container.active {
            width: 320px;
            height: 400px; 
        }

        .customer-service-container.active .minimized-btn {
            display: none;
        }
        .customer-service-container.active .chat-expanded-view {
            opacity: 1;
            visibility: visible;
        }

        .message.user {
            background: #007bff; 
            color: white;
            align-self: flex-end;
            margin-left: auto;
        }
    </style>

</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">
    <!-- Header/navigation -->
    <header class="w-full lg:max-w-6xl max-w-full text-sm mb-6 mx-auto">
        <nav class="nav">
            <div class="nav-links">
                <a href="{{ route('customer.orders') }}">{{ __("customer orders") }}</a>
                <a href="{{ route('customer.dashboard') }}">{{ __("customer dashboard") }}</a>
                <a href="{{ route('customer.checkout') }}" class="">{{ __("Checkout") }}</a>
                <a href="{{ route('customer.settings') }}" class="">{{ __("Account settings") }}</a>
            </div>

            <!-- nav container-->
            <div style="display: flex; align-items: center; gap: 16px; margin-left: auto; padding-right: 16px;">

                <!-- basket -->
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('customer.basket') }}">
                        <img src="{{ asset('images/mini basket icon.png') }}" alt="{{ __("Basket Icon") }}"
                            style="width:42px; height:42px; filter: brightness(0) invert(1);">
                    </a>
                </div>

                <!-- Search bar -->
                <div class="search-container" style="padding: 0;">
                    <form autocomplete="off" action="{{ route('customer.dashboard') }}" method="GET" style="display: flex; align-items: center; margin: 0;">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <div class="autocomplete" style="position: relative; display: inline-block;">
                        <input id="myInput" type="text" placeholder="{{ __("Search...") }}" name="search" value="{{ request('search') }}">
                    </div>
                    <button type="submit">{{ __("Go") }}</button>
                </form>

                </div>

                <script>
                var products = @json($productNames ?? []); 

                console.log("Autocomplete names loaded:", products);

                function autocomplete(inp, arr) {
                    var currentFocus;
                    
                    inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;
                    closeAllLists();
                    if (!val) { return false; }
                    currentFocus = -1;
                    
                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    
                    this.parentNode.appendChild(a);
                    
                    var resultsCount = 0; 
                    var maxResults = 5;
                    
                    for (i = 0; i < arr.length; i++) {
                    var matchIndex = arr[i].toUpperCase().indexOf(val.toUpperCase());
                    if (matchIndex > -1) {
                        
                        if (resultsCount >= maxResults) {
                            break;
                        }
                        
                        b = document.createElement("DIV");
                        b.innerHTML = arr[i].substr(0, matchIndex);
                        b.innerHTML += "<strong>" + arr[i].substr(matchIndex, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(matchIndex + val.length);
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        
                       b.addEventListener("click", function(e) {
                        inp.value = this.getElementsByTagName("input")[0].value;
                        
                        closeAllLists();

                        inp.form.submit(); 
                        });
                        a.appendChild(b);
                        
                        resultsCount++; 
                    }
                    }
                });

                    inp.addEventListener("keydown", function(e) {
                        var x = document.getElementById(this.id + "autocomplete-list");
                        if (x) x = x.getElementsByTagName("div");
                        if (e.keyCode == 40) {
                        currentFocus++;
                        addActive(x);
                        } else if (e.keyCode == 38) {
                        currentFocus--;
                        addActive(x);
                        } else if (e.keyCode == 13) {
                        if (currentFocus > -1) {
                            e.preventDefault(); 
                            if (x) x[currentFocus].click();
                        }
                        }
                    });

                    function addActive(x) {
                    if (!x) return false;
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);
                    x[currentFocus].classList.add("autocomplete-active");
                    }

                    function removeActive(x) {
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                    }

                    function closeAllLists(elmnt) {
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                        }
                    }
                    }

                    document.addEventListener("click", function (e) {
                        closeAllLists(e.target);
                    });
                }

                autocomplete(document.getElementById("myInput"), products); 
                </script>


                
                <!--logout-->
                <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        {{ __("Logout") }}
                    </button>
                </form>
            </div>
        </nav>

<!-- customer service stuff -->
<div id="chatWindow" class="customer-service-container">
    
    <!-- small button -->
    <button id="chatTrigger" class="minimized-btn" onclick="toggleChat()">{{ __("Need help?") }}</button>

    <!-- expanded chat -->
    <div class="chat-expanded-view">
        
        <!-- header -->
        <div class="chat-header" onclick="toggleChat()">
            <span>{{ __("Customer Support") }}</span>
            <span class="close-x">✕</span>
        </div>

        <!-- conversation history -->
        <div class="chat-messages">
            <div class="message bot">{!! __('Hello! How can we help you today?') !!}</div>
        </div>


        <!-- Input form  chat-bar -->
        <div class="chat-bar">
            <input type="text" id="chatInput" placeholder="{{ __("What's on your mind?") }}" onkeypress="handleKeyPress(event)">
            <button class="send-btn" onclick="sendMessage()">{{ __("Send") }}</button>
        </div>


    </div>

</div>

<script>
    let chatPollInterval = null;

    function toggleChat() {
        const container = document.getElementById('chatWindow');
        container.classList.toggle('active');
        
        if (container.classList.contains('active')) {
            setTimeout(() => {
                document.getElementById('chatInput').focus();
            }, 300);

            fetchCustomerMessages();

            clearInterval(chatPollInterval);
            chatPollInterval = setInterval(fetchCustomerMessages, 3000);
        } else {
            clearInterval(chatPollInterval);
        }
    }

    function fetchCustomerMessages() {
        const sessionId = "customer_{{ auth()->user()->id ?? 'guest' }}";;

        fetch(`/chat/messages/${sessionId}`)
            .then(res => res.json())
            .then(messages => {
             const messagesContainer = document.querySelector('.chat-messages');
                messagesContainer.innerHTML = '';

               const defaultMsg = document.createElement('div');
                defaultMsg.className = 'message bot';
                defaultMsg.innerText = @json(__('Hello! How can we help you today?'));
                messagesContainer.appendChild(defaultMsg);

                messages.forEach(msg => {
                    const bubble = document.createElement('div');
                    
                    bubble.className = msg.sender_type === 'customer' ? 'message user' : 'message';
                    bubble.innerText = msg.message;
                    
                    messagesContainer.appendChild(bubble);
                });

                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .catch(error => console.error('Error fetching stream records:', error));
    }

    function sendMessage() {
        const inputElement = document.getElementById('chatInput');
        const messageText = inputElement.value.trim();
        if (messageText === '') return;

        const sessionId = "customer_{{ auth()->user()->id ?? 'guest' }}"; 

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                session_id: sessionId,
                sender_type: 'customer',
                message: messageText
            })
        })
        .then(response => response.json())
        .then(() => {
            inputElement.value = '';
            fetchCustomerMessages();
        });
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }
</script>

    @yield('content')
</body>

</html>
